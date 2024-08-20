<?php

namespace App\Http\Livewire\Sociedad;

use App\Models\Sociedad;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public $nombre;
    public $descripcion;
    public $estado;
    public $codigo;
    public $sociedad_old;
    protected $listeners = ['editSociedad'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|max:2',
        'descripcion' => 'nullable|string',
    ];

    public function editSociedad($id)
    {
        $this->sociedad_old  = Sociedad::find($id);
        $this->nombre       = $this->sociedad_old->nombre;
        $this->descripcion    = $this->sociedad_old->descripcion;
        $this->estado    = $this->sociedad_old->estado;
        $this->codigo    = $this->sociedad_old->codigo;
    }

    public function submit()
    {
        if (!$this->sociedad_old) {
            $this->validate();
            $nombrestr = Str::upper($this->nombre);
            $codigostr = Str::upper($this->codigo);
            $create = Sociedad::create([
                'nombre' => $nombrestr,
                'descripcion' => $this->descripcion,
                'codigo' => $codigostr,
                'estado' => 0,
            ]);
            if ($create) {
                $this->reset();
                $this->emit('ok_sociedad');
                $this->emit('cargarSociedad');
            }
        } else {
            $this->validate();
            $nombrestr = Str::upper($this->nombre);
            $codigostr = Str::upper($this->codigo);
            $this->sociedad_old->nombre    = $nombrestr;
            $this->sociedad_old->descripcion = $this->descripcion;
            $this->sociedad_old->estado = $this->estado;
            $this->sociedad_old->codigo = $codigostr;
            $this->sociedad_old->save();
            $this->emit('update_sociedad_ok');
            $this->emit('cargarSociedad');
            $this->resetear();
        }
    }

    public function render()
    {
        return view('livewire.sociedad.create');
    }

    public function resetear()
    {
        $this->resetValidation();
        $this->sociedad_old = "";
        $this->nombre = "";
        $this->descripcion = "";
        $this->codigo = "";
    }
}
