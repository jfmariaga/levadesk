<?php

namespace App\Http\Livewire\Urgencia;

use App\Models\Urgencia;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarUrgencia'];

    public function render()
    {
        return view('livewire.urgencia.index');
    }

    public function cargarUrgencia(){
        $urgencias = Urgencia::get()->toArray();
        $this->emit('cargarUrgenciasTabla', json_encode($urgencias));
    }
}
