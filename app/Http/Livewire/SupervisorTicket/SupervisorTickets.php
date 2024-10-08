<?php

namespace App\Http\Livewire\SupervisorTicket;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervisorTickets extends Component
{
    public $fecha_desde, $fecha_hasta;

    protected $listeners = ['cargarDatosSupervisor'];
    protected $queryString = ['fecha_desde', 'fecha_hasta'];

    public function mount()
    {
        $this->iniciarFechas();
        $this->cargarDatosSupervisor();
    }

    public function cargarDatosSupervisor()
    {
        $user = Auth::user();

        $subcategoriasSupervisor = DB::table('sociedad_subcategoria_grupo')
            ->where('supervisor_id', $user->id)
            ->pluck('subcategoria_id');

        // Si el usuario no supervisa ninguna subcategoría, no verá tickets
        if ($subcategoriasSupervisor->isEmpty()) {
            $tickets = collect(); // Colección vacía
        } else {
            // Consultar los tickets que tienen esas subcategorías
            $tickets = Ticket::with('urgencia', 'estado', 'colaboradores', 'asignado')
                ->whereIn('subcategoria_id', $subcategoriasSupervisor);

            $tickets = $tickets->get();
        }

        $this->emit('cargarSupervisorTabla', json_encode($tickets));
    }

    public function iniciarFechas()
    {
        $this->fecha_desde = date('Y-m-01');
        $this->fecha_hasta = date('Y-m-d');
    }

    public function render()
    {
        if (!$this->fecha_desde && !$this->fecha_hasta) {
            $this->iniciarFechas();
        }
        return view('livewire.supervisor-ticket.supervisor-tickets');
    }
}
