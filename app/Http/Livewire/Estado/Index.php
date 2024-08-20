<?php

namespace App\Http\Livewire\Estado;

use App\Models\Estado;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarEstado'];

    public function render()
    {
        return view('livewire.estado.index');
    }

    public function cargarEstado(){
        $estados = Estado::get()->toArray();
        $this->emit('cargarEstadosTabla', json_encode($estados));
    }
}
