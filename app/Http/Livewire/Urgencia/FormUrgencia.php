<?php

namespace App\Http\Livewire\Urgencia;

use App\Models\Urgencia;
use Livewire\Component;
use Illuminate\Support\Str;


class FormUrgencia extends Component
{
    public $nombre;
    public $puntuacion;
    public $urgencia_old;
    protected $listeners = ['editUrgencia'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'puntuacion' => 'required|max:2',
    ];

    public function editUrgencia($id)
    {
        $this->urgencia_old = Urgencia::find($id);
        $this->nombre = $this->urgencia_old->nombre;
        $this->puntuacion  = $this->urgencia_old->puntuacion ;
    }

    public function submit()
    {
        $this->validate();

        if (!$this->urgencia_old) {
            $create = Urgencia::create([
                'nombre' => Str::upper($this->nombre),
                'puntuacion' => $this->puntuacion,
            ]);
            if ($create) {
                $this->reset();
                $this->emit('ok_urgencia');
                $this->emit('cargarUrgencia');
            }
        } else {
            $this->urgencia_old->nombre = Str::upper($this->nombre);
            $this->urgencia_old->puntuacion  = $this->puntuacion ;
            $this->urgencia_old->save();
            $this->emit('update_urgencia_ok');
            $this->emit('cargarUrgencia');
            $this->resetear();
        }
    }

    public function resetear()
    {
        $this->resetValidation();
        $this->urgencia_old = "";
        $this->nombre = "";
        $this->puntuacion  = "";
    }

    public function render()
    {
        return view('livewire.urgencia.form-urgencia');
    }
}
