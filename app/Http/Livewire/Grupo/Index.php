<?php

namespace App\Http\Livewire\Grupo;

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

        // Buscar relaciones con JOIN a sociedades
        $relacionesSociedad = DB::table('sociedad_subcategoria_grupo as ssg')
            ->join('sociedades as s', 's.id', '=', 'ssg.sociedad_id')
            ->where('ssg.grupo_id', $id)
            ->select('ssg.id', 's.nombre as sociedad')
            ->get();

        // Buscar aplicaciones con JOIN a sociedades
        $aplicaciones = DB::table('aplicaciones as a')
            ->join('sociedades as s', 's.id', '=', 'a.sociedad_id')
            ->where('a.grupo_id', $id)
            ->select('a.id', 'a.nombre as aplicacion', 's.nombre as sociedad')
            ->get();

        if ($relacionesSociedad->count() > 0 || $aplicaciones->count() > 0) {
            $relaciones = $relacionesSociedad->map(function ($item) {
                return [
                    'tipo' => 'Relación',
                    'sociedad' => $item->sociedad,
                    'detalle' => "ID Relación: {$item->id}"
                ];
            });

            $apps = $aplicaciones->map(function ($app) {
                return [
                    'tipo' => 'Aplicación',
                    'sociedad' => $app->sociedad,
                    'detalle' => $app->aplicacion
                ];
            });

            $detalles = $relaciones->merge($apps);

            $this->emit('showRelationModal', [
                'titulo' => 'No se puede eliminar este grupo',
                'detalles' => $detalles
            ]);
        } else {
            $grupo->delete();
            $this->cargarGrupo();

            $this->emit('showToast', [
                'type' => 'success',
                'message' => 'Grupo eliminado'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.grupo.index');
    }
}
