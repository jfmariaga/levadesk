<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompletarAreaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail']; // puedes añadir 'database' si quieres que quede registrado
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Completa tu área en el sistema')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Notamos que aún no has indicado el área a la que perteneces dentro del sistema.')
            ->line('Es muy importante que completes esta información para que podamos organizar mejor la gestión y comunicación dentro de la empresa.')
            ->action('Indicar mi área', url('/home')) // ajusta la ruta a tu app
            ->line('Gracias por tu colaboración y por ayudarnos a mejorar.');
    }
}
