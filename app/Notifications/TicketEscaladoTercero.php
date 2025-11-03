<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class TicketEscaladoTercero extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $justificacion;

    public function __construct($ticket, $justificacion)
    {
        $this->ticket = $ticket;
        $this->justificacion = $justificacion;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $usuario = $this->ticket->usuario->name ?? 'Usuario desconocido';
        $correo = $this->ticket->usuario->email ?? 'N/A';
        $agente = $this->ticket->asignado->name ?? 'Sin asignar';
        $prioridad = $this->ticket->prioridad ?? 'No definida';
        $aplicacion = $this->ticket->aplicacion->nombre ?? 'Sin aplicación';
        $sociedad = $this->ticket->aplicacion->sociedad->nombre ?? 'N/A';

        // 🧩 Últimos 3 comentarios relevantes para contexto
        $comentarios = $this->ticket->comentarios()
            ->where('tipo', 0)
            ->latest()
            ->take(3)
            ->get();

        $mail = (new MailMessage)
            ->subject("Escalamiento de Ticket ##{$this->ticket->nomenclatura}## - {$this->ticket->titulo}")
            ->greeting("Estimado equipo,")
            ->line("Reciban un cordial saludo.")
            ->line("Escalamos a su equipo el siguiente ticket de soporte de **LevaDesk**.")
            ->line('')
            ->line("**Ticket:** {$this->ticket->nomenclatura} - {$this->ticket->titulo}")
            ->line("**Prioridad:** {$prioridad}")
            ->line("**Sociedad:** {$sociedad}")
            ->line("**Aplicación:** {$aplicacion}")
            ->line("**Agente responsable:** {$agente}")
            ->line('')
            ->line("**Usuario solicitante:** {$usuario} ({$correo})")
            ->line('')
            ->line("**Descripción del incidente:**")
            ->line($this->ticket->descripcion)
            ->line('')
            ->line("**Justificación del escalamiento:**")
            ->line($this->justificacion);

        if ($comentarios->count() > 0) {
            $mail->line('')
                ->line('**Últimos comentarios registrados:**');
            foreach ($comentarios as $c) {
                $fecha = $c->created_at->format('d/m/Y h:i a');
                $autor = $c->user->name ?? 'Anónimo';
                $mail->line("• {$fecha} - {$autor}: " . strip_tags($c->comentario));
            }
        }

        $mail->line('')
            ->line("Durante el tiempo de atención por su equipo, el ANS de este ticket estará detenido.")
            ->line("Agradecemos su pronta atención y quedamos atentos a cualquier actualización.")
            ->salutation("Saludos cordiales,\nEl equipo de soporte LevaDesk");

        // 📎 Adjuntar archivos del ticket
        foreach ($this->ticket->archivos as $archivo) {
            $path = storage_path('app/' . $archivo->ruta);
            if (file_exists($path)) {
                $mail->attach($path, [
                    'as' => basename($archivo->ruta),
                    'mime' => mime_content_type($path),
                ]);
            }
        }

        return $mail;
    }
}
