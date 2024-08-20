<?php

namespace App\Http\Livewire\Impacto;

use App\Models\Impacto;
use Livewire\Component;
use Illuminate\Support\Str;

class FormImpacto extends Component
{
    public $nombre;
    public $puntuacion;
    public $impacto_old;
    protected $listeners = ['editImpacto'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'puntuacion' => 'required|max:2',
    ];

    public function editImpacto($id)
    {
        $this->impacto_old = Impacto::find($id);
        $this->nombre = $this->impacto_old->nombre;
        $this->puntuacion  = $this->impacto_old->puntuacion ;
    }

    public function submit()
    {
        $this->validate();

        if (!$this->impacto_old) {
            $create = Impacto::create([
                'nombre' => Str::upper($this->nombre),
                'puntuacion' => $this->puntuacion,
            ]);
            if ($create) {
                $this->reset();
                $this->emit('ok_impacto');
                $this->emit('cargarImpacto');
            }
        } else {
            $this->impacto_old->nombre = Str::upper($this->nombre);
            $this->impacto_old->puntuacion  = $this->puntuacion ;
            $this->impacto_old->save();
            $this->emit('update_impacto_ok');
            $this->emit('cargarImpacto');
            $this->resetear();
        }
    }

    public function resetear()
    {
        $this->resetValidation();
        $this->impacto_old = "";
        $this->nombre = "";
        $this->puntuacion  = "";
    }
    public function render()
    {
        return view('livewire.impacto.form-impacto');
    }
}
