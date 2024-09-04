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
        // dd($aplicaciones);
        $this->emit('cargarTablaAplicaciones', $aplicaciones);
    }

    public function render()
    {
        return view('livewire.aplicaciones.index');
    }
}
