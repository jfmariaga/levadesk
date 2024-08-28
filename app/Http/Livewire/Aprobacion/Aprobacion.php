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

        // Asumiendo que tienes relaciones definidas en el modelo User
        // para las aprobaciones funcionales y de TI, y que el modelo Aprobacion
        // tiene una relación `ticket` que apunta al modelo Ticket.

        $this->aprobacionesFuncional = $user->aprobacionesFuncionales()
            ->where(function ($query) {
                $query->where('estado', 'pendiente')
                    ->orWhere('estado', 'rechazado_ti');
            })
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
                    'estado' => $aprobacion->ticket->estado->nombre,
                ];
            })
            ->toArray();

        $this->aprobacionesTi = $user->aprobacionesTi()
            ->where('estado', 'aprobado_funcional')
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
                    'estado' => $aprobacion->ticket->estado->nombre,
                ];
            })
            ->toArray();

        $this->emit('cargarAprobacionesFuncionalTabla', json_encode($this->aprobacionesFuncional));
        $this->emit('cargarAprobacionesTiTabla', json_encode($this->aprobacionesTi));
    }

    public function render()
    {
        return view('livewire.aprobacion.aprobacion');
    }
}
