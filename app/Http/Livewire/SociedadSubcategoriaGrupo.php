<?php

namespace App\Http\Livewire;

use App\Models\Sociedad;
use App\Models\Subcategoria;
use App\Models\Grupo;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SociedadSubcategoriaGrupo extends Component
{
    public $sociedades;
    public $subcategorias;
    public $grupos;
    public $sociedad_id;
    public $subcategoria_id;
    public $grupo_id;
    public $edit_mode = false;  // Para verificar si estamos editando
    public $relacion_id;  // ID de la relación que se va a editar

    // Definir el listener que escuchará el evento para cargar relaciones y editar
    protected $listeners = ['cargarRelaciones', 'editRelacion'];

    // Reglas de validación para el formulario
    protected $rules = [
        'sociedad_id' => 'required',
        'subcategoria_id' => 'required',
        'grupo_id' => 'required',
    ];

    // Mensajes de error personalizados
    protected $messages = [
        'sociedad_id.required' => 'El campo Sociedad es obligatorio.',
        'subcategoria_id.required' => 'El campo Subcategoría es obligatorio.',
        'grupo_id.required' => 'El campo Grupo es obligatorio.',
    ];

    public function mount()
    {
        // Cargar sociedades, subcategorías y grupos al iniciar el componente
        $this->sociedades = Sociedad::all();
        $this->subcategorias = Subcategoria::all();
        $this->grupos = Grupo::all();
    }

    public function agregarRelacion()
    {
        // Validar los campos del formulario
        $this->validate();

        // Verificar si ya existe la relación antes de agregar o actualizar
        $existe = DB::table('sociedad_subcategoria_grupo')
            ->where('sociedad_id', $this->sociedad_id)
            ->where('subcategoria_id', $this->subcategoria_id)
            ->where('grupo_id', $this->grupo_id)
            ->exists();

        if ($existe && !$this->edit_mode) {
            // Si ya existe y no estamos en modo de edición, mostrar un error
            $this->emit('showToast', ['type' => 'error', 'message' => 'Esta relación ya existe.']);
        } elseif ($this->edit_mode) {
            // Si estamos en modo de edición, actualizamos la relación existente
            DB::table('sociedad_subcategoria_grupo')
                ->where('id', $this->relacion_id)
                ->update([
                    'sociedad_id' => $this->sociedad_id,
                    'subcategoria_id' => $this->subcategoria_id,
                    'grupo_id' => $this->grupo_id,
                ]);

            $this->emit('showToast', ['type' => 'success', 'message' => 'Relación actualizada exitosamente!']);
        } else {
            // Si no estamos en modo de edición y la relación no existe, insertamos la nueva relación
            DB::table('sociedad_subcategoria_grupo')->insert([
                'sociedad_id' => $this->sociedad_id,
                'subcategoria_id' => $this->subcategoria_id,
                'grupo_id' => $this->grupo_id,
            ]);

            $this->emit('showToast', ['type' => 'success', 'message' => 'Relación agregada exitosamente!']);
        }

        // Emitir el evento para recargar la tabla con las nuevas relaciones
        $this->emit('cargarRelacionesTabla', json_encode($this->obtenerRelaciones()));

        // Resetear el formulario después de agregar o editar
        $this->resetForm();
    }

    public function eliminarRelacion($relacionId)
    {
        // Eliminar la relación seleccionada
        DB::table('sociedad_subcategoria_grupo')->where('id', $relacionId)->delete();

        // Emitir el evento para recargar la tabla después de eliminar la relación
        $this->emit('cargarRelacionesTabla', json_encode($this->obtenerRelaciones()));
        $this->emit('showToast', ['type' => 'error', 'message' => 'Relación eliminada exitosamente!']);
    }

    public function cargarRelaciones()
    {
        // Emitir las relaciones a la tabla
        $relaciones = $this->obtenerRelaciones();
        $this->emit('cargarRelacionesTabla', json_encode($relaciones));
    }

    public function obtenerRelaciones()
    {
        // Obtener las relaciones entre sociedades, subcategorías y grupos
        return DB::table('sociedad_subcategoria_grupo')
            ->join('sociedades', 'sociedad_subcategoria_grupo.sociedad_id', '=', 'sociedades.id')
            ->join('subcategorias', 'sociedad_subcategoria_grupo.subcategoria_id', '=', 'subcategorias.id')
            ->join('grupos', 'sociedad_subcategoria_grupo.grupo_id', '=', 'grupos.id')
            ->select('sociedad_subcategoria_grupo.id', 'sociedades.nombre as sociedad', 'subcategorias.nombre as subcategoria', 'grupos.nombre as grupo')
            ->get()->toArray();
    }

    public function editRelacion($relacionId)
    {
        // Obtener los detalles de la relación seleccionada para editar
        $relacion = DB::table('sociedad_subcategoria_grupo')->where('id', $relacionId)->first();

        // Cargar los datos en los formularios para editar
        $this->sociedad_id = $relacion->sociedad_id;
        $this->subcategoria_id = $relacion->subcategoria_id;
        $this->grupo_id = $relacion->grupo_id;
        $this->relacion_id = $relacion->id;

        // Cambiar a modo edición
        $this->edit_mode = true;

        // Emitir un evento para notificar que estamos en modo de edición
        $this->emit('showToast', ['type' => 'info', 'message' => 'Editando relación']);
    }

    public function resetForm()
    {
        // Restablecer los campos del formulario
        $this->sociedad_id = null;
        $this->subcategoria_id = null;
        $this->grupo_id = null;
        $this->edit_mode = false;
        $this->relacion_id = null;
    }

    public function render()
    {
        // Renderizar la vista del componente
        return view('livewire.sociedad-subcategoria-grupo');
    }
}
