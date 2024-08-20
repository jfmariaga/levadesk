<?php

namespace App\Http\Livewire\Sociedad;

use App\Models\Sociedad;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarSociedad'];

    public function render()
    {
        return view('livewire.sociedad.index');
    }

    public function cargarSociedad(){
        $sociedades = Sociedad::get()->toArray();
        $this->emit('cargarSociedadesTabla', json_encode($sociedades));
    }
}
