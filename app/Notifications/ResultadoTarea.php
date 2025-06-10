<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultadoTarea extends Notification implements ShouldQueue
{
    use Queueable;
    public $tarea;
    public $resultado;
    public $ticket;


    public function __construct($tarea, $resultado, $ticket)
    {
        $this->tarea = $tarea;
        $this->resultado = $resultado;
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Autorización de la tarea relacionada con el ticket '. $this->ticket->nomenclatura)
            ->line($this->resultado)
            ->line('Ticket ' . $this->ticket->nomenclatura)
            ->action('Ver aprobación', url('/home'));
    }

    public function toArray($notifiable)
    {
        return [
            'titulo' => $this->tarea,
            'ticket_id' => $this->ticket->id,
            'resultado' => $this->resultado,
        ];
    }
}
