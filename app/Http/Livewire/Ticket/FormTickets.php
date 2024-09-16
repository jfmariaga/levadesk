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



    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'sociedad_id' => 'required|exists:sociedades,id',
        'tipo_solicitud_id' => 'required|exists:tipo_solicitudes,id',
        'categoria_id' => 'required|exists:categorias,id',
        'subcategoria_id' => 'required|exists:subcategorias,id',
        'archivos.*' => 'nullable|file|mimes:jpg,png,pdf,doc,docx,xlsx,xls',
        'urgencia' => 'required',
        'archivos' => 'array|max:2',
        'aplicacion_id' => 'nullable|exists:aplicaciones,id',
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
        $this->subcategorias = Subcategoria::where('categoria_id', $value)
        ->where('estado', 0)
        ->get();
        $this->aplicaciones = [];
    }

    public function updatedSociedadId($value)
    {
        // Limpiar aplicaciones al cambiar de sociedad
        $this->aplicaciones = [];

        // Verificar si la subcategoría seleccionada es SOPORTE DE APLICACIONES
        if ($this->subcategoria_id && Subcategoria::find($this->subcategoria_id)->nombre === 'SOPORTE DE APLICACIONES') {
            $this->aplicaciones = Aplicaciones::where('sociedad_id', $this->sociedad_id)->get();
        }
    }

    public function updatedSubcategoriaId($value)
    {
        $this->aplicaciones = [];

        // Verificar si la subcategoría seleccionada es SOPORTE DE APLICACIONES
        $subcategoria = Subcategoria::find($value);
        if ($subcategoria && $subcategoria->nombre === 'SOPORTE DE APLICACIONES') {
            $this->aplicaciones = Aplicaciones::where('sociedad_id', $this->sociedad_id)->get();
        } else {
            $this->aplicacion_id = null; // Si no es SOPORTE DE APLICACIONES, ocultar el campo de aplicaciones
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

        // Asegurarse de que $subcategoria no es null
        if (!$subcategoria) {
            session()->flash('error', 'Subcategoría no encontrada');
            return;
        }

        // Definir el usuario que será asignado al ticket
        $usuario = null;
        $grupo = null; // Definir el grupo aquí, porque el grupo dependerá de la subcategoría o aplicación

        // Si la subcategoría es SOPORTE DE APLICACIONES, asignar según la aplicación seleccionada
        if ($subcategoria->nombre === 'SOPORTE DE APLICACIONES') {
            // Obtener la aplicación seleccionada
            $aplicacion = Aplicaciones::find($this->aplicacion_id);

            // Verificar que la aplicación existe y tiene un grupo asociado
            if ($aplicacion && $aplicacion->grupo_id) {
                // Cargar el grupo relacionado con la aplicación a través de la relación grupo_id
                $grupo = $aplicacion->grupo;

                // Verificar que el grupo existe
                if ($grupo) {
                    // Obtener el usuario con menos tickets en el grupo relacionado con la aplicación
                    $usuario = $grupo->usuarios()->withCount('ticketsAsignados')->orderBy('tickets_asignados_count', 'asc')->first();
                } else {
                    session()->flash('error', 'No hay grupo asignado a la aplicación seleccionada.');
                    return;
                }
            } else {
                session()->flash('error', 'No hay grupo o usuarios asignados a la aplicación seleccionada.');
                return;
            }
        } else {
            // Obtener el grupo asociado a la subcategoría
            $grupo = $subcategoria->grupo;

            // Verificar que $grupo no es null
            if (!$grupo) {
                session()->flash('error', 'No hay grupo asignado a la subcategoría seleccionada');
                return;
            }

            // Obtener el usuario del grupo con menos tickets asignados
            $usuario = $grupo->usuarios()->withCount('ticketsAsignados')->orderBy('tickets_asignados_count', 'asc')->first();
        }

        if (!$usuario) {
            session()->flash('error', 'No hay usuarios disponibles en el grupo');
            return;
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
            'categoria_id' => $this->categoria_id,
            'subcategoria_id' => $this->subcategoria_id,
            'nomenclatura' => $this->generateNomenclatura(),
            'estado_id' => $this->estado_id,
            'creador_id' => Auth::id(),
            'asignado_a' => $usuario->id,
            'usuario_id' => Auth::id(),
            'grupo_id' => $grupo->id, // Grupo de la subcategoría o la aplicación, dependiendo del caso
            'urgencia_id' => $this->urgencia,
            'aplicacion_id' => $this->aplicacion_id,
            'ans_id' => $ansInicial ? $ansInicial->id : null,  // Asignar ANS inicial
        ]);

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
        $numeroTicket = Ticket::where('sociedad_id', $this->sociedad_id)->count() + 1;

        return "{$sociedad}{$tipoSolicitud}{$categoria}{$subcategoria}{$numeroTicket}";
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
