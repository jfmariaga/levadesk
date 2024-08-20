<?php

namespace App\Http\Livewire\Grupo;

use App\Models\Grupo;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarGrupo'];

    public function cargarGrupo()
    {
        $grupos = Grupo::with('usuarios')->get()->toJson();
        $this->emit('cargarGruposTabla', $grupos);
    }
    public function render()
    {
        return view('livewire.grupo.index');
    }
}
