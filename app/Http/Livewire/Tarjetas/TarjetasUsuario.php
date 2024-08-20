<?php

namespace App\Http\Livewire\Tarjetas;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TarjetasUsuario extends Component
{
    public $ticketsAbiertos;
    public $ticketsEnProceso;
    public $ticketsCerrados;
    public $ticketsTotal;
    public $usuarioId;

    public function mount()
    {
        $this->usuarioId = Auth::id();
        $this->contarTickets();
    }

    public function contarTickets()
    {
        $userId = Auth::id();
        $this->ticketsAbiertos = Ticket::where('usuario_id', $userId)->where('estado_id', 1)->count(); // Estado 1 es Abierto
        $this->ticketsEnProceso = Ticket::where('usuario_id', $userId)->where('estado_id', 2)->count(); // Estado 2 es En Proceso
        $this->ticketsCerrados = Ticket::where('usuario_id', $userId)->where('estado_id', 4)->count(); // Estado 3 es Cerrado
        $this->ticketsTotal = Ticket::where('usuario_id', $userId)->count(); // Estado 3 es Cerrado
    }
    public function render()
    {
        return view('livewire.tarjetas.tarjetas-usuario');
    }
}
