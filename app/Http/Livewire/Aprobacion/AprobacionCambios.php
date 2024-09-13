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
            // dd($this->aprobacionesFuncionalCambios);

        // Aprobaciones TI Cambios
        $this->aprobacionesTiCambios = $user->aprobacionesTiCambios()->where('estado', '!=','pendiente')
            ->with(['ticket' => function ($query) {
                $query->select('id', 'nomenclatura', 'usuario_id', 'asignado_a', 'estado_id')
                    ->with(['usuario:id,name', 'asignado:id,name', 'estado:id,nombre']);
            }])
            ->get()
            ->sortBy(function ($aprobacion) {
                return $aprobacion->estado === 'aprobado_funcional' ? 0 : 1;
            })
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
            // dd($this->aprobacionesTiCambios);


        $this->emit('cargarAprobacionesFuncionalTablaCambios', json_encode($this->aprobacionesFuncionalCambios));
        $this->emit('cargarAprobacionesTiTablaCambios', json_encode($this->aprobacionesTiCambios));
    }

    public function render()
    {
        return view('livewire.aprobacion.aprobacion-cambios');
    }
}
