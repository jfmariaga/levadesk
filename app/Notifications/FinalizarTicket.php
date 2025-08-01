<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinalizarTicket extends Notification implements ShouldQueue
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
            ->subject('Aprobación para finalizar ticket '. $this->ticket->nomenclatura)
            ->line('El Agente TI encargado del ticket ' . $this->ticket->nomenclatura . 'pidió finalizarlo')
            ->line('Por favor, actúa con prontitud para cumplir con los tiempos establecidos.')
            ->action('Ver Ticket', url('/gestionar?ticket_id='  . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'titulo' => $this->ticket->nomenclatura,
        ];
    }
}
