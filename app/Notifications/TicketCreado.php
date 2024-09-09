<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCreado extends Notification implements ShouldQueue
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
            ->greeting('¡Hola! ' . $this->ticket->usuario->name)
            ->subject('Ticket Creado Exitosamente')
            ->line('Su ticket ha sido creado con éxito.')
            ->line('Titulo: ' . $this->ticket->titulo)
            ->line('Código del Ticket: ' . $this->ticket->nomenclatura)
            ->line('Estado: ' . $this->ticket->estado->nombre)
            ->line('Urgencia: ' . $this->ticket->urgencia->nombre)
            ->line('Agente TI Asignado: ' . $this->ticket->asignado->name);
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'nomenclatura' => $this->ticket->nomenclatura,
            'estado' => $this->ticket->estado->nombre,
            'urgencia' => $this->ticket->urgencia->nombre,
            'asignado_a' => $this->ticket->asignado->name,
            'titulo' => $this->ticket->titulo,
        ];
    }
}
