<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RechazoFlujoCambio extends Notification implements ShouldQueue
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
                    ->subject('Solicitud Rechazada ')
                    ->line('El flujo de aprobación de cambio relacionado con el ticket ' . $this->ticket->nomenclatura . '.')
                    ->line('No fue aprobada por ' . $this->ticket->cambio->aprobadorFuncionalCambio->name)
                    ->line('Motivo: ' . $this->ticket->cambio->comentarios_funcional)
                    ->line('El estado del ticket es '. $this->ticket->estado->nombre)
                    ->action('Ver Ticket', url('/gestionar?ticket_id=' . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'nomenclatura' => $this->ticket->nomenclatura,
            'aprobador_funcional' => $this->ticket->cambio->aprobadorFuncionalCambio->name ,
            'estado' => $this->ticket->estado->nombre,
            'comentario' =>$this->ticket->cambio->comentarios_funcional,
        ];
    }
}
