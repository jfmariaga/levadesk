<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Reasignado extends Notification implements ShouldQueue
{
    use Queueable;

    protected $usuarioOld;
    protected $ticket;

    public function __construct($usuarioAsignado, $ticket)
    {
        $this->usuarioOld = $usuarioAsignado;
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('¡Hola! ' . $this->ticket->asignado->name)
            ->subject('Ticket reasignado')
            ->line($this->usuarioOld->name . ' Te reasigno el ticket ' . $this->ticket->nomenclatura)
            ->line('Estado: ' . $this->ticket->estado->nombre)
            ->line('Urgencia: ' . $this->ticket->urgencia->nombre)
            ->line('Titulo: ' . $this->ticket->titulo)
            ->action('Ver Tarea', url('/tickets/' . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'nomenclatura_ticket' => $this->ticket->nomenclatura,
            'usuario_anterior' => $this->usuarioOld->nombre,
            'estado' => $this->ticket->estado->nombre,
            'urgencia' => $this->ticket->urgencia->nombre,
            'titulo' => $this->ticket->titulo,
        ];
    }
}
