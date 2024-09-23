<?php

namespace App\Http\Livewire\Subcategoria;

use App\Models\Subcategoria;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['cargarSubCategoria'];

    public function render()
    {
        return view('livewire.subcategoria.index');
    }

    public function cargarSubCategoria(){
        $subcategorias = Subcategoria::with(['categoria', 'categoria.solicitud'])->get()->toArray();
        $this->emit('cargarSubCategoriasTabla', json_encode($subcategorias));
    }
}
