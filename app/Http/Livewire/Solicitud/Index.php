<?php

namespace App\Http\Livewire\Solicitud;

use App\Models\TipoSolicitud;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarSolicitud'];

    public function render()
    {
        return view('livewire.solicitud.index');
    }

    public function cargarSolicitud(){
        $solicitudes = TipoSolicitud::get()->toArray();
        $this->emit('cargarSolicitudesTabla', json_encode($solicitudes));
    }
}
