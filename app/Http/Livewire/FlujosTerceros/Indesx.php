<?php

namespace App\Http\Livewire\FlujosTerceros;

use Livewire\Component;
use App\Models\FlujoTercero;
use App\Models\Aplicaciones;
use App\Models\User;
use App\Models\Tercero;

class Indesx extends Component
{
    public $flujos;
    public $aplicacion_id;
    public $tercero_id;
    public $usuario_id;
    public $destinatarios = [];
    public $activo = true;
    public $flujo_id;

    protected $rules = [
        'aplicacion_id' => 'required|exists:aplicaciones,id',
        'tercero_id'    => 'required|exists:terceros,id',
        'usuario_id'    => 'nullable|exists:users,id',
        'destinatarios' => 'nullable|array',
    ];

    public function mount()
    {
        $this->loadFlujos();
    }

    public function render()
    {
        return view('livewire.flujos-terceros.indesx', [
            'aplicaciones' => Aplicaciones::with('sociedad')->get(),
            'usuarios'     => User::role(['admin', 'agente'])->get(),
            'terceros'     => Tercero::all(),
        ]);
    }

    public function save()
    {
        $this->validate();

        FlujoTercero::updateOrCreate(
            ['id' => $this->flujo_id],
            [
                'aplicacion_id' => $this->aplicacion_id,
                'tercero_id'    => $this->tercero_id,
                'usuario_id'    => $this->usuario_id,
                'destinatarios' => $this->destinatarios,
                'activo'        => $this->activo,
            ]
        );

        $this->resetForm();
        $this->loadFlujos();

        $this->dispatchBrowserEvent('showToast', [
            'type' => 'success',
            'message' => '✅ Flujo guardado con éxito'
        ]);

        $this->dispatchBrowserEvent('reset-form');
    }

    public function edit($id)
    {
        $flujo = FlujoTercero::findOrFail($id);

        $this->flujo_id      = $flujo->id;
        $this->aplicacion_id = $flujo->aplicacion_id;
        $this->tercero_id    = $flujo->tercero_id;
        $this->usuario_id    = $flujo->usuario_id;
        $this->destinatarios = $flujo->destinatarios ?? [];
        $this->activo        = $flujo->activo;

        $this->dispatchBrowserEvent('load-selects', [
            'aplicacion_id' => $this->aplicacion_id,
            'tercero_id'    => $this->tercero_id,
            'usuario_id'    => $this->usuario_id,
        ]);

        $this->dispatchBrowserEvent('load-tagsinput', [
            'destinatarios' => $this->destinatarios,
        ]);
    }

    public function deleteFlujoConfirmed($id)
    {
        FlujoTercero::findOrFail($id)->delete();
        $this->loadFlujos();
        $this->dispatchBrowserEvent('reset-form');

        $this->dispatchBrowserEvent('showToast', [
            'type' => 'success',
            'message' => '🗑️ Flujo eliminado correctamente'
        ]);
    }

    private function resetForm()
    {
        $this->flujo_id      = null;
        $this->aplicacion_id = null;
        $this->tercero_id    = null;
        $this->usuario_id    = null;
        $this->destinatarios = [];
        $this->activo        = true;
    }

    private function loadFlujos()
    {
        $this->flujos = FlujoTercero::with('aplicacion.sociedad', 'usuario', 'tercero')->get();
    }
}
