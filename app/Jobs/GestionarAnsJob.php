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
        $tiempoRestante = $ticket->tiempo_restante;

        // Verifica si estamos en horario laboral
        if (FuncionesHelper::esDiaHabil($ahora)) {
            // Descontamos una cantidad pequeña de tiempo en cada ejecución del job
            // Esto simula el paso de segundos en el horario laboral
            $tiempoRestante = max(0, $tiempoRestante - 60); // Descuenta 60 segundos, puedes ajustar según frecuencia del job
        }

        // Guarda el tiempo restante en la base de datos
        $ticket->tiempo_restante = $tiempoRestante;
        $ticket->save();

        // Cierre automático si el tiempo restante es 0 y el ticket no está cerrado
        if ($ticket->tiempo_inicio_aceptacion != null && $ticket->tiempo_restante <= 0 && !in_array($ticket->estado_id, [4, 5])) {
            $this->cerrarTicketAutomaticamente($ticket);
        }
    }


    private function cerrarTicketAutomaticamente($ticket)
    {
        // Busca o crea un comentario de tipo 2
        $comentario = Comentario::where('ticket_id', $ticket->id)->where('tipo', 2)->first();
        if ($comentario) {
            $comentario->update([
                'calificacion' => 5,
                'comentario_calificacion' => 'La solución fue aceptada por el sistema ya que se superó el tiempo máximo de aceptación'
            ]);
        } else {
            Log::warning("Comentario tipo 2 para el Ticket ID {$ticket->id} no encontrado.");
        }


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
