<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GraphService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Ticket;
use App\Models\Comentario;
use App\Models\Historial;
use App\Models\FlujoTercero;

class SyncGraphRespuestasTickets extends Command
{
    protected $signature = 'tickets:sync-respuestas-graph';
    protected $description = 'Lee correos de soporte desde Graph API y documenta respuestas en los tickets de LevaDesk';

    public function handle(GraphService $graph)
    {
        try {
            // 📥 Correos de terceros permitidos
            $correosPermitidos = FlujoTercero::where('activo', true)
                ->pluck('destinatarios')
                ->flatten()
                ->map(function ($item) {
                    if (is_string($item)) {
                        return strtolower(trim($item));
                    }
                    return null;
                })
                ->filter()
                ->unique()
                ->toArray();

            $messages = $graph->getInboxMessages(100);

            foreach ($messages as $msg) {
                $subject = $msg['subject'] ?? '';
                $bodyHtml = $msg['uniqueBody']['content'] ?? $msg['body']['content'] ?? '';
                $from = strtolower($msg['from']['emailAddress']['address'] ?? '');

                // 🔒 Filtrar remitentes
                if (!in_array($from, $correosPermitidos)) {
                    Log::channel('graph')->info("Correo ignorado por remitente no permitido", [
                        'from'    => $from,
                        'subject' => $subject,
                    ]);
                    continue;
                }

                // 🧾 Buscar nomenclatura ##XYZ##
                if (preg_match('/##(.*?)##/', $subject, $m)) {
                    $nomenclatura = trim($m[1]);
                    $ticket = Ticket::where('nomenclatura', $nomenclatura)->first();

                    if ($ticket) {
                        // ✨ LIMPIEZA DEL CUERPO
                        $contenido = $this->limpiarBody($bodyHtml);

                        if (empty($contenido)) {
                            Log::channel('graph')->warning("Cuerpo vacío o no relevante para {$nomenclatura}");
                            continue;
                        }

                        // 🧩 Buscar flujo asociado a la aplicación del ticket
                        $flujo = FlujoTercero::with('tercero')
                            ->where('aplicacion_id', $ticket->aplicacion_id)
                            ->where('activo', true)
                            ->first();

                        $terceroNombre = $flujo?->tercero?->nombre ?? 'Tercero desconocido';

                        // 🗒️ Crear comentario
                        $comentario = Comentario::create([
                            'ticket_id'  => $ticket->id,
                            'user_id'    => 16,
                            'comentario' => trim($contenido),
                            'tipo'       => 10,
                            'origen'     => $terceroNombre,
                        ]);

                        // 📎 Adjuntos
                        $attachmentsCount = 0;
                        if (!empty($msg['hasAttachments']) && $msg['hasAttachments'] === true) {
                            $attachments = $graph->getAttachments($msg['id']);
                            foreach ($attachments as $att) {
                                if ($att['@odata.type'] === '#microsoft.graph.fileAttachment') {
                                    $filename = $att['name'];
                                    $content  = base64_decode($att['contentBytes']);

                                    $path = "public/tickets/{$ticket->id}/" . $filename;
                                    Storage::put($path, $content);

                                    $ticket->archivos()->create([
                                        'ruta'          => $path,
                                        'comentario_id' => $comentario->id,
                                    ]);

                                    $attachmentsCount++;
                                }
                            }
                        }

                        // 🕓 Registrar historial
                        Historial::create([
                            'ticket_id' => $ticket->id,
                            'user_id'   => null,
                            'accion'    => 'Respuesta de tercero',
                            'detalle'   => "Respuesta recibida de {$terceroNombre}",
                        ]);

                        // 🔔 Notificar a los involucrados
                        if ($ticket->usuario) {
                            $ticket->usuario->notify(new \App\Notifications\ComentarioRespuestaTercero($comentario));
                        }
                        if ($ticket->asignado) {
                            $ticket->asignado->notify(new \App\Notifications\ComentarioRespuestaTercero($comentario));
                        }
                        if ($ticket->colaboradors) {
                            foreach ($ticket->colaboradors as $colaborador) {
                                $colaborador->user->notify(new \App\Notifications\ComentarioRespuestaTercero($comentario));
                            }
                        }

                        $this->info("✅ Ticket {$ticket->nomenclatura} actualizado con respuesta de {$from}");
                        Log::channel('graph')->info("Ticket actualizado con respuesta", [
                            'ticket'      => $ticket->nomenclatura,
                            'from'        => $from,
                            'subject'     => $subject,
                            'attachments' => $attachmentsCount,
                            'preview'     => mb_substr($contenido, 0, 100)
                        ]);
                    } else {
                        Log::channel('graph')->warning("No se encontró ticket para la nomenclatura", [
                            'nomenclatura' => $nomenclatura,
                            'from'         => $from,
                            'subject'      => $subject,
                        ]);
                    }
                }

                // 📬 Marcar como leído
                $graph->markAsRead($msg['id']);
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            Log::channel('graph')->error("Error ejecutando sync de correos", [
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * 🧹 Limpia el cuerpo del correo dejando solo la respuesta principal
     */
    private function limpiarBody($bodyHtml)
    {
        // 1️⃣ Eliminar etiquetas HTML innecesarias
        $texto = strip_tags($bodyHtml);

        // 2️⃣ Convertir entidades HTML
        $texto = html_entity_decode($texto, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 3️⃣ Quitar saltos excesivos
        $texto = preg_replace("/(\r?\n){2,}/", "\n", $texto);

        // 4️⃣ Cortar justo antes de los indicios de mensajes previos o firmas
        $patrones = [
            '/(De:|From:).*/si',
            '/(On .* wrote:)/si',
            '/El .* escribió:/si',
            '/-----Mensaje original-----/si',
            '/LevaDesk.*Estimado equipo.*/si',
            '/Este mensaje puede contener.*$/si',
            '/ProteccionDatos.*$/si',
            '/Firma levapan.*$/si',
            '/Grupo Levapan.*$/si',
            '/http:\/\/www\.stratesys-ts\.com.*/si', // corta al ver firma del tercero
        ];

        foreach ($patrones as $pattern) {
            if (preg_match($pattern, $texto, $m, PREG_OFFSET_CAPTURE)) {
                $texto = substr($texto, 0, $m[0][1]);
                break;
            }
        }

        // 5️⃣ Quitar espacios en blanco y caracteres raros
        $texto = trim(preg_replace('/\s+/', ' ', $texto));

        // 6️⃣ Evitar respuestas vacías o automáticas
        if (strlen($texto) < 5) {
            return null;
        }

        return $texto;
    }
}
