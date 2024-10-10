<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RechazoFlujo extends Notification implements ShouldQueue
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
                    ->subject('Solicitud Rechazada. Ticket '. $this->ticket->nomenclatura)
                    ->line('La solicitud de gestión de acceso relacionada con el ticket ' . $this->ticket->nomenclatura . '.')
                    ->line('No fue aprobada por ' . $this->ticket->aprobacion->aprobadorFuncional->name)
                    ->line('Motivo: ' . $this->ticket->aprobacion->comentarios_funcional)
                    ->line('El estado del ticket es '. $this->ticket->estado->nombre)
                    ->action('Ver Ticket', url('/gestionar?ticket_id=' . $this->ticket->id));
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
