<?php

namespace App\Http\Livewire\SupervisorTicket;

use App\Models\Estado;
use App\Models\Ticket;
use App\Models\User; // Asegúrate de tener el modelo de usuarios.
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervisorTickets extends Component
{
    public $fecha_desde, $fecha_hasta;
    public $estados, $SelectedEstado;
    public $asignados, $SelectedAsignado;

    protected $listeners = ['cargarDatosSupervisor'];
    protected $queryString = ['fecha_desde', 'fecha_hasta', 'SelectedAsignado', 'SelectedEstado'];

    public function mount()
    {
        $this->iniciarFechas();
        $this->cargarDatosSupervisor();
        $this->estados = Estado::all();
        $this->cargarAsignados(); // Cargar los asignados
    }

    public function cargarAsignados()
    {
        // Obtener los asignados que tienen tickets bajo supervisión del usuario autenticado
        $user = Auth::user();

        $asignadosIds = Ticket::whereIn('sociedad_id', DB::table('sociedad_subcategoria_grupo')
            ->where('supervisor_id', $user->id)
            ->pluck('sociedad_id'))
            ->pluck('asignado_a')
            ->unique();

        $this->asignados = User::whereIn('id', $asignadosIds)->get(); // Obtener los usuarios asignados
    }

    public function cargarDatosSupervisor()
    {
        $user = Auth::user();

        // Obtener las asignaciones del supervisor
        $asignacionesSupervisor = DB::table('sociedad_subcategoria_grupo')
            ->where('supervisor_id', $user->id)
            ->select('sociedad_id', 'categoria_id', 'subcategoria_id')
            ->get();

        if ($asignacionesSupervisor->isEmpty()) {
            $tickets = collect(); // Colección vacía
        } else {
            $sociedadIds = $asignacionesSupervisor->pluck('sociedad_id');
            $categoriaIds = $asignacionesSupervisor->pluck('categoria_id');
            $subcategoriaIds = $asignacionesSupervisor->pluck('subcategoria_id');

            $query = Ticket::with('urgencia', 'estado', 'colaboradores', 'asignado', 'usuario', 'categoria', 'subcategoria')
                ->whereIn('sociedad_id', $sociedadIds)
                ->whereIn('categoria_id', $categoriaIds)
                ->whereIn('subcategoria_id', $subcategoriaIds);

            if ($this->SelectedEstado) {
                $query->where('estado_id', $this->SelectedEstado);
            }

            if ($this->SelectedAsignado) {
                $query->where('asignado_a', $this->SelectedAsignado);
            }

            if ($this->fecha_desde && $this->fecha_hasta) {
                $fecha_desde = date('Y-m-d', strtotime($this->fecha_desde));
                $fecha_hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
                $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
            }

            $tickets = $query->get();
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
