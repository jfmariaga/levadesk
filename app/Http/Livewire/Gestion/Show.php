<?php

namespace App\Http\Livewire\Gestion;

use App\Models\Aprobacion;
use App\Models\Ticket;
use App\Models\Categoria;
use App\Models\Colaborador;
use App\Models\Subcategoria;
use App\Models\Historial;
use App\Models\Impacto;
use App\Models\Recordatorio;
use App\Models\Tarea;
use App\Models\TipoSolicitud;
use App\Models\User;
use App\Notifications\CambioEstado;
use App\Notifications\FlujoAcceso;
use App\Notifications\NotificacionAprobacion;
use App\Notifications\NuevaTarea;
use App\Notifications\NuevoColaborador;
use App\Notifications\NuevoComentario;
use App\Notifications\NuevoComentarioPrivado;
use App\Notifications\NuevoComentarioSolucion;
use App\Notifications\Reasignado;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Notification;

class Show extends Component
{
    use WithFileUploads;

    public $ticket_id;
    public $ticket;
    public $categorias; // Para re-categorizar
    public $subcategorias; // Para re-categorizar
    public $tipos_solicitud;
    public $categoria_id; // Nuevo valor si se recategoriza
    public $subcategoria_id; // Nuevo valor si se recategoriza
    public $solicitud_id;
    public $recategorizar = false;
    public $impacto = false;
    public $showTimeline = false;
    public $showGestion = false;
    public $recordatorio = false;
    public $participante = false;
    public $categoria_nombre, $subcategoria_nombre, $solicitud_nombre;
    public $impactos;
    public $impacto_id;
    public $prioridad;
    public $acceso = false;
    public $escalar = false;
    public $cambio = false;
    public $tarea = false;
    public $newComment;
    public $reminder_at;
    public $desDetalle;
    public $newFile;
    public $commentType = 0; // Valor por defecto es 'Público'
    public $usuarios = [];
    public $selectedUser;
    public $selectedFuncional;
    public $selectedTi;

    public $titulo;
    public $descripcion;
    public $asignado_a;
    public $estado = 'pendiente';
    public $fecha_cumplimiento;

    protected $rules = [
        'categoria_id' => 'required',
        'subcategoria_id' => 'required',
        'solicitud_id' => 'required'
    ];

    protected $queryString = ['ticket_id'];

    public function mount()
    {
        $this->loadTicket();
        $this->usuarios = User::all();  // Obtener todos los usuarios
    }

    public function crearTarea()
    {
        $this->validate([
            'titulo' => 'required|string',
            'descripcion' => 'nullable|string',
            'asignado_a' => 'nullable|integer|exists:users,id',
            'fecha_cumplimiento' => 'required|date',
        ]);


        $tarea = Tarea::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'user_id' => $this->asignado_a ? $this->asignado_a : auth()->id(),
            'fecha_cumplimiento' => $this->fecha_cumplimiento,
            'ticket_id' => $this->ticket->id,
        ]);

        if ($this->asignado_a) {
            $usuario = User::find($this->asignado_a);
            $usuario->notify(new NuevaTarea($tarea, $this->ticket));
        }

        $this->titulo = '';
        $this->descripcion = '';
        $this->asignado_a = '';
        $this->fecha_cumplimiento = '';
        $this->loadTicket();
        $this->emit('tareaCreada');
    }

    public function flujoAprobacion()
    {
        $this->validate([
            'selectedFuncional' => 'required|exists:users,id',
            'selectedTi' => 'required|exists:users,id',
        ]);

        $aprobacion = Aprobacion::create([
            'ticket_id' => $this->ticket->id,
            'aprobador_funcional_id' => $this->selectedFuncional,
            'aprobador_ti_id' => $this->selectedTi,
            'estado' => 'pendiente',
        ]);

        $this->ticket->update([
            'estado_id' => 8
        ]);

        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => Auth::id(),
            'accion' => 'Inicio de flujo',
            'detalle' => "Se inicio el flujo de aprobación de acceso.",
        ]);

        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => Auth::id(),
            'accion' => 'cambio de estado ticket',
            'detalle' => "El nuevo estado del ticket es: En espera.",
        ]);

        // Notificar al líder funcional
        $aprobacion->aprobadorFuncional->notify(new NotificacionAprobacion($aprobacion, $this->ticket));
        $this->ticket->usuario->notify(new CambioEstado($this->ticket));


        $this->emit('showToast', ['type' => 'success', 'message' => 'Se inicio el flujo correctamente']);
        $this->selectedTi = "";
        $this->selectedFuncional = "";
        $this->loadTicket();
    }


    public function asignarColaborador()
    {
        // Obtener el usuario seleccionado
        $usuario = User::find($this->selectedUser);

        // Verificar si el usuario ya es el agente asignado al ticket
        if ($this->ticket->asignado_a == $usuario->id) {
            $this->emit('agenteExiste');
            return;
        }

        // Verificar si el usuario ya está asignado como colaborador al ticket
        if ($this->ticket->colaboradores->contains('id', $usuario->id)) {
            $this->emit('colaboradorExiste');
            return;
        }

        $this->validate([
            'selectedUser' => 'required|exists:users,id',
        ]);

        // Crear el colaborador y asignarlo al ticket
        Colaborador::create([
            'user_id' => $this->selectedUser,
            'ticket_id' => $this->ticket->id,
        ]);

        $usuarioColaborador = User::find($this->selectedUser);
        // Registrar en el historial la recategorización y reasignación
        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => Auth::id(),
            'accion' => 'Recategorizado y Reasignado',
            'detalle' => "Se agregó a {$usuarioColaborador->name}. Con el rol de colaborador.",
        ]);

        $usuario->notify(new NuevoColaborador($this->ticket));
        // Limpiar el campo seleccionado
        $this->selectedUser = '';
        $this->participante = false;
        $this->emit('colaboradorOk');

        // Actualizar la lista de colaboradores
        $this->ticket->load('colaboradors');
    }


    public function loadTicket()
    {
        $this->ticket = Ticket::with(['categoria', 'subcategoria'])->find($this->ticket_id);
        if (!$this->ticket) {
            return redirect()->to('/404');
        }
        $this->categoria_id = $this->ticket->categoria_id;
        $this->subcategoria_id = $this->ticket->subcategoria_id;
        $this->solicitud_id = $this->ticket->tipo_solicitud_id;
        $this->categoria_nombre = $this->ticket->categoria->nombre;
        $this->subcategoria_nombre = $this->ticket->subcategoria->nombre;
        $this->solicitud_nombre = $this->ticket->tipoSolicitud->nombre;
        $this->impacto_id = $this->ticket->impacto_id ? $this->ticket->impacto_id : 'NULL';
        $this->prioridad = $this->ticket->prioridad ? $this->ticket->prioridad : 'NULL';

        $this->emit('selectImpacto', $this->impacto_id);

        // dd($this->impacto_nombre);
    }

    public function toggleTimeline()
    {
        $this->showTimeline = !$this->showTimeline;
    }

    public function toggleGestion()
    {
        $this->showGestion = !$this->showGestion;
    }

    public function recordatorios()
    {
        $this->recordatorio = !$this->recordatorio;
    }

    public function participantes()
    {
        $this->participante = !$this->participante;
    }

    public function tareas()
    {
        $this->tarea = !$this->tarea;
    }

    public function verificarImpacto()
    {
        $this->emit('verificarOtravez');
    }

    public function guardarRecordatorio()
    {

        $this->validate(['reminder_at' => 'required', 'desDetalle' => 'nullable']);
        $this->ticket->recordatorios()->create([
            'usuario_id' => auth()->id(),
            'reminder_at' => $this->reminder_at,
            'ticket_id' => $this->ticket->id,
            'detalle' => $this->desDetalle,
        ]);

        $this->reminder_at = '';
        $this->desDetalle = '';
        $this->loadTicket(); // Refresh ticket data
    }

    public function eliminarRecordatorio($id)
    {
        Recordatorio::destroy($id);
        $this->loadTicket();
        $this->emit('borrarRecordatorio');
    }

    public function actualizarCategoria()
    {
        $this->validate();

        // Obtener la nueva subcategoría seleccionada
        $subcategoria = Subcategoria::find($this->subcategoria_id);

        // Asegurarse de que $subcategoria no es null
        if (!$subcategoria) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'Subcategoría no encontrada']);
            return;
        }

        // Obtener el grupo asociado a la subcategoría
        $nuevoGrupo = $subcategoria->grupo;

        // Asegurarse de que $nuevoGrupo no es null
        if (!$nuevoGrupo) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'No hay grupo de atención asignado a la subcategoría seleccionada, reportar al administrador del sistema']);
            return;
        }

        // Obtener el usuario asignado al ticket
        $usuarioAsignado = User::find($this->ticket->asignado_a);

        // Verificar si el usuario asignado existe y tiene un grupo
        if ($usuarioAsignado && $usuarioAsignado->grupo) {
            $grupoActual = $usuarioAsignado->grupo;

            // Verificar si el grupo de la nueva subcategoría es diferente al grupo actual
            if ($grupoActual->id !== $nuevoGrupo->id) {
                // Obtener el usuario del nuevo grupo con menos tickets asignados
                $nuevoUsuario = $nuevoGrupo->usuarios()->withCount('ticketsAsignados')->orderBy('tickets_asignados_count', 'asc')->first();

                if (!$nuevoUsuario) {
                    $this->emit('showToast', ['type' => 'error', 'message' => 'No hay usuarios disponibles en el nuevo grupo']);
                    return;
                }

                // Emitir un evento para notificar sobre la reasignación del ticket
                $this->emit('showToast', ['type' => 'warning', 'message' => "El ticket será reasignado de {$usuarioAsignado->name} a {$nuevoUsuario->name}."]);

                // Actualizar el ticket con la nueva información
                $this->ticket->update([
                    'categoria_id' => $this->categoria_id,
                    'subcategoria_id' => $this->subcategoria_id,
                    'tipo_solicitud_id' => $this->solicitud_id,
                    'asignado_a' => $nuevoUsuario->id,  // Reasignar al nuevo usuario
                    'grupo_id' => $nuevoGrupo->id,       // Actualizar el grupo
                ]);

                // Registrar en el historial la recategorización y reasignación
                Historial::create([
                    'ticket_id' => $this->ticket_id,
                    'user_id' => Auth::id(),
                    'accion' => 'Recategorizado y Reasignado',
                    'detalle' => "Ticket recategorizado y reasignado de {$usuarioAsignado->name} a {$nuevoUsuario->name} en el grupo {$nuevoGrupo->nombre}.",
                ]);
            } else {
                // El grupo no cambió, solo actualiza la categoría y subcategoría
                $this->ticket->update([
                    'categoria_id' => $this->categoria_id,
                    'subcategoria_id' => $this->subcategoria_id,
                    'tipo_solicitud_id' => $this->solicitud_id,
                ]);

                // Registrar en el historial la recategorización
                Historial::create([
                    'ticket_id' => $this->ticket_id,
                    'user_id' => Auth::id(),
                    'accion' => 'Recategorizado',
                    'detalle' => "Ticket recategorizado por el usuario {$usuarioAsignado->name}.",
                ]);
            }
        } else {
            // Si no hay usuario asignado o el usuario no tiene grupo, reasignar al nuevo grupo y usuario
            $nuevoUsuario = $nuevoGrupo->usuarios()->withCount('ticketsAsignados')->orderBy('tickets_asignados_count', 'asc')->first();

            if (!$nuevoUsuario) {
                $this->emit('showToast', ['type' => 'error', 'message' => 'No hay usuarios disponibles en el nuevo grupo']);
                return;
            }

            // Emitir un evento para notificar sobre la reasignación del ticket
            $this->emit('showToast', ['type' => 'warning', 'message' => "El ticket será asignado a {$nuevoUsuario->name} en el grupo {$nuevoGrupo->nombre}."]);

            // Actualizar el ticket con la nueva información
            $this->ticket->update([
                'categoria_id' => $this->categoria_id,
                'subcategoria_id' => $this->subcategoria_id,
                'tipo_solicitud_id' => $this->solicitud_id,
                'asignado_a' => $nuevoUsuario->id,  // Asignar al nuevo usuario
                'grupo_id' => $nuevoGrupo->id,       // Asignar al nuevo grupo
            ]);

            // Registrar en el historial la recategorización y asignación
            Historial::create([
                'ticket_id' => $this->ticket_id,
                'user_id' => Auth::id(),
                'accion' => 'Recategorizado y Asignado',
                'detalle' => "Ticket recategorizado y reasignado de {$usuarioAsignado->name} a {$nuevoUsuario->name} en el grupo {$nuevoGrupo->nombre}.",
            ]);
        }

        $nuevoUsuario->notify(new Reasignado($usuarioAsignado, $this->ticket));

        // Recargar los datos del ticket para reflejar los cambios
        $this->loadTicket();
    }


    public function actualizarImpacto()
    {
        $this->validate([
            'impacto_id' => 'required',
        ]);

        $this->ticket->update([
            'impacto_id' => $this->impacto_id
        ]);

        $this->ticket->load('impacto');

        // Calculando la prioridad
        $urgencia = $this->ticket->urgencia->puntuacion;
        $impacto = $this->ticket->impacto->puntuacion;
        // dd($impacto);
        if ($urgencia && $impacto) {
            $prioridadNumerica = $urgencia * $impacto;

            // Determina la categoría de prioridad según la puntuación
            if ($prioridadNumerica >= 500) {
                $prioridadCategoria = 'CRITICA';
            } elseif ($prioridadNumerica >= 300) {
                $prioridadCategoria = 'ALTA';
            } elseif ($prioridadNumerica >= 150) {
                $prioridadCategoria = 'MEDIA';
            } else {
                $prioridadCategoria = 'BAJA';
            }

            $this->ticket->update([
                'prioridad' => $prioridadCategoria,
                'estado_id' => 3
            ]);
        }

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'Impacto',
            'detalle' =>  $this->ticket->asignado->name . ' Asigno el impacto ' . $this->ticket->impacto->nombre . ' lo que define la prioridad del ticket como ' . $prioridadCategoria,
        ]);

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'Cambio de estado',
            'detalle' =>  'El sistema cambio el estado del ticket a: En atención',
        ]);

        $this->ticket->usuario->notify(new CambioEstado($this->ticket));

        $this->loadTicket();
        $this->emit('editorVisible');
        $this->impacto = false;
    }

    public function addComment()
    {
        $this->validate(['newComment' => 'required|string', 'commentType' => 'required|integer|in:0,1,2']);

        // Crear el comentario y guardarlo en la variable $comentario
        $comentario = $this->ticket->comentarios()->create([
            'user_id' => auth()->id(),
            'comentario' => $this->newComment,
            'tipo' => $this->commentType,
        ]);

        // Asocia el archivo con el comentario recién creado si existe
        if ($this->newFile) {
            $this->addFile($comentario->id);
        }

        // Notificaciones basadas en el tipo de comentario
        if ($this->commentType == 0) {
            $this->ticket->usuario->notify(new NuevoComentario($comentario));
            if ($this->ticket->colaboradors) {
                foreach ($this->ticket->colaboradors as $colaborador) {
                    $colaborador->user->notify(new NuevoComentario($comentario));
                }
            }
        } elseif ($this->commentType == 1) {
            if ($this->ticket->colaboradors) {
                foreach ($this->ticket->colaboradors as $colaborador) {
                    $colaborador->user->notify(new NuevoComentarioPrivado($comentario));
                }
            }
        } else {
            $this->ticket->update(['estado_id' => 3]);

            $this->ticket->update([
                'estado_id' => 6
            ]);

            Historial::create([
                'ticket_id' => $this->ticket->id,
                'user_id' => Auth::id(),
                'accion' => 'Cambio de estado',
                'detalle' => 'El sistema cambio el estado del ticket a: Por aceptación',
            ]);

            $this->ticket->usuario->notify(new NuevoComentarioSolucion($comentario));
            if ($this->ticket->colaboradors) {
                foreach ($this->ticket->colaboradors as $colaborador) {
                    $colaborador->user->notify(new NuevoComentarioSolucion($comentario));
                }
            }
        }

        // Limpiar el estado después de agregar el comentario
        $this->newComment = '';
        $this->commentType = 0;
        $this->loadTicket('comentarios'); // Refresca los datos del ticket
        $this->emit('resetearEditor');
    }

    public function addFile($comentario_id = null)
    {
        $this->validate(['newFile' => 'required|file|max:10240']);
        $nombre_original = $this->newFile->getClientOriginalName();
        $nombre_sin_extension = pathinfo($nombre_original, PATHINFO_FILENAME);
        $extension = $this->newFile->getClientOriginalExtension();
        $nombre_db = Str::slug($nombre_sin_extension);
        $nombre_a_guardar = $nombre_db . '.' . $extension;
        $path = $this->newFile->storeAs('public/tickets', $nombre_a_guardar);
        // Guardar el archivo con la referencia al comentario (si existe) y al ticket
        $this->ticket->archivos()->create([
            'ruta' => $path,
            'comentario_id' => $comentario_id,
        ]);
        $this->newFile = null;
        $this->loadTicket($this->ticket->id); // Refresh ticket data
    }

    public function removeFile()
    {
        // Remover el archivo temporal
        $this->reset('newFile');
    }

    public function render()
    {
        $this->tipos_solicitud = TipoSolicitud::where('estado', 0)->get();
        $this->categorias = Categoria::where('solicitud_id', $this->solicitud_id)->get();
        $this->subcategorias = Subcategoria::where('categoria_id', $this->categoria_id)->get();
        $this->impactos = Impacto::all();
        $historial = Historial::where('ticket_id', $this->ticket_id)->orderBy('created_at', 'Asc')->get();
        return view('livewire.gestion.show', compact('historial'));
    }
}
