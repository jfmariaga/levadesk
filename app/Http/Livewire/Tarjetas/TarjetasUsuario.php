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
    public $ticketsRechazados;
    public $ticketsTotal;
    public $usuarioId;
    public $aprobacion;

    public function mount()
    {
        $this->usuarioId = Auth::user();
        // dd($this->usuarioId->roles->first()->name);
        $this->contarTickets();
    }

    public function contarTickets()
    {
        $this->ticketsAbiertos = Ticket::where('usuario_id', $this->usuarioId->id)->where('estado_id', 1)->count(); // Estado 1 es Abierto
        $this->ticketsEnProceso = Ticket::where('usuario_id', $this->usuarioId->id)->whereIn('estado_id', ['3','8','7','6','9','10','11','12','13','14'])->count(); // Estado 2 es En Proceso
        $this->ticketsCerrados = Ticket::where('usuario_id', $this->usuarioId->id)->where('estado_id', 4)->count(); // Estado 3 es Cerrado
        $this->ticketsRechazados = Ticket::where('usuario_id', $this->usuarioId->id)->where('estado_id', 5)->count(); // Estado 3 es Cerrado
        $this->ticketsTotal = Ticket::where('usuario_id', $this->usuarioId->id)->count(); // Estado 3 es Cerrado
        $aprobacionFuncional = $this->usuarioId->aprobacionesFuncionales()->where('estado','pendiente')->get()->count();
        $aprobacionFuncionalCambio = $this->usuarioId->aprobacionesFuncionalesCambios()->where('estado','pendiente')->get()->count();
        $aprobacionTi = $this->usuarioId->aprobacionesTi()->where('estado','aprobado_funcional')->get()->count();
        $aprobacionTiCambio = $this->usuarioId->aprobacionesTiCambios()->where('estado','aprobado_funcional')->get()->count();
        $this->aprobacion = $aprobacionFuncional + $aprobacionTi + $aprobacionFuncionalCambio + $aprobacionTiCambio;

    }
    public function render()
    {
        return view('livewire.tarjetas.tarjetas-usuario');
    }
}
