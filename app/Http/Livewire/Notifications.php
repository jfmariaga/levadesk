<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class Notifications extends Component
{
    public $usuarioId;
    public $aprobaciones;  // Aprobaciones con ticket_id
    public $cambios;  // Cambios con ticket_id

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
            ->where('estado', 'aprobado_funcional')
            ->get();

        // Unir las aprobaciones y los cambios usando concat() para mantener las relaciones intactas
        $this->aprobaciones = $aprobacionesFuncionales->concat($aprobacionesTi);
        $this->cambios = $aprobacionesFuncionalesCambios->concat($aprobacionesTiCambios);
    }

    public function render()
    {
        return view('livewire.notifications', [
            'aprobaciones' => $this->aprobaciones,
            'cambios' => $this->cambios,
        ]);
    }
}
