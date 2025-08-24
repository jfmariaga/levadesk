<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Helpers\FuncionesHelper;
use App\Models\Comentario;
use App\Models\Historial;
use App\Notifications\Finalizado;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GestionarAnsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        // 1) Lógica existente de ANS (la dejo intacta)
        $tickets = Ticket::whereNotIn('estado_id', [4, 5, 8, 9, 10, 11, 13])->get();
        foreach ($tickets as $ticket) {
            $this->actualizarTiempoAns($ticket);
        }

        // 2) NUEVA lógica: finalizar por inactividad (estados 12 y 16 con 5 días hábiles sin updates)
        $ticketsInactivos = Ticket::whereIn('estado_id', [12, 16])->get();
        foreach ($ticketsInactivos as $ticket) {
            if ($this->diasHabilesDesde($ticket->updated_at, Carbon::now()) >= 5) {
                $this->finalizarPorInactividad($ticket);
            }
        }
    }

    private function actualizarTiempoAns($ticket)
    {
        $ahora = Carbon::now();
        $tiempoRestante = $ticket->tiempo_restante;

        // Verifica si estamos en horario laboral
        if (FuncionesHelper::esDiaHabil($ahora)) {
            // Descuenta 60 segundos por ejecución (ajusta según tu frecuencia real del Job)
            $tiempoRestante = max(0, (int)$tiempoRestante - 60);
        }

        $ticket->tiempo_restante = $tiempoRestante;
        $ticket->save();

        // Cierre automático si se acabó el tiempo y no está cerrado
        if ($ticket->tiempo_inicio_aceptacion != null && $ticket->tiempo_restante <= 0 && !in_array($ticket->estado_id, [4, 5])) {
            $this->cerrarTicketAutomaticamente($ticket);
        }
    }

    /**
     * Cuenta cuántos días hábiles hay entre dos fechas (inclusive el día final si es hábil).
     * Usa FuncionesHelper::esDiaHabil($fecha) para respetar festivos/horarios que tengas allí.
     */
    private function diasHabilesDesde($desde, $hasta): int
    {
        $inicio = Carbon::parse($desde)->startOfDay();
        $fin    = Carbon::parse($hasta)->startOfDay();

        // Si por alguna razón updated_at es futuro, no contamos nada
        if ($inicio->greaterThan($fin)) {
            return 0;
        }

        $contador = 0;
        $cursor = $inicio->copy();

        // Recorremos día a día sumando únicamente días hábiles
        while ($cursor->lessThanOrEqualTo($fin)) {
            if (FuncionesHelper::esDiaHabil($cursor)) {
                $contador++;
            }
            // siguiente día
            $cursor->addDay()->startOfDay();
        }

        return $contador;
    }

    /**
     * Finaliza por inactividad (estados 12/16 con 5+ días hábiles sin actualización).
     * Cambia a estado 4, registra historial y (opcional) notifica.
     */
    private function finalizarPorInactividad($ticket): void
    {
        $estadoAnterior = $ticket->estado_id;

        // Cambiamos a "Finalizado" (4)
        $ticket->estado_id = 4;
        $ticket->save();

        // Historial
        Historial::create([
            'ticket_id' => $ticket->id,
            'user_id'   => null, // sistema
            'accion'    => 'Finalización automática por inactividad',
            'detalle'   => "El ticket fue finalizado automáticamente por inactividad. Estado previo: {$estadoAnterior}. Han transcurrido 5 o más días hábiles sin actualizaciones (updated_at).",
        ]);

        // (Opcional) Notificar al asignado. Si tu notificación requiere Comentario, puedes pasar null o crear uno.
        try {
            if ($ticket->asignado) {
                $ticket->asignado->notify(new Finalizado(null));
            }
        } catch (\Throwable $e) {
            Log::warning("No se pudo notificar finalización automática del Ticket ID {$ticket->id}: {$e->getMessage()}");
        }
    }

    private function cerrarTicketAutomaticamente($ticket)
    {
        // Busca o crea un comentario de tipo 2 (si existe lo actualiza)
        $comentario = Comentario::where('ticket_id', $ticket->id)
            ->where('tipo', 2)
            ->first();

        if ($comentario) {
            $comentario->update([
                'calificacion'            => 5,
                'comentario_calificacion' => 'La solución fue aceptada por el sistema ya que se superó el tiempo máximo de aceptación',
            ]);
        } else {
            Log::warning("Comentario tipo 2 para el Ticket ID {$ticket->id} no encontrado.");
        }

        // Cambia el estado del ticket a "Cerrado" (estado_id = 4)
        $ticket->estado_id = 4;
        $ticket->save();

        // Historial
        Historial::create([
            'ticket_id' => $ticket->id,
            'user_id'   => null, // Usuario automático
            'accion'    => 'Calificación de solución',
            'detalle'   => "El sistema calificó la solución con 5 estrellas.",
        ]);

        // Notifica al asignado sobre el cierre del ticket
        try {
            if ($ticket->asignado) {
                $ticket->asignado->notify(new Finalizado($comentario));
            }
        } catch (\Throwable $e) {
            Log::warning("No se pudo notificar cierre automático del Ticket ID {$ticket->id}: {$e->getMessage()}");
        }
    }
}
