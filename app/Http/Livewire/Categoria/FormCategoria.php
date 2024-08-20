<?php

namespace App\Http\Livewire\Categoria;

use App\Models\Categoria;
use App\Models\TipoSolicitud;
use Livewire\Component;
use Illuminate\Support\Str;

class FormCategoria extends Component
{
    public $nombre;
    public $descripcion;
    public $estado;
    public $codigo;
    public $solicitud;
    public $categoria_old;
    public $solicitudes =[];
    protected $listeners = ['editCategoria'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|max:1',
        'solicitud' => 'required',
        'descripcion' => 'nullable|string',
    ];

    public function editCategoria($id)
    {
        $this->categoria_old  = Categoria::find($id);
        $this->nombre       = $this->categoria_old->nombre;
        $this->descripcion    = $this->categoria_old->descripcion;
        $this->estado    = $this->categoria_old->estado;
        $this->codigo    = $this->categoria_old->codigo;
        $this->solicitud    = $this->categoria_old->solicitud_id;

        if ($this->solicitud) {
            $this->emit('selectSolicitud', $this->solicitud);
        }
    }

    public function submit()
    {
        if (!$this->categoria_old) {
            $this->validate();
            $nombrestr = Str::upper($this->nombre);
            $codigostr = Str::upper($this->codigo);
            $create = Categoria::create([
                'nombre' => $nombrestr,
                'descripcion' => $this->descripcion,
                'codigo' => $codigostr,
                'estado' => 0,
                'solicitud_id' => $this->solicitud,
            ]);
            if ($create) {
                $this->reset();
                $this->emit('ok_categoria');
                $this->emit('cargarCategoria');
            }
        } else {
            $this->validate();
            $nombrestr = Str::upper($this->nombre);
            $codigostr = Str::upper($this->codigo);
            $this->categoria_old->nombre    = $nombrestr;
            $this->categoria_old->descripcion = $this->descripcion;
            $this->categoria_old->estado = $this->estado;
            $this->categoria_old->codigo = $codigostr;
            $this->categoria_old->solicitud_id = $this->solicitud;
            $this->categoria_old->save();
            $this->emit('update_categoria_ok');
            $this->emit('cargarCategoria');
            $this->resetear();
        }
    }

    public function resetear()
    {
        $this->reset();
        $this->resetValidation();
        // $this->categoria_old = "";
        // $this->nombre = "";
        // $this->descripcion = "";
        // $this->codigo = "";
        // $this->solicitud = "";
        // $this->solicitudes=[];
        $this->emit('selectSolicitud');

    }

    public function render()
    {
        if( !$this->solicitud ){
            $this->solicitudes = TipoSolicitud::where('estado', '0')->get();
        }
        return view('livewire.categoria.form-categoria');
    }
}
