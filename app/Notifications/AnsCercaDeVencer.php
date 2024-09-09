<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnsCercaDeVencer extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // O los canales que desees usar
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('¡Hola!' . $this->ticket->asignado->name)
            ->subject('Aviso de ANS a punto de vencer')
            ->line('El ANS del ticket "' . $this->ticket->nomenclatura . '" está a punto de vencer.')
            ->action('Ver Ticket', url('/tickets/' . $this->ticket->id))
            ->line('Por favor, actúa con prontitud para cumplir con los tiempos establecidos.');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'titulo' => $this->ticket->nomenclatura,
        ];
    }
}
