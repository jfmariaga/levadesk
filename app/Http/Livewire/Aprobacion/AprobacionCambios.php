<?php

namespace App\Http\Livewire\Aprobacion;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AprobacionCambios extends Component
{
    public $aprobacionesFuncionalCambios;
    public $aprobacionesTiCambios;
    protected $listeners = ['loadAprobacionesCambios'];

    public function loadAprobacionesCambios()
    {
        $user = Auth::user();

        // Aprobaciones Funcionales Cambios
        $this->aprobacionesFuncionalCambios = $user->aprobacionesFuncionalesCambios()
            ->with(['ticket' => function ($query) {
                $query->select('id', 'nomenclatura', 'usuario_id', 'asignado_a', 'estado_id')
                    ->with(['usuario:id,name', 'asignado:id,name', 'estado:id,nombre']);
            }])
            ->get()
            ->sortBy(function ($aprobacion) {
                return $aprobacion->estado === 'pendiente' || $aprobacion->estado === 'rechazado_ti' ? 0 : 1;
            })
            ->map(function ($aprobacion) {
                return [
                    'id' => $aprobacion->ticket->id,
                    'nomenclatura' => $aprobacion->ticket->nomenclatura,
                    'usuario' => $aprobacion->ticket->usuario->name ?? 'N/A',
                    'agente_ti' => $aprobacion->ticket->asignado->name ?? 'N/A',
                    'estado' => $aprobacion->ticket->estado->nombre,
                ];
            })
            ->toArray();

        // Aprobaciones TI Cambios
        $this->aprobacionesTiCambios = $user->aprobacionesTiCambios()
            ->with(['ticket' => function ($query) {
                $query->select('id', 'nomenclatura', 'usuario_id', 'asignado_a', 'estado_id')
                    ->with(['usuario:id,name', 'asignado:id,name', 'estado:id,nombre']);
            }])
            ->get()
            ->sortBy(function ($aprobacion) {
                return $aprobacion->estado === 'aprobado_funcional' || $aprobacion->check_aprobado ? 0 : 1;
            })
            ->map(function ($aprobacion) {
                return [
                    'id' => $aprobacion->ticket->id,
                    'nomenclatura' => $aprobacion->ticket->nomenclatura,
                    'usuario' => $aprobacion->ticket->usuario->name ?? 'N/A',
                    'agente_ti' => $aprobacion->ticket->asignado->name ?? 'N/A',
                    'estado' => $aprobacion->ticket->estado->nombre,
                ];
            })
            ->toArray();

        $this->emit('cargarAprobacionesFuncionalTablaCambios', json_encode($this->aprobacionesFuncionalCambios));
        $this->emit('cargarAprobacionesTiTablaCambios', json_encode($this->aprobacionesTiCambios));
    }

    public function render()
    {
        return view('livewire.aprobacion.aprobacion-cambios');
    }
}
