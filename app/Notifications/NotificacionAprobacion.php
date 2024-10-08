<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionAprobacion extends Notification implements ShouldQueue
{
    use Queueable;

    protected $aprobacion;
    protected $ticket;

    public function __construct($aprobacion, $ticket)
    {
        $this->aprobacion = $aprobacion;
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Aprobación requerida. Ticket '. $this->ticket->nomenclatura)
            ->line('El agente TI ' . $this->ticket->asignado->name . ' Te ha asignado como aprobador para el ticket: ' . $this->ticket->nomenclatura)
            ->line('Descripción :  ' . $this->ticket->titulo)
            ->line('Por favor ingresa a LevaDesk y en tus notificaciones encontraras las aprobaciones pendientes')
            ->action('Ver aprobación', url('/home'));
    }

    public function toArray($notifiable)
    {
        return [
            'agente_ti' => $this->ticket->asignado->name,
            'nomenclatura_ticket' => $this->ticket->nomenclatura,
        ];
    }
}
