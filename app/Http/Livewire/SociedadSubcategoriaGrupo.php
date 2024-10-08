<?php

namespace App\Http\Livewire;

use App\Models\Sociedad;
use App\Models\Subcategoria;
use App\Models\Grupo;
use App\Models\Categoria; // Agregar el modelo de Categoria
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SociedadSubcategoriaGrupo extends Component
{
    public $sociedades;
    public $subcategorias;
    public $categorias; // Nueva propiedad para categorías
    public $grupos;
    public $sociedad_id;
    public $subcategoria_id;
    public $categoria_id;  // Nueva propiedad para categoría
    public $grupo_id;
    public $edit_mode = false;  // Para verificar si estamos editando
    public $relacion_id;  // ID de la relación que se va a editar
    public $supervisores;
    public $supervisor_id;

    // Definir el listener que escuchará el evento para cargar relaciones y editar
    protected $listeners = ['cargarRelaciones', 'editRelacion'];

    // Reglas de validación para el formulario
    protected $rules = [
        'sociedad_id' => 'required',
        'categoria_id' => 'required', // Validar la categoría
        'subcategoria_id' => 'required',
        'grupo_id' => 'required',
        'supervisor_id' => 'required|exists:users,id',
    ];

    // Mensajes de error personalizados
    protected $messages = [
        'sociedad_id.required' => 'El campo Sociedad es obligatorio.',
        'categoria_id.required' => 'El campo Categoría es obligatorio.',  // Mensaje para categoría
        'subcategoria_id.required' => 'El campo Subcategoría es obligatorio.',
        'grupo_id.required' => 'El campo Grupo es obligatorio.',
        'supervisor_id.required' => 'El campo Supervisor es obligatorio.',
        'supervisor_id.exists' => 'El supervisor seleccionado no es válido.',
    ];

    public function mount()
    {
        // Cargar sociedades, subcategorías, categorías y grupos al iniciar el componente
        $this->sociedades = Sociedad::all();
        $this->categorias = Categoria::where('estado', 0)->get();  // Cargar las categorías
        $this->subcategorias = Subcategoria::all();
        $this->grupos = Grupo::all();
        $this->supervisores = User::role(['Agente', 'Admin'])->get();
    }

    public function agregarRelacion()
    {
        // Validar los campos del formulario
        $this->validate();

        // Verificar si ya existe la relación antes de agregar o actualizar
        $existe = DB::table('sociedad_subcategoria_grupo')
            ->where('sociedad_id', $this->sociedad_id)
            ->where('categoria_id', $this->categoria_id) // Verificar categoría
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
                    'categoria_id' => $this->categoria_id,  // Actualizar la categoría
                    'subcategoria_id' => $this->subcategoria_id,
                    'grupo_id' => $this->grupo_id,
                    'supervisor_id' => $this->supervisor_id
                ]);

            $this->emit('showToast', ['type' => 'success', 'message' => 'Relación actualizada exitosamente!']);
        } else {
            // Si no estamos en modo de edición y la relación no existe, insertamos la nueva relación
            DB::table('sociedad_subcategoria_grupo')->insert([
                'sociedad_id' => $this->sociedad_id,
                'categoria_id' => $this->categoria_id,  // Agregar la categoría
                'subcategoria_id' => $this->subcategoria_id,
                'grupo_id' => $this->grupo_id,
                'supervisor_id' => $this->supervisor_id,
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
        // Obtener las relaciones entre sociedades, categorías, subcategorías y grupos
        return DB::table('sociedad_subcategoria_grupo')
            ->join('sociedades', 'sociedad_subcategoria_grupo.sociedad_id', '=', 'sociedades.id')
            ->join('categorias', 'sociedad_subcategoria_grupo.categoria_id', '=', 'categorias.id')  // Relación con la categoría
            ->join('tipo_solicitudes', 'categorias.solicitud_id', '=', 'tipo_solicitudes.id') // Unir con solicitudes
            ->join('subcategorias', 'sociedad_subcategoria_grupo.subcategoria_id', '=', 'subcategorias.id')
            ->join('grupos', 'sociedad_subcategoria_grupo.grupo_id', '=', 'grupos.id')
            ->join('users', 'sociedad_subcategoria_grupo.supervisor_id', '=', 'users.id')
            ->select(
                'sociedad_subcategoria_grupo.id',
                'sociedades.nombre as sociedad',
                'categorias.nombre as categoria',
                'tipo_solicitudes.nombre as solicitud', // Añadir la solicitud asociada
                'subcategorias.nombre as subcategoria',
                'grupos.nombre as grupo',
                'users.name as supervisor'
            )
            ->get()->toArray();
    }


    public function editRelacion($relacionId)
    {
        // Obtener los detalles de la relación seleccionada para editar
        $relacion = DB::table('sociedad_subcategoria_grupo')->where('id', $relacionId)->first();

        // Cargar los datos en los formularios para editar
        $this->sociedad_id = $relacion->sociedad_id;
        $this->categoria_id = $relacion->categoria_id;  // Cargar la categoría
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
        $this->categoria_id = null;  // Resetear la categoría
        $this->subcategoria_id = null;
        $this->grupo_id = null;
        $this->edit_mode = false;
        $this->relacion_id = null;
        $this->supervisor_id = null;
    }

    public function render()
    {
        // Renderizar la vista del componente
        return view('livewire.sociedad-subcategoria-grupo');
    }
}
