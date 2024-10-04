<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstadoTarea extends Notification
implements ShouldQueue
{
    use Queueable;

    protected $tarea;
    protected $ticket;

    public function __construct($tarea, $ticket)
    {
        $this->tarea = $tarea;
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
            ->subject('Cambio de estado para la tarea: ' . $this->tarea->titulo)
            ->line('Cambio de estado para la tarea: ' . $this->tarea->titulo)
            ->line('La tarea pertenece al ticket: ' . $this->ticket->nomenclatura)
            ->line('Estado: ' . $this->tarea->estado)
            ->action('Ver Tarea', url('/gestionar?ticket_id='  . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'tarea_id' => $this->tarea->id,
            'asignado' => $this->ticket->asignado->name,
            'titulo_tarea' => $this->tarea->titulo,
            'ticket_id' => $this->ticket->id,
            'nomenclatura_ticket' => $this->ticket->nomenclatura,
            'tarea_estado' => $this->tarea->estado,
        ];
    }
}
