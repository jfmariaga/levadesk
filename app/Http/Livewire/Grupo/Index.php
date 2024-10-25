<?php

namespace App\Http\Livewire\Grupo;

use App\Models\Aplicaciones;
use App\Models\Grupo;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class Index extends Component
{
    protected $listeners = ['cargarGrupo'];

    public function cargarGrupo()
    {
        $grupos = Grupo::with('usuarios')->get()->toJson();
        $this->emit('cargarGruposTabla', $grupos);
    }

    public function deleteGrupo($id)
    {
        $grupo = Grupo::find($id);
        $existe = DB::table('sociedad_subcategoria_grupo')
            ->where('grupo_id', $id)
            ->exists();

        $aplicacion =  Aplicaciones::where('grupo_id', $id)->get();

        if ($existe || $aplicacion) {
            $this->emit('showToast', ['type' => 'warning', 'message' => 'No se puede eliminar este grupo, ya que esta relacionado con un flujo activo']);
        } else {
            $grupo->delete();
            $this->cargarGrupo();
            $this->emit('showToast', ['type' => 'success', 'message' => 'Grupo eliminado']);
        }
    }

    public function render()
    {
        return view('livewire.grupo.index');
    }
}
