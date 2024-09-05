<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AprobarSet extends Notification implements ShouldQueue
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
            ->subject('Aprobación requerida')
            ->line('El ticket ' . $this->ticket->nomenclatura . ' Requiere de la aprobación del SET de pruebas')
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
