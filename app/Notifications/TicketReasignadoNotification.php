<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReasignadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    private function getTicketUrl($notifiable)
    {
        // Cargar usuario con roles
        $user = User::with('roles')->find($notifiable->id);

        if ($user && $user->hasAnyRole(['agente', 'admin'])) {
            return url('/gestionar?ticket_id=' . $this->ticket->id);
        }

        return url('/verTicket?id=' . $this->ticket->id);
    }

    public function toMail($notifiable)
    {
        $url = $this->getTicketUrl($notifiable);

        return (new MailMessage)
            ->subject('Se te ha reasignado un ticket')
            ->greeting("Hola, {$notifiable->name}")
            ->line("Se te ha reasignado el ticket #{$this->ticket->nomenclatura}: {$this->ticket->titulo}")
            ->action('Ver Ticket', $url)
            ->line('Por favor revisa y continúa con la gestión.');
    }

    public function toArray($notifiable)
    {
        $url = $this->getTicketUrl($notifiable);

        return [
            'ticket_id' => $this->ticket->id,
            'titulo' => $this->ticket->titulo,
            'mensaje' => "Se te ha reasignado el ticket #{$this->ticket->nomenclatura}",
            'url' => $url,
        ];
    }
}
