<?php

namespace App\Http\Livewire\Aprobacion;

use App\Models\Estado;
use App\Models\Sociedad;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AprobacionCambios extends Component
{
    public $aprobacionesFuncionalCambios;
    public $aprobacionesTiCambios;
    public $fecha_desde, $fecha_hasta;
    public $item;
    public $estados, $SelectedEstado = [];
    public $usuarios, $selectedUsuario;
    public $agentes, $selectedAgente;
    public $sociedades, $selectedSociedad;

    protected $listeners = ['loadAprobacionesCambios'];

    public function mount()
    {
        $this->iniciarFechas();
        $this->loadAprobacionesCambios();
        $this->estados = Estado::all();
        $this->usuarios = User::all();
        $this->agentes = User::role(['Agente', 'Admin'])->get();
        $this->sociedades = Sociedad::all();
    }

    public function loadAprobacionesCambios()
    {
        $user = Auth::user();

        $funcionalQuery = $user->aprobacionesFuncionalesCambios()
            ->with(['ticket' => function ($query) {
                $query->select('id', 'nomenclatura', 'usuario_id', 'asignado_a', 'estado_id','sociedad_id','tipo_solicitud_id','titulo','categoria_id','subcategoria_id','prioridad')
                    ->with(['usuario:id,name', 'asignado:id,name', 'estado:id,nombre','sociedad:id,nombre','tipoSolicitud:id,nombre','categoria:id,nombre','subcategoria:id,nombre']);
            }]);

        $tiQuery = $user->aprobacionesTiCambios()
            ->with(['ticket' => function ($query) {
                $query->select('id', 'nomenclatura', 'usuario_id', 'asignado_a', 'estado_id','sociedad_id','tipo_solicitud_id','titulo','categoria_id','subcategoria_id','prioridad')
                    ->with(['usuario:id,name', 'asignado:id,name', 'estado:id,nombre','sociedad:id,nombre','tipoSolicitud:id,nombre','categoria:id,nombre','subcategoria:id,nombre']);
            }]);

        // Aplicar filtros
        if ($this->fecha_desde && $this->fecha_hasta) {
            $fecha_desde = date('Y-m-d', strtotime($this->fecha_desde));
            $fecha_hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
            $funcionalQuery->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
            $tiQuery->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
        }

        if (!empty($this->SelectedEstado)) {
            $funcionalQuery->whereHas('ticket', function ($query) {
                $query->whereIn('estado_id', $this->SelectedEstado);
            });

            $tiQuery->whereHas('ticket', function ($query) {
                $query->whereIn('estado_id', $this->SelectedEstado);
            });
        }

        if ($this->selectedUsuario) {
            $funcionalQuery->whereHas('ticket', function ($query) {
                $query->where('usuario_id', $this->selectedUsuario);
            });

            $tiQuery->whereHas('ticket', function ($query) {
                $query->where('usuario_id', $this->selectedUsuario);
            });
        }

        if ($this->selectedAgente) {
            $funcionalQuery->whereHas('ticket', function ($query) {
                $query->where('asignado_a', $this->selectedAgente);
            });

            $tiQuery->whereHas('ticket', function ($query) {
                $query->where('asignado_a', $this->selectedAgente);
            });
        }

        if ($this->selectedSociedad) {
            $funcionalQuery->whereHas('ticket', function ($query) {
                $query->where('sociedad_id', $this->selectedSociedad);
            });

            $tiQuery->whereHas('ticket', function ($query) {
                $query->where('sociedad_id', $this->selectedSociedad);
            });
        }

        $this->aprobacionesFuncionalCambios = $funcionalQuery->get()->map(function ($aprobacion) {
            return $this->mapAprobacion($aprobacion);
        })->toArray();

        $this->aprobacionesTiCambios = $tiQuery->get()->map(function ($aprobacion) {
            return $this->mapAprobacion($aprobacion);
        })->toArray();

        $this->emit('cargarAprobacionesFuncionalTablaCambios', json_encode($this->aprobacionesFuncionalCambios));
        $this->emit('cargarAprobacionesTiTablaCambios', json_encode($this->aprobacionesTiCambios));
    }

    public function mapAprobacion($aprobacion)
    {
        return [
            'id' => $aprobacion->ticket->id,
            'nomenclatura' => $aprobacion->ticket->nomenclatura,
            'usuario' => $aprobacion->ticket->usuario->name ?? 'N/A',
            'agente_ti' => $aprobacion->ticket->asignado->name ?? 'N/A',
            'estado' => $aprobacion->estado,
            'estado_ticket' => $aprobacion->ticket->estado->nombre,
            'fecha' => $aprobacion->created_at->format('Y-m-d'),
            'sociedad' => $aprobacion->ticket->sociedad->nombre,
            'tipo_solicitud' => $aprobacion->ticket->tipoSolicitud->nombre,
            'titulo' => $aprobacion->ticket->titulo,
            'categoria' => $aprobacion->ticket->categoria->nombre,
            'subcategoria' => $aprobacion->ticket->subcategoria->nombre,
            'prioridad' => $aprobacion->ticket->prioridad,
        ];
    }

    public function filtrarPorEstado($estadoId)
    {
        $this->SelectedEstado = [$estadoId];
        $this->loadAprobacionesCambios();
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
        return view('livewire.aprobacion.aprobacion-cambios');
    }
}
