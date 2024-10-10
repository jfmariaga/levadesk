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

        // Obtener las asignaciones del supervisor
        $asignacionesSupervisor = DB::table('sociedad_subcategoria_grupo')
            ->where('supervisor_id', $user->id)
            ->select('sociedad_id', 'categoria_id', 'subcategoria_id')
            ->get();
            // dd($asignacionesSupervisor);

        // Si el usuario no tiene asignaciones como supervisor, retorna una colección vacía
        if ($asignacionesSupervisor->isEmpty()) {
            $tickets = collect(); // Colección vacía
        } else {
            // Extraer los IDs de las sociedades, categorías y subcategorías
            $sociedadIds = $asignacionesSupervisor->pluck('sociedad_id');
            $categoriaIds = $asignacionesSupervisor->pluck('categoria_id');
            $subcategoriaIds = $asignacionesSupervisor->pluck('subcategoria_id');

            // Buscar los tickets que coincidan con las asignaciones
            $tickets = Ticket::with('urgencia', 'estado', 'colaboradores', 'asignado')
                ->whereIn('sociedad_id', $sociedadIds)
                ->whereIn('categoria_id', $categoriaIds)
                ->whereIn('subcategoria_id', $subcategoriaIds)
                ->get();
        }

        // Emitir el evento para cargar la tabla de supervisor con los tickets filtrados
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
