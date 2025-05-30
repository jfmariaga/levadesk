<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EditarTarea extends Notification implements ShouldQueue
{
    use Queueable;
    public $tarea;
    public $logueado;
    public $ticket;

    public function __construct($tarea, $ticket,$logueado)
    {
        $this->tarea = $tarea;
        $this->ticket = $ticket;
        $this->logueado = $logueado;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Modificar la tarea del Ticket '. $this->ticket->nomenclatura)
            ->line( $this->logueado . ' Solicito modificar la tarea' , $this->tarea->titulo)
            ->line('Por favor ingresa a LevaDesk y modifica la tarea para que el ticket pueda continuar')
            ->action('Ver ticket', url('/gestionar?ticket_id='  . $this->ticket->id));
    }

    public function toArray($notifiable)
    {
        return [
            'titulo' => $this->tarea,
            'ticket_id' => $this->ticket->id,
        ];
    }
}
