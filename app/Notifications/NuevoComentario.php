<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoComentario extends Notification
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
        $ultimoComentario = $this->ticket->comentarios->last();

        return (new MailMessage)
            ->subject('Nuevo comentario para el ticket ' . $this->ticket->nomenclatura)
            ->line($ultimoComentario->user->name . ' hizo el siguiente comentario:')
            ->line($ultimoComentario->comentario)
            ->action('Ver Ticket', url('/tickets/' . $this->ticket->id));
    }


    public function toArray($notifiable)
    {
        $ultimoComentario = $this->ticket->comentarios->last();

        return [
            'ticket_id' => $this->ticket->id,
            'nomenclatura' => $this->ticket->nomenclatura,
            'comentario' => $ultimoComentario->comentario,
            'usuario' => $ultimoComentario->user->name,
        ];
    }
}
