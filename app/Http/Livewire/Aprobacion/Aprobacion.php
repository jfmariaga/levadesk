<?php

namespace App\Http\Livewire\Aprobacion;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Aprobacion extends Component
{
    public $aprobacionesFuncional;
    public $aprobacionesTi;
    protected $listeners = ['loadAprobaciones'];

    public function loadAprobaciones()
    {
        $user = Auth::user();

        // Aprobaciones Funcionales
        $this->aprobacionesFuncional = $user->aprobacionesFuncionales()
            ->with(['ticket' => function ($query) {
                $query->select('id', 'nomenclatura', 'usuario_id', 'asignado_a', 'estado_id')
                    ->with(['usuario:id,name', 'asignado:id,name', 'estado:id,nombre']);
            }])
            ->get()
            ->map(function ($aprobacion) {
                return [
                    'id' => $aprobacion->ticket->id,
                    'nomenclatura' => $aprobacion->ticket->nomenclatura,
                    'usuario' => $aprobacion->ticket->usuario->name ?? 'N/A',
                    'agente_ti' => $aprobacion->ticket->asignado->name ?? 'N/A',
                    'estado' => $aprobacion->estado,
                ];
            })
            ->toArray();

        // Aprobaciones TI
        $this->aprobacionesTi = $user->aprobacionesTi()->where('estado', '!=','pendiente')->where('estado', '!=','rechazado_funcional')
            ->with(['ticket' => function ($query) {
                $query->select('id', 'nomenclatura', 'usuario_id', 'asignado_a', 'estado_id')
                    ->with(['usuario:id,name', 'asignado:id,name', 'estado:id,nombre']);
            }])
            ->get()
            ->map(function ($aprobacion) {
                return [
                    'id' => $aprobacion->ticket->id,
                    'nomenclatura' => $aprobacion->ticket->nomenclatura,
                    'usuario' => $aprobacion->ticket->usuario->name ?? 'N/A',
                    'agente_ti' => $aprobacion->ticket->asignado->name ?? 'N/A',
                    'estado' => $aprobacion->estado,
                ];
            })
            ->toArray();

        // Emitir los datos a las tablas correspondientes
        $this->emit('cargarAprobacionesFuncionalTabla', json_encode($this->aprobacionesFuncional));
        $this->emit('cargarAprobacionesTiTabla', json_encode($this->aprobacionesTi));
    }

    public function render()
    {
        return view('livewire.aprobacion.aprobacion');
    }
}
