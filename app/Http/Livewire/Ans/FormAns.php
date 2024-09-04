<?php

namespace App\Http\Livewire\Ans;

use App\Models\ANS;
use App\Models\TipoSolicitud;
use Livewire\Component;
use Illuminate\Support\Str;

class FormAns extends Component
{
    public $nivel;
    public $h_atencion;
    public $t_asignacion; // en minutos
    public $t_resolucion; // en horas
    public $t_aceptacion; // en horas
    public $solicitud;
    public $ans_old;
    public $solicitudes = [];
    protected $listeners = ['editAns'];

    protected $rules = [
        'nivel' => 'required|string|max:255',
        'h_atencion' => 'required|string|max:255',
        't_asignacion' => 'required|integer|min:0', // en minutos
        't_resolucion' => 'required|integer|min:0', // en horas
        't_aceptacion' => 'required|integer|min:0', // en horas
        'solicitud' => 'required',
    ];

    public function editAns($id)
    {
        $this->ans_old = ANS::find($id);
        $this->nivel = $this->ans_old->nivel;
        $this->h_atencion = $this->ans_old->h_atencion;
        $this->t_asignacion = $this->ans_old->t_asignacion_segundos / 60; // convertir a minutos
        $this->t_resolucion = $this->ans_old->t_resolucion_segundos / 3600; // convertir a horas
        $this->t_aceptacion = $this->ans_old->t_aceptacion_segundos / 3600; // convertir a horas
        $this->solicitud = $this->ans_old->solicitud_id;

        if ($this->solicitud) {
            $this->emit('selectSolicitud', $this->solicitud);
        }
    }

    public function submit()
    {
        $this->validate();

        // Validar unicidad del nivel para el tipo de solicitud
        $existeNivel = ANS::where('nivel', $this->nivel)
                          ->where('solicitud_id', $this->solicitud)
                          ->when($this->ans_old, function($query) {
                              return $query->where('id', '!=', $this->ans_old->id);
                          })
                          ->exists();

        if ($existeNivel) {
            $this->emit('error_ans', 'El nivel ya existe para este tipo de solicitud.');
            return;
        }

        $tiempoAsignacionSegundos = $this->t_asignacion * 60; // convertir a segundos
        $tiempoResolucionSegundos = $this->t_resolucion * 3600; // convertir a segundos
        $tiempoAceptacionSegundos = $this->t_aceptacion * 3600; // convertir a segundos

        if (!$this->ans_old) {
            $create = ANS::create([
                'nivel' => Str::upper($this->nivel),
                'h_atencion' => $this->h_atencion,
                't_asignacion_segundos' => $tiempoAsignacionSegundos,
                't_resolucion_segundos' => $tiempoResolucionSegundos,
                't_aceptacion_segundos' => $tiempoAceptacionSegundos,
                'solicitud_id' => $this->solicitud,
            ]);
            if ($create) {
                $this->reset();
                $this->emit('ok_ans');
                $this->emit('cargarAns');
            }
        } else {
            $this->ans_old->nivel = Str::upper($this->nivel);
            $this->ans_old->h_atencion = $this->h_atencion;
            $this->ans_old->t_asignacion_segundos = $tiempoAsignacionSegundos;
            $this->ans_old->t_resolucion_segundos = $tiempoResolucionSegundos;
            $this->ans_old->t_aceptacion_segundos = $tiempoAceptacionSegundos;
            $this->ans_old->solicitud_id = $this->solicitud;
            $this->ans_old->save();
            $this->emit('update_ans_ok');
            $this->emit('cargarAns');
            $this->resetear();
        }
    }

    public function resetear()
    {
        $this->reset();
        $this->resetValidation();
        $this->emit('selectSolicitud');
    }

    public function render()
    {
        if (!$this->solicitud) {
            $this->solicitudes = TipoSolicitud::where('estado', '0')->get();
        }
        return view('livewire.ans.form-ans');
    }
}
