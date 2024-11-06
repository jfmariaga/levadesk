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
        Log::info('GestionarAnsJob iniciado');
        // $tickets = Ticket::whereNotIn('estado_id', ['FINALIZADO', 'RECHAZADO'])->get();
        $tickets = Ticket::whereNotIn('estado_id', [4, 5, 8, 9, 10, 11, 12, 13, 16])->get();

        foreach ($tickets as $ticket) {
            $this->actualizarTiempoAns($ticket);
        }
        Log::info('GestionarAnsJob finalizado');
    }

    private function actualizarTiempoAns($ticket)
    {
        $ahora = Carbon::now();
        $ans = $ticket->ans;
        $tiempoRestante = 0;

        // Verifica si el ticket no tiene una prioridad asignada (caso inicial)
        if ($ticket->prioridad === null) {
            $tiempoInicio = $ticket->created_at ? Carbon::parse($ticket->created_at) : null;
            $tiempoTotalAns = $ans->t_asignacion_segundos;

            if (FuncionesHelper::esDiaHabil($ahora) && $tiempoInicio) {
                $tiempoTranscurrido = $tiempoInicio->diffInSeconds($ahora);
                $tiempoRestante = max(0, $tiempoTotalAns - $tiempoTranscurrido);
            } else {
                $tiempoRestante = $ticket->tiempo_restante;
            }

            // Caso de resolución (cuando `tiempo_inicio_aceptacion` es nulo pero `tiempo_inicio_resolucion` tiene valor)
        } elseif ($ticket->tiempo_inicio_aceptacion === null) {
            $inicioAns = $ticket->tiempo_inicio_resolucion ? Carbon::parse($ticket->tiempo_inicio_resolucion) : null;
            $tiempoTotalAns = $ans->t_resolucion_segundos;

            if (FuncionesHelper::esDiaHabil($ahora) && $inicioAns) {
                $tiempoTranscurrido = $inicioAns->diffInSeconds($ahora);
                $tiempoRestante = max(0, $tiempoTotalAns - $tiempoTranscurrido);
            } else {
                $tiempoRestante = $ticket->tiempo_restante;
            }

            // Caso de aceptación (cuando `tiempo_inicio_aceptacion` tiene valor)
        } else {
            $inicioAns = $ticket->tiempo_inicio_aceptacion ? Carbon::parse($ticket->tiempo_inicio_aceptacion) : null;
            $tiempoTotalAns = $ans->t_aceptacion_segundos;

            if (FuncionesHelper::esDiaHabil($ahora) && $inicioAns) {
                $tiempoTranscurrido = $inicioAns->diffInSeconds($ahora);
                $tiempoRestante = max(0, $tiempoTotalAns - $tiempoTranscurrido);
            } else {
                $tiempoRestante = $ticket->tiempo_restante;
            }
        }

        // Guarda el tiempo restante en la base de datos
        $ticket->tiempo_restante = $tiempoRestante;
        $ticket->save();

        // if ($ticket->tiempo_inicio_aceptacion != null) {
        //     if ($ticket->tiempo_restante <= 0 && $ticket->estado_id != 4) {
        //         $this->cerrarTicketAutomaticamente($ticket);
        //     }
        // }
    }

    private function cerrarTicketAutomaticamente($ticket)
    {
        // Busca o crea un comentario de tipo 2
        $comentario = Comentario::firstOrCreate(
            ['ticket_id' => $ticket->id, 'tipo' => 2],
            ['calificacion' => 5, 'comentario_calificacion' => 'La solución fue aceptada por el sistema ya que se superó el tiempo máximo de aceptación']
        );

        // Cambia el estado del ticket a "Cerrado" (estado_id = 4)
        $ticket->estado_id = 4;
        $ticket->save();

        // Crea una entrada en el historial
        Historial::create([
            'ticket_id' => $ticket->id,
            'user_id' => null, // Usuario automático
            'accion' => 'Calificación de solución',
            'detalle' => "El sistema calificó la solución con 5 estrellas."
        ]);

        // Notifica al asignado sobre el cierre del ticket
        $ticket->asignado->notify(new Finalizado($comentario));

        Log::info("Ticket ID {$ticket->id} cerrado automáticamente por el sistema.");
    }
}
