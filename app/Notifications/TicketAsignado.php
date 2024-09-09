<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAsignado extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Puedes agregar otros canales como SMS, Slack, etc.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('¡Hola! ' . $this->ticket->asignado->name)
            ->subject('Nuevo Ticket Asignado')
            ->line('Se le ha asignado un nuevo ticket.')
            ->line('Generado por: ' . $this->ticket->usuario->name)
            ->line('Código del Ticket: ' . $this->ticket->nomenclatura)
            ->line('Estado: ' . $this->ticket->estado->nombre)
            ->line('Urgencia: ' . $this->ticket->urgencia->nombre)
            ->line('Titulo: ' . $this->ticket->titulo)
            ->action('Ver Ticket',url('/home?ticket_id=' . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'nomenclatura' => $this->ticket->nomenclatura,
            'estado' => $this->ticket->estado->nombre,
            'urgencia' => $this->ticket->urgencia->nombre,
            'titulo' => $this->ticket->titulo,
        ];
    }
}
