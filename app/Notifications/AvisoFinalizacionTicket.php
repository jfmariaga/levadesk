<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AvisoFinalizacionTicket extends Notification implements ShouldQueue
{
    use Queueable;

    private $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail']; // O 'database' si quieres que aparezca en la campanita
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tu ticket será finalizado automáticamente')
            ->greeting('Hola ' . $this->ticket->usuario->name . ' 👋')
            ->line("Tu ticket #{$this->ticket->id} no ha tenido actividad en casi un mes.")
            ->line("Si no registras actividad en las próximas 24 horas, será finalizado automáticamente por el sistema.")
            ->action('Ver ticket', url("/tickets/{$this->ticket->id}"))
            ->line('Gracias por tu atención.');
    }
}
