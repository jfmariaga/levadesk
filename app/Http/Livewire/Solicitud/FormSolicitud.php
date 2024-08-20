<?php

namespace App\Http\Livewire\Solicitud;

use App\Models\TipoSolicitud;
use Livewire\Component;
use Illuminate\Support\Str;


class FormSolicitud extends Component
{
    public $nombre;
    public $estado;
    public $codigo;
    public $solicitud_old;

    protected $listeners = ['editSolicitud'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|max:1',
    ];

    public function editSolicitud($id)
    {
        $this->solicitud_old  = TipoSolicitud::find($id);
        $this->nombre       = $this->solicitud_old->nombre;
        $this->estado    = $this->solicitud_old->estado;
        $this->codigo    = $this->solicitud_old->codigo;
    }

    public function submit()
    {
        if (!$this->solicitud_old) {
            $this->validate();
            $nombrestr = Str::upper($this->nombre);
            $codigostr = Str::upper($this->codigo);
            $create = TipoSolicitud::create([
                'nombre' => $nombrestr,
                'codigo' => $codigostr,
                'estado' => 0,
            ]);
            if ($create) {
                $this->reset();
                $this->emit('ok_solicitud');
                $this->emit('cargarSolicitud');
            }
        } else {
            $this->validate();
            $nombrestr = Str::upper($this->nombre);
            $codigostr = Str::upper($this->codigo);
            $this->solicitud_old->nombre    = $nombrestr;
            $this->solicitud_old->estado = $this->estado;
            $this->solicitud_old->codigo = $codigostr;
            $this->solicitud_old->save();
            $this->emit('update_solicitud_ok');
            $this->emit('cargarSolicitud');
            $this->resetear();
        }
    }

    public function resetear()
    {
        $this->resetValidation();
        $this->solicitud_old = "";
        $this->nombre = "";
        $this->codigo = "";
    }


    public function render()
    {
        return view('livewire.solicitud.form-solicitud');
    }
}
