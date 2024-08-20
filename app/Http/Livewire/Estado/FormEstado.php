<?php

namespace App\Http\Livewire\Estado;

use App\Models\Estado;
use Livewire\Component;
use Illuminate\Support\Str;

class FormEstado extends Component
{
    public $nombre;
    public $descripcion;
    public $estado_old;
    protected $listeners = ['editEstado'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ];

    public function editEstado($id)
    {
        $this->estado_old = Estado::find($id);
        $this->nombre = $this->estado_old->nombre;
        $this->descripcion = $this->estado_old->descripcion;
    }

    public function submit()
    {
        $this->validate();

        if (!$this->estado_old) {
            $create = Estado::create([
                'nombre' => Str::upper($this->nombre),
                'descripcion' => $this->descripcion,
            ]);
            if ($create) {
                $this->reset();
                $this->emit('ok_estado');
                $this->emit('cargarEstado');
            }
        } else {
            $this->estado_old->nombre = Str::upper($this->nombre);
            $this->estado_old->descripcion = $this->descripcion;
            $this->estado_old->save();
            $this->emit('update_estado_ok');
            $this->emit('cargarEstado');
            $this->resetear();
        }
    }

    public function resetear()
    {
        $this->resetValidation();
        $this->estado_old = "";
        $this->nombre = "";
        $this->descripcion = "";
    }

    public function render()
    {
        return view('livewire.estado.form-estado');
    }
}
