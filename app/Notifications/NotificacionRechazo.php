<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionRechazo extends Notification implements ShouldQueue
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
                    ->subject('Flujo de gestión de acceso ticket ' .$this->ticket->nomenclatura)
                    ->line('El aprobador TI '. $this->ticket->aprobacion->aprobadorTi->name .' No aprobó los accesos solicitados por el usuario en el ticket '. $this->ticket->nomenclatura)
                    ->action('Ver Ticket', url('/tickets/' . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'nomenclatura' => $this->ticket->nomenclatura,
            'aprobador_ti' => $this->ticket->aprobacion->aprobadorTi->name ,
        ];
    }
}
