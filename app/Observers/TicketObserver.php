<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketEstado;

class TicketObserver
{
    public function updating(Ticket $ticket)
    {
        if ($ticket->isDirty('estado_id')) {
            TicketEstado::create([
                'ticket_id' => $ticket->id,
                'estado_id' => $ticket->estado_id,
            ]);
        }
    }
}
