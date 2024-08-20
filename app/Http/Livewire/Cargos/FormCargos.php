<?php

namespace App\Http\Livewire\Cargos;

use App\Models\Cargo;
use Livewire\Component;
use Illuminate\Support\Str;

class FormCargos extends Component
{
    public $titulo;
    public $descripcion;
    public $cargo_old;
    protected $listeners = ['editCargo'];

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ];

    public function editCargo($id)
    {
        $this->cargo_old = Cargo::find($id);
        $this->titulo = $this->cargo_old->titulo;
        $this->descripcion = $this->cargo_old->descripcion;
    }

    public function submit()
    {
        $this->validate();

        if (!$this->cargo_old) {
            $create = Cargo::create([
                'titulo' => Str::upper($this->titulo),
                'descripcion' => $this->descripcion,
            ]);
            if ($create) {
                $this->reset();
                $this->emit('ok_cargo');
                $this->emit('cargarCargo');
            }
        } else {
            $this->cargo_old->titulo = Str::upper($this->titulo);
            $this->cargo_old->descripcion = $this->descripcion;
            $this->cargo_old->save();
            $this->emit('update_cargo_ok');
            $this->emit('cargarCargo');
            $this->resetear();
        }
    }

    public function render()
    {
        return view('livewire.cargos.form-cargos');
    }

    public function resetear()
    {
        $this->resetValidation();
        $this->cargo_old = "";
        $this->titulo = "";
        $this->descripcion = "";
    }
}
