<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketSapFiNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $usuarioNombre = $this->ticket->usuario->name ?? 'Usuario desconocido';
        $usuarioCorreo = $this->ticket->usuario->email ?? 'Correo no disponible';

        $mail = (new MailMessage)
            ->subject("##{$this->ticket->nomenclatura}## {$this->ticket->titulo}")
            ->greeting("Estimado equipo,")
            ->line("Reciban un cordial saludo.")
            ->line("Escalamos a su equipo el siguiente ticket de soporte.")
            ->line("**Ticket:** {$this->ticket->nomenclatura} - {$this->ticket->titulo}")
            ->line("**Prioridad:** {$this->ticket->urgencia->nombre}")
            ->line("**Usuario solicitante:** {$usuarioNombre} ({$usuarioCorreo})")
            ->line("**Descripción:**")
            ->line($this->ticket->descripcion)
            ->line("Agradezco su pronta atención y quedo atenta a cualquier información adicional que necesiten para avanzar con la solución.")
            ->salutation("Saludos cordiales,");

        // 👉 Adjuntar archivos
        foreach ($this->ticket->archivos as $archivo) {
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
