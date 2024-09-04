<?php

namespace App\Http\Livewire\Aplicaciones;

use App\Models\Aplicaciones;
use App\Models\Grupo;
use App\Models\Sociedad;
use Livewire\Component;
use Illuminate\Support\Str;


class FormAplicaciones extends Component
{
    public $nombre,$sociedad,$responsable,$estado;
    public $aplicacion_old;

    protected $listeners = ['editAplicacion'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'sociedad' => 'required',
        'responsable' => 'required',
    ];

    public function editAplicacion($id)
    {
        $this->aplicacion_old  = Aplicaciones::find($id);
        $this->nombre       = $this->aplicacion_old->nombre;
        $this->sociedad    = $this->aplicacion_old->sociedad_id;
        $this->estado    = $this->aplicacion_old->estado;
        $this->responsable    = $this->aplicacion_old->grupo_id;
        $this->emit('selectResponsable', $this->responsable);
        $this->emit('selectSociedad', $this->sociedad);
    }

    public function submit()
    {
        if (!$this->aplicacion_old) {
            $this->validate();
            $nombrestr = Str::upper($this->nombre);
            $create = Aplicaciones::create([
                'nombre' => $nombrestr,
                'sociedad_id' => $this->sociedad,
                'estado' => 0,
                'grupo_id' => $this->responsable,
            ]);
            if ($create) {
                $this->emit('ok_aplicacion');
                $this->emit('getAplicaciones');
                $this->resetear();
            }

        } else {
            $this->validate();
            $nombrestr = Str::upper($this->nombre);
            $this->aplicacion_old->nombre    = $nombrestr;
            $this->aplicacion_old->grupo_id = $this->responsable;
            $this->aplicacion_old->estado = $this->estado;
            $this->aplicacion_old->sociedad_id = $this->sociedad;
            $this->aplicacion_old->save();
            $this->emit('update_aplicacion_ok');
            $this->emit('getAplicaciones');
            $this->resetear();
        }

    }

    public function resetear()
    {
        $this->reset();
        $this->resetValidation();
        // $this->aplicacion_old = "";
        // $this->nombre = "";
        // $this->sociedad = "";
        // $this->responsable = "";
        // $this->solicitud = "";
        // $this->solicitudes=[];
        $this->emit('selectSociedad');
        $this->emit('selectResponsable');

    }

    public function render()
    {
        $sociedades = Sociedad::all();
        $responsables = Grupo::all();
        return view('livewire.aplicaciones.form-aplicaciones', compact('sociedades', 'responsables'));
    }
}
