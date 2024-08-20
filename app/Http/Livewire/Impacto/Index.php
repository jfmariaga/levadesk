<?php

namespace App\Http\Livewire\Impacto;

use App\Models\Impacto;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarImpacto'];

    public function render()
    {
        return view('livewire.impacto.index');
    }

    public function cargarImpacto(){
        $impactos = Impacto::get()->toArray();
        $this->emit('cargarImpactosTabla', json_encode($impactos));
    }
}
