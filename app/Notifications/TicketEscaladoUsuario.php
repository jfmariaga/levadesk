<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketEscaladoUsuario extends Notification implements ShouldQueue
{
    use Queueable;
    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Tu ticket ##{$this->ticket->nomenclatura}## ha sido escalado")
            ->greeting("Hola {$this->ticket->usuario->name},")
            ->line("Tu ticket **{$this->ticket->nomenclatura} - {$this->ticket->titulo}** ha sido escalado al tercero **{$this->ticket->tercero->nombre}**.")
            ->line("Durante este proceso, el ANS se encuentra detenido.")
            ->line("Serás notificado cuando haya una respuesta del tercero o el ticket retome su atención normal.")
            ->salutation("Atentamente,\nEl equipo de soporte LevaDesk");
    }
}
