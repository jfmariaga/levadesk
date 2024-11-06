<?php

namespace App\Http\Livewire\Ticket;

use App\Models\Ticket;
use App\Models\Sociedad;
use App\Models\TipoSolicitud;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\ANS;
use App\Models\Aplicaciones;
use App\Models\Estado;
use App\Models\Grupo;
use App\Models\Historial;
use App\Models\Urgencia;
use App\Models\User;
use App\Notifications\TicketAsignado;
use App\Notifications\TicketCreado;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;


class FormTickets extends Component
{
    use WithfileUploads;
    public $sociedades;
    public $identificar;
    public $urgencias;
    public $tipos_solicitud;
    public $categorias = [];
    public $subcategorias = [];
    public $sociedad_id;
    public $tipo_solicitud_id;
    public $categoria_id;
    public $subcategoria_id;
    public $titulo;
    public $descripcion;
    public $archivos = [];
    public $nomenclatura;
    public $ans_id;
    public $estado_id;
    public $ticket_old;
    public $urgencia;
    public $usuarioId;
    public $aplicaciones = []; // Campo para almacenar las aplicaciones
    public $aplicacion_id; // Para almacenar la aplicación seleccionada
    public $esExcepcion = false; // Para marcar si es una excepción
    public $usuario_sap;
    public $modulo;
    public $fecha_inicio;
    public $fecha_fin;



    protected $rules = [
        'titulo'            => 'required|string|max:255',
        'descripcion'       => 'required|string|min:20|max:325',
        'sociedad_id'       => 'required|exists:sociedades,id',
        'tipo_solicitud_id' => 'required|exists:tipo_solicitudes,id',
        'categoria_id'      => 'required|exists:categorias,id',
        'subcategoria_id'   => 'required|exists:subcategorias,id',
        'archivos.*'        => 'nullable|file|mimes:jpg,png,pdf,doc,docx,xlsx,xls,msg,eml',
        'urgencia'          => 'required',
        'archivos'          => 'array|max:2',
        'aplicacion_id'     => 'nullable|exists:aplicaciones,id',
        // 'usuario_sap'       => 'required_if:esExcepcion,true|string',  // Solo requerido si es una excepción
        // 'modulo'            => 'required_if:esExcepcion,true|string',
        // 'fecha_inicio'      => 'required_if:esExcepcion,true|date',
        // 'fecha_fin'         => 'required_if:esExcepcion,true|date|after_or_equal:fecha_inicio',
    ];

    // Mensajes de error personalizados
    protected $messages = [
        'titulo.required'            => 'El campo Título es obligatorio.',
        'titulo.string'              => 'El campo Título debe ser una cadena de texto.',
        'titulo.max'                 => 'El campo Título no debe exceder los 255 caracteres.',

        'descripcion.required'       => 'El campo Descripción es obligatorio.',
        'descripcion.string'         => 'El campo Descripción debe ser una cadena de texto.',
        'descripcion.min'            => 'El campo Descripción debe tener al menos 20 caracteres.',
        'descripcion.max'            => 'El campo Descripción no debe exceder los 325 caracteres.',

        'sociedad_id.required'       => 'El campo Sociedad es obligatorio.',
        'sociedad_id.exists'         => 'La Sociedad seleccionada no es válida.',

        'tipo_solicitud_id.required' => 'El campo Tipo de Solicitud es obligatorio.',
        'tipo_solicitud_id.exists'   => 'El Tipo de Solicitud seleccionado no es válido.',

        'categoria_id.required'      => 'El campo Categoría es obligatorio.',
        'categoria_id.exists'        => 'La Categoría seleccionada no es válida.',

        'subcategoria_id.required'   => 'El campo Subcategoría es obligatorio.',
        'subcategoria_id.exists'     => 'La Subcategoría seleccionada no es válida.',

        'archivos.*.file'            => 'Cada archivo debe ser un archivo válido.',
        'archivos.*.mimes'           => 'Los archivos deben ser de tipo: jpg, png, pdf, doc, docx, xlsx, xls, msg, eml.',
        'archivos.array'             => 'El campo Archivos debe ser un arreglo.',
        'archivos.max'               => 'No se pueden subir más de 2 archivos.',

        'urgencia.required'          => 'El campo Urgencia es obligatorio.',

        'aplicacion_id.exists'       => 'La Aplicación seleccionada no es válida.',

        // Mensajes opcionales para los campos condicionales
        // 'usuario_sap.required_if'   => 'El campo Usuario SAP es obligatorio cuando es una excepción.',
        // 'modulo.required_if'        => 'El campo Módulo es obligatorio cuando es una excepción.',
        // 'fecha_inicio.required_if'  => 'El campo Fecha de Inicio es obligatorio cuando es una excepción.',
        // 'fecha_fin.required_if'     => 'El campo Fecha de Fin es obligatorio cuando es una excepción.',
        // 'fecha_fin.after_or_equal'  => 'La Fecha de Fin debe ser igual o posterior a la Fecha de Inicio.',
    ];


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        $this->sociedades = Sociedad::where('estado', 0)->get();
        $this->urgencias = Urgencia::all();
        $this->tipos_solicitud = TipoSolicitud::where('estado', 0)->get();
        $this->estado_id = Estado::where('nombre', 'Asignado')->first()->id;
        $this->identificar =  rand();
        $this->usuarioId = Auth::id();
    }

    public function updatedTipoSolicitudId($value)
    {
        $this->categorias = Categoria::where('solicitud_id', $value)->where('estado', 0)->get();
        $this->subcategorias = [];
        $this->aplicaciones = [];
    }

    public function updatedCategoriaId($value)
    {
        // Definir las sociedades permitidas para la subcategoría 'SOPORTE DE DISPOSITIVOS MOVILES FUERZA DE VENTAS'
        $sociedadesPermitidas = ['ECUADOR', 'REPÚBLICA DOMINICANA', 'LEVAPAN'];

        // Obtener la sociedad seleccionada
        $sociedad = Sociedad::find($this->sociedad_id);

        $this->subcategorias = Subcategoria::where('categoria_id', $value)
            ->where('estado', 0)  // Filtrar por estado
            ->when($sociedad, function ($query) use ($sociedad, $sociedadesPermitidas) {
                // Filtrar la subcategoría específica solo si la sociedad está permitida
                return $query->when($sociedad && !in_array($sociedad->nombre, $sociedadesPermitidas), function ($query) {
                    return $query->where('nombre', '!=', 'SOPORTE DE DISPOSITIVOS MOVILES FUERZA DE VENTAS');
                });
            })
            ->get();

        // Limpiar las aplicaciones cuando se actualiza la categoría
        $this->aplicaciones = [];
    }


    public function updatedSociedadId($value)
    {
        // Limpiar aplicaciones al cambiar de sociedad
        $this->aplicaciones = [];

        if ($this->categoria_id) {
            $this->updatedCategoriaId($this->categoria_id);
        }

        // Verificar si la subcategoría seleccionada es SOPORTE DE APLICACIONES
        if ($this->subcategoria_id && Subcategoria::find($this->subcategoria_id)->nombre === 'SOPORTE DE APLICACIONES') {
            $this->aplicaciones = Aplicaciones::where('sociedad_id', $this->sociedad_id)->where('estado', 0)->get();
        }
    }

    public function updatedSubcategoriaId($value)
    {
        $this->aplicaciones = [];

        // Verificar si la subcategoría seleccionada es SOPORTE DE APLICACIONES
        $subcategoria = Subcategoria::find($value);
        if ($subcategoria && $subcategoria->nombre === 'SOPORTE DE APLICACIONES') {
            $this->aplicaciones = Aplicaciones::where('sociedad_id', $this->sociedad_id)->where('estado', 0)->get();
        } else {
            $this->aplicacion_id = null; // Si no es SOPORTE DE APLICACIONES, ocultar el campo de aplicaciones
        }

        // Verificar si la subcategoría es una excepción
        if ($subcategoria && $subcategoria->nombre === 'EXCEPCIONES') {
            $this->esExcepcion = true;
        } else {
            $this->esExcepcion = false;
        }
    }

    public function submit()
    {
        $this->validate();

        if (count($this->archivos) > 2) {
            $this->addError('archivos', 'No se pueden subir más de dos archivos.');
            return;
        }

        // Obtener la subcategoría seleccionada
        $subcategoria = Subcategoria::find($this->subcategoria_id);

        if (!$subcategoria) {
            // session()->flash('error', 'Subcategoría no encontrada');
            $this->emit('showToast', ['type' => 'warning', 'message' => "Subcategoría no encontrada."]);
            return;
        }

        // Obtener la categoría seleccionada
        $categoria = Categoria::find($this->categoria_id);

        if (!$categoria) {
            $this->emit('showToast', ['type' => 'warning', 'message' => "Categoría no encontrada."]);

            return;
        }

        // Definir el usuario que será asignado al ticket
        $usuario = null;
        $grupo = null;

        // Si la subcategoría es SOPORTE DE APLICACIONES, asignar según la aplicación seleccionada
        if ($subcategoria->nombre === 'SOPORTE DE APLICACIONES') {
            // Obtener la aplicación seleccionada
            $aplicacion = Aplicaciones::find($this->aplicacion_id);

            // Verificar que la aplicación existe y tiene un grupo asociado
            if ($aplicacion && $aplicacion->grupo_id) {
                // Cargar el grupo relacionado con la aplicación
                $grupo = $aplicacion->grupo;

                // Verificar que el grupo existe
                if ($grupo) {
                    // Obtener el usuario con menos tickets en el grupo relacionado con la aplicación
                    $usuario = $grupo->usuarios()->withCount('ticketsAsignados')->orderBy('tickets_asignados_count', 'asc')->first();
                } else {
                    $this->emit('showToast', ['type' => 'warning', 'message' => "No hay grupo asignado a la aplicación seleccionada."]);
                    return;
                }
            } else {
                $this->emit('showToast', ['type' => 'warning', 'message' => "No hay grupo o usuarios asignados a la aplicación seleccionada."]);
                return;
            }
        } else {
            // Obtener el grupo relacionado con la subcategoría, categoría y sociedad seleccionadas
            $grupo = $subcategoria->gruposPorSociedad($this->sociedad_id, $this->categoria_id)->first();
            if (!$grupo) {
                $this->emit('showToast', ['type' => 'warning', 'message' => "No hay grupo asignado para esta combinación de sociedad, categoría y subcategoría."]);
                return;
            }

            // Obtener el usuario del grupo con menos tickets asignados
            $usuario = $grupo->usuarios()->withCount('ticketsAsignados')->orderBy('tickets_asignados_count', 'asc')->first();
        }

        if (!$usuario) {
            $this->emit('showToast', ['type' => 'warning', 'message' => "No hay usuarios disponibles en el grupo."]);
            return;
        }

        // Lógica de vacaciones
        if ($usuario->en_vacaciones) {
            $backupAgente = $usuario->backups()->first(); // Obtener el primer agente de respaldo
            if ($backupAgente) {
                $usuario = $backupAgente;
            } else {
                $this->emit('showToast', ['type' => 'warning', 'message' => "El usuario está de vacaciones y no tiene un agente de respaldo asignado."]);
                return;
            }
        }

        // Obtenemos el ANS inicial asociado al tipo de solicitud
        $ansInicial = ANS::where('solicitud_id', $this->tipo_solicitud_id)
            ->where('nivel', 'INICIAL')
            ->first();

        // Crear el ticket
        $ticket = Ticket::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'sociedad_id' => $this->sociedad_id,
            'tipo_solicitud_id' => $this->tipo_solicitud_id,
            'categoria_id' => $this->categoria_id,  // Aseguramos que el ticket también incluya la categoría
            'subcategoria_id' => $this->subcategoria_id,
            'nomenclatura' => $this->generateNomenclatura(),
            'estado_id' => $this->estado_id,
            'creador_id' => Auth::id(),
            'asignado_a' => $usuario->id,
            'usuario_id' => Auth::id(),
            'grupo_id' => $grupo->id,
            'urgencia_id' => $this->urgencia,
            'aplicacion_id' => $this->aplicacion_id,
            'tiempo_restante' => 3600,
            'ans_id' => $ansInicial ? $ansInicial->id : null,
        ]);

        if ($this->esExcepcion) {
            $ticket->excepcion()->create([
                'usuario_sap' => $this->usuario_sap,
                'modulo' => $this->modulo,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
            ]);
        }

        // Guardar archivos si los hay
        if ($this->archivos) {
            foreach ($this->archivos as $archivo) {
                $nombre_original = $archivo->getClientOriginalName();
                $nombre_sin_extension = pathinfo($nombre_original, PATHINFO_FILENAME);
                $extension = $archivo->getClientOriginalExtension();
                $nombre_db = Str::slug($nombre_sin_extension);
                $nombre_a_guardar = $nombre_db . '.' . $extension;
                $ruta = $archivo->storeAs('public/tickets', $nombre_a_guardar);

                $ticket->archivos()->create([
                    'ruta' => $ruta,
                ]);
            }
        }

        // Enviar notificaciones
        $ticket->usuario->notify(new TicketCreado($ticket));
        $usuario->notify(new TicketAsignado($ticket));

        // Registrar en el historial
        Historial::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'Nuevo',
            'detalle' => 'Nuevo ticket',
        ]);

        Historial::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'Asignado',
            'detalle' => 'Ticket asignado por el sistema a ' . $usuario->name,
        ]);

        $this->emit('cargarTickets');
        $this->emit('ok_ticket');
        $this->resetForm();
    }


    public function generateNomenclatura()
    {
        // Genera la nomenclatura del ticket
        $sociedad = Sociedad::find($this->sociedad_id)->codigo;
        $tipoSolicitud = TipoSolicitud::find($this->tipo_solicitud_id)->codigo;
        $categoria = Categoria::find($this->categoria_id)->codigo;
        $subcategoria = Subcategoria::find($this->subcategoria_id)->codigo;
        // Contar tickets que tienen la misma nomenclatura base
        $baseNomenclatura = "{$sociedad}{$tipoSolicitud}{$categoria}{$subcategoria}";
        $numeroTicket = Ticket::where('nomenclatura', 'LIKE', "{$baseNomenclatura}%")->count() + 1;

        return "{$baseNomenclatura}{$numeroTicket}";
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->titulo = "";
        $this->urgencia = "";
        $this->descripcion = "";
        $this->sociedad_id = "";
        $this->tipo_solicitud_id = "";
        $this->categoria_id = "";
        $this->aplicacion_id = "";
        $this->subcategoria_id = "";
        $this->archivos = [];
        $this->ticket_old = null;
        $this->esExcepcion = false; // Para marcar si es una excepción
        $this->usuario_sap = "";
        $this->modulo = "";
        $this->fecha_inicio = "";
        $this->fecha_fin = "";
        $this->identificar =  rand();
    }

    public function getIcon($extension)
    {
        $icons = [
            'pdf' => asset('icons/pdf-icon.png'),
            'doc' => asset('icons/word-icon.png'),
            'docx' => asset('icons/word-icon.png'),
            'zip' => asset('icons/zip-icon.png'),
            'rar' => asset('icons/zip-icon.png'),
            'xls' => asset('icons/excel-icon.png'),
            'xlsx' => asset('icons/excel-icon.png'),
        ];

        return $icons[$extension] ?? asset('icons/default-icon.png');
        $this->resetValidation();
    }


    public function render()
    {
        return view('livewire.ticket.form-tickets');
    }
}
