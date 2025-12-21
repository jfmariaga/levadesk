<?php

namespace App\Http\Livewire\Usuarios;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarUsuarios'];

    public function render()
    {

        return view('livewire.usuarios.index');
    }

    public function cargarUsuarios(){
        $usuarios = User::with(['roles', 'sociedad'])->get()->toArray();
        $this->emit('cargarUsuariosTabla', json_encode($usuarios));
    }
}
