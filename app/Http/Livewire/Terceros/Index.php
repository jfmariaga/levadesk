<?php

namespace App\Http\Livewire\Terceros;

use Livewire\Component;
use App\Models\Tercero;

class Index extends Component
{
    public $terceros;
    public $nombre;
    public $descripcion;
    public $activo = true;
    public $tercero_id;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:500',
        'activo' => 'boolean',
    ];

    public function mount()
    {
        $this->loadTerceros();
    }

    public function render()
    {
        return view('livewire.terceros.index');
    }

    public function loadTerceros()
    {
        $this->terceros = Tercero::all();
    }

    public function save()
    {
        $this->validate();

        Tercero::updateOrCreate(
            ['id' => $this->tercero_id],
            [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'activo' => $this->activo,
            ]
        );

        $this->resetForm();
        $this->loadTerceros();
        $this->dispatchBrowserEvent('showToast', ['type' => 'success', 'message' => 'Tercero guardado con éxito']);
    }

    public function edit($id)
    {
        $tercero = Tercero::findOrFail($id);
        $this->tercero_id = $tercero->id;
        $this->nombre = $tercero->nombre;
        $this->descripcion = $tercero->descripcion;
        $this->activo = $tercero->activo;
    }

    public function delete($id)
    {
        Tercero::findOrFail($id)->delete();
        $this->loadTerceros();
        $this->dispatchBrowserEvent('showToast', ['type' => 'success', 'message' => 'Tercero eliminado']);
    }

    private function resetForm()
    {
        $this->tercero_id = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->activo = true;
    }
}

