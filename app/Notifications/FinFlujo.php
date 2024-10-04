<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinFlujo extends Notification implements ShouldQueue
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
            ->subject('Flujo de gestión de acceso finalizado')
            ->line('El flujo de gestión de acceso relacionado con el Ticket: ' . $this->ticket->nomenclatura . ' Ha finalizado')
            ->line('Resultado: ' . $this->ticket->aprobacion->estado)
            ->line('Estado del ticket: '. $this->ticket->estado->nombre)
            ->action('Ver Ticket', url('/gestionar?ticket_id='  . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'nomenclatura' => $this->ticket->nomenclatura,
            'estado' => $this->ticket->aprobacion->estado,
            'estado_ticket' => $this->ticket->estado->nombre,
        ];
    }
}
