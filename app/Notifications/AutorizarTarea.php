<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AutorizarTarea extends Notification implements ShouldQueue
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
            ->subject('Aprobación requerida para un nuevo transporte. Ticket '. $this->ticket->nomenclatura)
            ->line('El agente TI ' . $this->logueado . ' Necesita confirma esta tarea.')
            ->line('Descripción :  ' . $this->tarea->titulo)
            ->line('Por favor ingresa a LevaDesk y en tus notificaciones encontraras las aprobaciones pendientes')
            ->action('Ver aprobación', url('/home'));
    }

    public function toArray($notifiable)
    {
        return [
            'titulo' => $this->tarea,
            'ticket_id' => $this->ticket->id,
        ];
    }
}
