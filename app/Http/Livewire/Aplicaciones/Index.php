<?php

namespace App\Http\Livewire\Aplicaciones;

use App\Models\Aplicaciones;
use Livewire\Component;

class Index extends Component
{
    public $sociedad_id;
    protected $listeners = ['getAplicaciones'];

    protected $queryString = ['sociedad_id'];

    public function mount()
    {
        $this->getAplicaciones();
    }

    public function getAplicaciones(){
        $aplicaciones = Aplicaciones::where('sociedad_id', $this->sociedad_id)->with(['sociedad', 'grupo'])->get()->toJson();
        $this->emit('cargarTablaAplicaciones', $aplicaciones);
    }

    public function deleteAplicacion($id){
        $aplicacion = Aplicaciones::find($id);
        $aplicacion->delete();
        $this->getAplicaciones();
        $this->emit('showToast', ['type' => 'success', 'message' => 'Aplicación eliminada']);
    }

    public function render()
    {
        return view('livewire.aplicaciones.index');
    }
}
