<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Finalizado extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comentario;

    public function __construct($comentario)
    {
        $this->comentario = $comentario;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Puedes agregar otros canales como SMS, Slack, etc.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Ticket finalizado ' . $this->comentario->ticket->nomenclatura)
            ->line($this->comentario->user->name . ' Aceptó la solución que proporcionaste para el ticket ' . $this->comentario->ticket->nomenclatura)
            ->line('Tu calificacion fue de ' . $this->comentario->calificacion .'/5⭐')
            ->line($this->comentario->comentario_calificacion ? 'Comentario: '. $this->comentario->comentario_calificacion :'Sin comentarios' )
            ->action('Ver Ticket', url('/tickets/' . $this->comentario->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->comentario->ticket->id,
            'nomenclatura' => $this->comentario->ticket->nomenclatura,
            'comentario' => $this->comentario->comentario,
            'comentario_calificacion' => $this->comentario->comentario_calificacion,
            'usuario' => $this->comentario->user->name,
        ];
    }
}
