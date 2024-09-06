<?php

namespace App\Http\Livewire\Usuarios;

use App\Models\Sociedad;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class FormUsuarios extends Component
{
    public $name, $email, $rol, $sociedad, $estado;
    public $usuario_old;
    public $sociedades =[];
    public $roles =[];

    protected $listeners = ['editUsuarios'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|max:2',
        'rol' => 'required',
        'sociedad' => 'required',
        'estado' => 'required',
    ];

    public function mount()
    {
        $this->sociedades = Sociedad::where('estado', 0)->get();
        $this->roles = Role::all();
    }

    public function editUsuarios($id)
    {
        $this->usuario_old = User::find($id);
        $this->name = $this->usuario_old->name;
        $this->email  = $this->usuario_old->email;
        $this->rol  = $this->usuario_old->getRoleNames();
        $this->sociedad  = $this->usuario_old->sociedad_id;
        $this->estado  = $this->usuario_old->estado;
    }

    public function actualizar(){
        $this->validate();

        if ($this->usuario_old) {
             $this->usuario_old->name = $this->name;
             $this->usuario_old->email = $this->email;
             $this->usuario_old->sociedad_id = $this->sociedad;
             $this->usuario_old->estado = $this->estado;
             $this->usuario_old->roles()->sync($this->role);
             $this->usuario_old->update();
             $this->emit('usuario_actualizado');
             $this->resetear();
        }
    }

    public function resetear(){
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.usuarios.form-usuarios');
    }
}
