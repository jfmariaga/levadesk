<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComentarioRespuestaTercero extends Notification implements ShouldQueue
{
    use Queueable;

    public $comentario;

    public function __construct($comentario)
    {
        $this->comentario = $comentario;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $ticket = $this->comentario->ticket;
        $origen = $this->comentario->origen ?? 'Tercero';
        $nomenclatura = $ticket->nomenclatura ?? 'Ticket sin código';
        $titulo = $ticket->titulo ?? 'Sin título';

        $mail = (new MailMessage)
            ->subject("Respuesta del tercero en el ticket ##{$nomenclatura}##")
            ->greeting("Estimado equipo,")
            ->line("El tercero **{$origen}** ha respondido al ticket **{$nomenclatura} - {$titulo}**.")
            ->line('---')
            ->line($this->comentario->comentario)
            ->line('---')
            ->line("Puedes ingresar a **LevaDesk** para revisar los detalles del ticket y adjuntos.")
            ->action('Ver ticket en LevaDesk', url("/tickets/{$ticket->id}"))
            ->salutation("Atentamente,\nEl sistema de tickets LevaDesk");

        // 📎 Adjuntar archivos del comentario
        foreach ($ticket->archivos()->where('comentario_id', $this->comentario->id)->get() as $archivo) {
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
