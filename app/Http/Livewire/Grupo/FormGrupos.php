<?php

namespace App\Http\Livewire\Grupo;

use App\Models\Grupo;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;

class FormGrupos extends Component
{
    public $nombre;
    public $descripcion;
    public $usuarios = [];
    public $selectedUsuarios = [];
    public $grupo_old;
    protected $listeners = ['editGrupo'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'selectedUsuarios' => 'required|array',
    ];

    public function editGrupo($id)
    {
        $this->grupo_old = Grupo::find($id);
        $this->nombre = $this->grupo_old->nombre;
        $this->descripcion = $this->grupo_old->descripcion;
        $this->selectedUsuarios = $this->grupo_old->usuarios->pluck('id')->toArray();
        $this->emit('selectUsuarios', $this->selectedUsuarios);
    }

    public function submit()
    {
        $this->validate();

        if (!$this->grupo_old) {
            $grupo = Grupo::create([
                'nombre' => Str::upper($this->nombre),
                'descripcion' => $this->descripcion,
            ]);
            $grupo->usuarios()->sync($this->selectedUsuarios);
            $this->resetear();
            $this->emit('ok_grupo');
            $this->emit('cargarGrupo');
        } else {
            $this->grupo_old->nombre = Str::upper($this->nombre);
            $this->grupo_old->descripcion = $this->descripcion;
            $this->grupo_old->save();
            $this->grupo_old->usuarios()->sync($this->selectedUsuarios);
            $this->emit('update_grupo_ok');
            $this->emit('cargarGrupo');
            $this->resetear();
        }
    }

    public function render()
    {
        $this->usuarios = User::all();
        return view('livewire.grupo.form-grupos');
    }

    public function resetear()
    {
        $this->resetValidation();
        $this->grupo_old = "";
        $this->nombre = "";
        $this->descripcion = "";
        $this->selectedUsuarios = [];
        $this->emit('resetSelect2');
    }
}
