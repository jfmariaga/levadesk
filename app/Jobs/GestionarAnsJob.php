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
                Log::info("Ticket {$ticket->id} actualizado en {$ticket->updated_at}, días hábiles transcurridos: ");
                $this->finalizarPorInactividad($ticket);
            }
        }

        // 3) Tickets con 29 días sin actividad → notificar al usuario
        // $ticketsAviso = Ticket::whereNotIn('estado_id', [4, 5])
        //     ->whereDate('updated_at', '=', Carbon::now()->subDays(29)->toDateString())
        //     ->get();

        $ticketsAviso = Ticket::whereNotIn('estado_id', [4, 5])
            ->whereDate('updated_at', '=', Carbon::now()->subDays(29)->toDateString())
            ->whereNull('aviso_enviado_at')
            ->get();

        foreach ($ticketsAviso as $ticket) {
            $this->avisarFinalizacionProxima($ticket);
        }

        // 4) Tickets con más de 1 mes sin actividad → finalizarlos
        $ticketsMes = Ticket::whereNotIn('estado_id', [4, 5])
            ->where('updated_at', '<=', Carbon::now()->subMonth())
            ->get();

        foreach ($ticketsMes as $ticket) {
            $this->finalizarPorMesSinActividad($ticket);
        }
    }

    private function avisarFinalizacionProxima($ticket)
    {
        try {
            if ($ticket->usuario) {
                $ticket->usuario->notify(
                    new \App\Notifications\AvisoFinalizacionTicket($ticket)
                );

                // Marca como avisado
                $ticket->aviso_enviado_at = Carbon::now();
                $ticket->save();
            }
        } catch (\Throwable $e) {
            Log::warning("No se pudo enviar aviso de finalización al Ticket {$ticket->id}: {$e->getMessage()}");
        }
    }


    private function finalizarPorMesSinActividad($ticket)
    {
        $estadoAnterior = $ticket->estado->nombre;

        // Cambiar estado a 5 (Finalizado)
        $ticket->estado_id = 5;
        $ticket->save();

        // Crear comentario tipo 5 con calificación 5
        $comentario = Comentario::create([
            'ticket_id'              => $ticket->id,
            'user_id'                => 16, // sistema
            'comentario'             => '<p>Ticket finalizado automáticamente por inactividad mayor a 1 mes.</p>',
            'tipo'                   => 2,
            'calificacion'           => 5,
            'comentario_calificacion' => 'El sistema finalizó este ticket por inactividad.',
            'check_comentario'       => 0,
        ]);

        // Historial
        Historial::create([
            'ticket_id' => $ticket->id,
            'user_id'   => 16, // sistema
            'accion'    => 'Finalización automática por inactividad de 1 mes',
            'detalle'   => "El ticket fue finalizado automáticamente tras más de 1 mes sin actividad. Estado previo: {$estadoAnterior}.",
        ]);

        Log::info("Ticket {$ticket->id} finalizado automáticamente por 1 mes de inactividad.");
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

        while ($cursor->lessThanOrEqualTo($fin)) {
            // Forzamos la validación por fecha, no por hora
            if (FuncionesHelper::esDiaHabil($cursor->copy()->setHour(10))) {
                $contador++;
            }
            $cursor->addDay();
        }

        return $contador;
    }

    /**
     * Finaliza por inactividad (estados 12/16 con 5+ días hábiles sin actualización).
     * Cambia a estado 4, registra historial y (opcional) notifica.
     */
    private function finalizarPorInactividad($ticket): void
    {
        $estadoAnterior = $ticket->estado->nombre;

        // Cambiamos a "Finalizado" (4)
        $ticket->estado_id = 4;
        $ticket->save();

        // Historial
        Historial::create([
            'ticket_id' => $ticket->id,
            'user_id'   => null, // sistema
            'accion'    => 'Finalización automática por inactividad',
            'detalle'   => "El ticket fue finalizado automáticamente por inactividad. Estado previo: {$estadoAnterior}. Han transcurrido 5 días hábiles sin respuesta del usuario.",
        ]);
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
