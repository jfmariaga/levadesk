<?php

namespace App\Jobs;

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
        $tickets = Ticket::whereNotIn('estado_id', [4, 5])->get();


        foreach ($tickets as $ticket) {
            // Obtener el ANS del ticket
            $ans = $ticket->ans;

            // Calcular el tiempo pasado desde la creación del ticket
            $tiempoPasado = now()->diffInSeconds($ticket->created_at);
            $tiempoRestante = $ans->t_asignacion_segundos - $tiempoPasado;

            // Notificar al usuario si quedan menos de 5 minutos
            if ($tiempoRestante <= 300 && !$ticket->notificado) {
                $ticket->asignado->notify(new AnsCercaDeVencer($ticket));
                $ticket->update(['notificado' => true]);
            }
        }
    }
}
