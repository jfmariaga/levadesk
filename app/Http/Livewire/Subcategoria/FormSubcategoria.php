<?php

namespace App\Http\Livewire\Subcategoria;

use App\Models\Subcategoria;
use App\Models\Categoria;
use App\Models\Grupo;
use Livewire\Component;
use Illuminate\Support\Str;


class FormSubcategoria extends Component
{
    public $nombre;
    public $descripcion;
    public $codigo;
    public $categoria_id;
    public $estado;
    public $subCategoria_old;
    public $categorias = [];
    public $grupos = [];
    protected $listeners = ['editSubCategoria'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|max:2',
        'categoria_id' => 'required|exists:categorias,id',
        'descripcion' => 'nullable|string',
    ];

    public function mount()
    {
        $this->categorias = Categoria::all();
        $this->grupos = Grupo::all();
    }

    public function editSubCategoria($id)
    {
        $this->subCategoria_old = Subcategoria::find($id);
        $this->nombre = $this->subCategoria_old->nombre;
        $this->descripcion = $this->subCategoria_old->descripcion;
        $this->codigo = $this->subCategoria_old->codigo;
        $this->categoria_id = $this->subCategoria_old->categoria_id;
        $this->estado = $this->subCategoria_old->estado;

        $this->emit('selectCategoria', $this->categoria_id);
    }

    public function submit()
    {
        $this->validate();

        if (!$this->subCategoria_old) {
            $create = Subcategoria::create([
                'nombre' => Str::upper($this->nombre),
                'descripcion' => $this->descripcion,
                'codigo' => Str::upper($this->codigo),
                'categoria_id' => $this->categoria_id,
                'estado' => 0,
            ]);
            if ($create) {
                $this->reset();
                $this->emit('ok_subcategoria');
                $this->emit('cargarSubCategoria');
            }
        } else {
            $this->subCategoria_old->update([
                'nombre' => Str::upper($this->nombre),
                'descripcion' => $this->descripcion,
                'codigo' => Str::upper($this->codigo),
                'categoria_id' => $this->categoria_id,
                'estado' => $this->estado,
            ]);

            $this->emit('update_subcategoria_ok');
            $this->emit('cargarSubCategoria');
            $this->resetear();
        }
    }

    public function resetear()
    {
        $this->resetValidation();
        $this->nombre = "";
        $this->descripcion = "";
        $this->codigo = "";
        $this->categoria_id = "";
        $this->estado = 0;
        $this->subCategoria_old = null;
    }

    public function render()
    {
        return view('livewire.subcategoria.form-subcategoria');
    }
}
