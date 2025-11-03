<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;


class NuevoComentarioTercero extends Notification implements ShouldQueue
{
    use Queueable;

    public $comentario;
    public $ticket;

    public function __construct($comentario)
    {
        $this->comentario = $comentario;
        $this->ticket = $comentario->ticket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $autor = $this->comentario->user->name ?? 'Usuario desconocido';
        $ticket = $this->ticket;

        $mail = (new MailMessage)
            ->subject("##{$ticket->nomenclatura}## Nuevo comentario en el ticket {$ticket->titulo}")
            ->greeting("Estimado equipo,")
            ->line("Reciban un cordial saludo.")
            ->line("Escalamos a su equipo una actualización relacionada con el siguiente ticket de soporte.")
            ->line("**Ticket:** {$ticket->nomenclatura} - {$ticket->titulo}")
            ->line("**Usuario solicitante:** {$ticket->usuario->name} ({$ticket->usuario->email})")
            ->line("**Comentario realizado por:** {$autor}")
            ->line("---")
            ->line(new HtmlString($this->comentario->comentario))
            ->line("---")
            ->line("Agradecemos su pronta atención y quedamos atentos a cualquier información adicional que necesiten para avanzar con la solución.")
            ->salutation("Saludos cordiales,\nEl sistema de tickets LevaDesk");

        // 👉 Adjuntar archivos si los hay
        foreach ($this->comentario->archivos as $archivo) {
            $path = storage_path('app/' . $archivo->ruta);
            if (file_exists($path)) {
                $mail->attach($path, [
                    'as'   => basename($archivo->ruta),
                    'mime' => mime_content_type($path),
                ]);
            }
        }

        return $mail;
    }
}
