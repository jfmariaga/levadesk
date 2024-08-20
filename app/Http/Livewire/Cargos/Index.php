<?php

namespace App\Http\Livewire\Cargos;

use App\Models\Cargo;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarCargo'];

    public function render()
    {
        return view('livewire.cargos.index');
    }

    public function cargarCargo(){
        $cargos = Cargo::get()->toArray();
        $this->emit('cargarCargosTabla', json_encode($cargos));
    }
}
