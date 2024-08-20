<?php

namespace App\Http\Livewire\Ticket;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{   
    protected $listeners = ['cargarTickets'];

    public function render()
    {
        return view('livewire.ticket.index');
    }

    public function cargarTickets()
    {
        $userId = Auth::id();
        $tickets = Ticket::where('usuario_id', $userId)->with(['usuario', 'asignado', 'sociedad', 'tipoSolicitud', 'categoria', 'subcategoria', 'estado'])->get()->toArray();
        $this->emit('cargarTicketsTabla', json_encode($tickets));
    }

}
