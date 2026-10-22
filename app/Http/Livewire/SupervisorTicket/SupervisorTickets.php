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
    // protected: no viajan en cada peticion de Livewire (el WAF del ALB bloquea cuerpos > 8 KB con 403)
    protected $tickets;
    protected $asignacionesSupervisor;

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
            ->orWhere('supervisor_id_2', $user->id)
            ->pluck('sociedad_id'))
            ->pluck('asignado_a')
            ->unique();

        $this->asignados = User::whereIn('id', $asignadosIds)->get(); // Obtener los usuarios asignados
    }

    /** Asignaciones del supervisor; se consulta una vez por peticion porque la propiedad ya no se sincroniza. */
    protected function asignaciones()
    {
        if ($this->asignacionesSupervisor === null) {
            $user = Auth::user();

            $this->asignacionesSupervisor = DB::table('sociedad_subcategoria_grupo')
                ->where('supervisor_id', $user->id)
                ->orWhere('supervisor_id_2', $user->id)
                ->select('sociedad_id', 'categoria_id', 'subcategoria_id')
                ->get();
        }

        return $this->asignacionesSupervisor;
    }

    public function cargarDatosSupervisor()
    {
        $user = Auth::user();

        // Obtener las asignaciones del supervisor
        $this->asignacionesSupervisor = null;
        $asignaciones = $this->asignaciones();

        if ($asignaciones->isEmpty()) {
            $this->tickets = collect();
            $this->emit('cargarSupervisorTabla', json_encode($this->tickets));
            return;
        }

        $sociedadIds = $asignaciones->pluck('sociedad_id')->unique();
        $categoriaIds = $asignaciones->pluck('categoria_id')->unique();
        $subcategoriaIds = $asignaciones->pluck('subcategoria_id')->unique();

        // Construcción de la consulta base
        $query = Ticket::with([
            'urgencia',
            'estado',
            'colaboradores',
            'asignado',
            'usuario',
            'categoria',
            'subcategoria'
        ])
            ->where(function ($q) use ($sociedadIds, $categoriaIds, $subcategoriaIds, $user) {
                // Grupo principal de condiciones OR
                $q->where(function ($q2) use ($sociedadIds, $categoriaIds, $subcategoriaIds) {
                    // Condición 1: Tickets que cumplan con sociedad, categoría y subcategoría
                    $q2->whereIn('sociedad_id', $sociedadIds)
                        ->whereIn('categoria_id', $categoriaIds)
                        ->whereIn('subcategoria_id', $subcategoriaIds);
                })
                    ->orWhere(function ($q2) use ($sociedadIds, $user) {
                        // Condición 2: Tickets con aplicacion_id que cumplan sociedad
                        $q2->whereNotNull('aplicacion_id')
                            ->whereIn('sociedad_id', $sociedadIds);
                    });
            });

        // Aplicación de filtros adicionales (AND con el grupo principal)
        if ($this->SelectedEstado) {
            $query->where('estado_id', $this->SelectedEstado);
        }

        if ($this->SelectedAsignado) {
            $query->where('asignado_a', $this->SelectedAsignado);
        }

        // Filtro de fechas (se aplica a todas las condiciones)
        if ($this->fecha_desde && $this->fecha_hasta) {
            $fecha_desde = date('Y-m-d', strtotime($this->fecha_desde));
            $fecha_hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
            $query->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
        }

        $this->tickets = $query->get();
        $this->emit('cargarSupervisorTabla', json_encode($this->tickets));
    }


    public function iniciarFechas()
    {
        $this->fecha_desde = date('Y-m-1');
        $this->fecha_hasta = date('Y-m-d');
    }

    public function render()
    {
        if (!$this->fecha_desde && !$this->fecha_hasta) {
            $this->iniciarFechas();
        }

        return view('livewire.supervisor-ticket.supervisor-tickets', [
            'asignacionesSupervisor' => $this->asignaciones(),
        ]);
    }
}
