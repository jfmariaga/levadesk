<?php

namespace App\Jobs;

use App\Models\Comentario;
use App\Models\Ticket;
use App\Notifications\AnsCercaDeVencer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckAnsVencimiento implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Obtener todos los tickets que no están en estado 'Cumplido' (o el estado que uses)
        $tickets = Ticket::whereIn('estado_id', ['3','6','7', '8', '9', '10', '11', '12', '13', '14','15','16'])->get();
        $aceptacion = Ticket::where('estado_id', 6)->get();


        foreach ($tickets as $ticket) {
            // Obtener el ANS del ticket
            $ans = $ticket->ans;

            // Calcular el tiempo pasado desde la creación del ticket
            $tiempoPasado = now()->diffInSeconds($ticket->created_at);

            $tiempoPasadoSolucion = now()->diffInSeconds($ticket->tiempo_inicio_resolucion);

            $tiempoRestante = $ans->t_asignacion_segundos - $tiempoPasado;

            $tiempoRestanteSolucion = $ans->t_resolucion_segundos - $tiempoPasadoSolucion;

            // Notificar al usuario si quedan menos de 5 minutos
            if ($tiempoRestante <= 300 && !$ticket->notificado) {
                $ticket->asignado->notify(new AnsCercaDeVencer($ticket));
                $ticket->update(['notificado' => true]);
            }

            // if ($tiempoRestanteSolucion <= 300 && !$ticket->notificadoSulucion) {
            //     $ticket->asignado->notify(new AnsCercaDeVencer($ticket));
            //     $ticket->update(['notificadoSolucion' => true]);
            // }
        }

        // foreach ($aceptacion as $ticket) {
        //     $tiempoPasadoAceptacion = now()->diffInSeconds($ticket->tiempo_inicio_aceptacion);

        //     $tiempoRestanteAceptacion = $ans->t_aceptacion_segundos - $tiempoPasadoAceptacion;

        //     if ($tiempoRestanteAceptacion < 0 && $ticket->estado_id == 6) {
        //         $ticket->update(['estado_id' => 4]);
        //         Comentario::create([
        //             'ticket_id' => $ticket->id,
        //             'user_id' => 16,
        //             'comentario' => 'Tiempo agotado para la aceptación, se finaliza el ticket con una calificación de 5/5⭐',
        //             'calificacion' => 5,
        //         ]);
        //         $ticket->asignado->notify(new AnsCercaDeVencer($ticket));
        //     }
        // }
    }
}
