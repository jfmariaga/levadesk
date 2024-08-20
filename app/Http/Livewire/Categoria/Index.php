<?php

namespace App\Http\Livewire\Categoria;

use App\Models\Categoria;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarCategoria'];

    public function render()
    {
        return view('livewire.categoria.index');
    }

    public function cargarCategoria(){
        $categorias = Categoria::with('solicitud')->get()->toArray();
        $this->emit('cargarCategoriasTabla', json_encode($categorias));
    }
}
