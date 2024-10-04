<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NuevoComentario extends Notification implements ShouldQueue
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
            ->subject('Nuevo comentario para el ticket ' . $this->comentario->ticket->nomenclatura)
            ->line($this->comentario->user->name . ' hizo el siguiente comentario:')
            ->line(new HtmlString($this->comentario->comentario))
            ->line('Ticket: '.$this->comentario->ticket->nomenclatura)
            ->action('Ver Ticket', url('/verTicket?ticket_id='  . $this->comentario->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->comentario->ticket->id,
            'nomenclatura' => $this->comentario->ticket->nomenclatura,
            'comentario' => $this->comentario->comentario,
            'usuario' => $this->comentario->user->name,
        ];
    }
}
