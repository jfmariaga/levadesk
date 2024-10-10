<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoAprobarProductivo extends Notification implements ShouldQueue
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
            ->subject('No se aprobó el paso a producción. Ticket' . $this->ticket->nomenclatura)
            ->line('No se aprobó el paso a producción')
            ->line('Si requieres mas información comunícate con el aprobar TI ' . $this->ticket->cambio->aprobadorTiCambio->name)
            ->line('El ticket ' . $this->ticket->nomenclatura . ' ahora esta en estado: ' . $this->ticket->estado->nombre)
            ->action('Ver Ticket', url('/gestionar?ticket_id='  . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'nomenclatura' => $this->ticket->nomenclatura,
            'estado' => $this->ticket->estado->nombre,
        ];
    }
}
