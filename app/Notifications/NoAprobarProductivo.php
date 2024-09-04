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
            ->subject('No se aprobó el paso a producción'. $this->ticket->nomenclatura)
            ->line('No se aprobó el paso a producción')
            ->line('Si requieres mas informacion comunicate con el aprobar TI '. $this->ticket->cambio->aprobadorTiCambio->name)
            ->line('El ticket '. $this->ticket->nomenclatura . ' ahora esta en estado: '. $this->ticket->estado->nombre  )
            ->action('Ver Ticket', url('/tickets/' . $this->ticket->id));
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

