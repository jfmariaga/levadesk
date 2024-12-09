<?php

namespace App\Http\Livewire;

use App\Models\Tarea;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class Notifications extends Component
{
    public $usuarioId;
    public $aprobaciones;
    public $cambios;
    public $tareas;

    public function getListeners()
    {
        return [
            'actualizarNotificaciones' => 'contarTickets',
        ];
    }

    public function mount()
    {
        $this->usuarioId = Auth::user();
        $this->contarTickets();
    }

    public function contarTickets()
    {
        // Obtener las aprobaciones funcionales y de TI con ticket_id y estado pendiente
        $aprobacionesFuncionales = $this->usuarioId->aprobacionesFuncionales()
            ->where('estado', 'pendiente')
            ->get();

        $aprobacionesTi = $this->usuarioId->aprobacionesTi()
            ->where('estado', 'aprobado_funcional')
            ->get();

        // Obtener las aprobaciones funcionales y de TI para cambios
        $aprobacionesFuncionalesCambios = $this->usuarioId->aprobacionesFuncionalesCambios()
            ->where('estado', 'pendiente')
            ->get();

        $aprobacionesTiCambios = $this->usuarioId->aprobacionesTiCambios()
            ->where(function ($query) {
                $query->where('estado', 'aprobado_funcional')
                    ->orWhere(function ($query) {
                        $query->where('check_aprobado', true)
                            ->where('check_aprobado_ti', false);
                    });
            })
            ->get();

        $this->tareas = Tarea::where('estado', '<>', 'completado')->where('user_id', Auth::user()->id)->get();

        // Unir las aprobaciones y los cambios usando concat() para mantener las relaciones intactas
        $this->aprobaciones = $aprobacionesFuncionales->concat($aprobacionesTi);
        $this->cambios = $aprobacionesFuncionalesCambios->concat($aprobacionesTiCambios);
    }

    public function render()
    {
        return view('livewire.notifications', [
            'aprobaciones' => $this->aprobaciones,
            'cambios' => $this->cambios,
            'tareas' => $this->tareas,
        ]);
    }
}
