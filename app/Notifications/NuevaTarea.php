<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class NuevaTarea extends Notification implements ShouldQueue
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
                    ->subject('Nueva Tarea Asignada. Ticket: ' . $this->ticket->nomenclatura)
                    ->line('Se te ha asignado una nueva tarea con el título: "' . $this->tarea->titulo . '"')
                    ->line('La tarea pertenece al ticket: ' . $this->ticket->nomenclatura)
                    ->line('Mas información en la descripción de la tarea')
                    ->line('Debes completar esta tarea antes de: ' . Carbon::parse($this->tarea->fecha_cumplimiento)->format('d-m-Y H:i'))
                    ->action('Ver Tarea', url('/gestionar?ticket_id='  . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'tarea_id' => $this->tarea->id,
            'titulo_tarea' => $this->tarea->titulo,
            'ticket_id' => $this->ticket->id,
            'nomenclatura_ticket' => $this->ticket->nomenclatura,
            'fecha_cumplimiento' => $this->tarea->fecha_cumplimiento,
        ];
    }
}
