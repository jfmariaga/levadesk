<?php

namespace App\Http\Livewire\Gestion;

use App\Models\ANS;
use App\Models\Aplicaciones;
use App\Models\Aprobacion;
use App\Models\Cambios;
use App\Models\Ticket;
use App\Models\Categoria;
use App\Models\Colaborador;
use App\Models\Comentario;
use App\Models\Subcategoria;
use App\Models\Historial;
use App\Models\Impacto;
use App\Models\Recordatorio;
use App\Models\Tarea;
use App\Models\TicketHistorial;
use App\Models\TipoSolicitud;
use App\Models\User;
use App\Notifications\AnsCercaDeVencer;
use App\Notifications\AprobarSet;
use App\Notifications\CambioEstado;
use App\Notifications\EstadoTarea;
use App\Notifications\FlujoAcceso;
use App\Notifications\NotificacionAprobacion;
use App\Notifications\NuevaTarea;
use App\Notifications\NuevoColaborador;
use App\Notifications\NuevoComentario;
use App\Notifications\NuevoComentarioPrivado;
use App\Notifications\NuevoComentarioSolucion;
use App\Notifications\PruebasAccesos;
use App\Notifications\PruebasProductivo;
use App\Notifications\PruebasSet;
use App\Notifications\Reasignado;
use App\Notifications\TicketAsignado;
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
    public $asignar = false;
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
    public $newFileCambio;
    public $commentType = 0; // Valor por defecto es 'Público'
    public $usuarios = [];
    public $aprobadores = [];
    public $selectedUser;
    public $selectedNewAgente;
    public $selectedFuncional;
    public $selectedTi;
    public $tiempoRestante;
    public $titulo;
    public $descripcion;
    public $asignado_a;
    public $estado = 'pendiente';
    public $fecha_cumplimiento;
    public $aplicacion_id;
    public $aplicaciones;
    public $sociedad_id;
    public $agentes = [];
    public $tipoANS;

    protected $rules = [
        'categoria_id' => 'required',
        'subcategoria_id' => 'required',
        'solicitud_id' => 'required'
    ];

    protected $queryString = ['ticket_id'];

    public function mount()
    {
        $this->loadTicket();
        $this->usuarios = User::where('estado', 1)->where('id', '!=', Auth::id())->get();
        $this->aprobadores = User::where('estado', 1)->where('id', '!=', Auth::id())->where('aprobador_ti', true)->get();
        $this->agentes = User::role(['Admin', 'Agente'])->where('id', '!=', Auth::id())->get();
        $this->calcularTiempoRestante();
    }

    public function calcularTiempoRestante()
    {
        $ans = $this->ticket->ans;

        if ($this->ticket->prioridad === null) {
            // ANS inicial (el tiempo empieza a contar desde la creación del ticket)
            $tiempoInicio = $this->ticket->created_at;
            $tiempoPasado = now()->diffInSeconds($tiempoInicio);
            $this->tiempoRestante = $ans->t_asignacion_segundos - $tiempoPasado;
            $this->tipoANS = 'inicial';
        } elseif ($this->ticket->tiempo_inicio_aceptacion === null) {
            // Verifica si ya existe una marca de tiempo para el inicio de la resolución
            $tiempoInicioResolucion = $this->ticket->tiempo_inicio_resolucion;

            // Calcular el tiempo pasado desde el inicio de la resolución
            $tiempoPasado = now()->diffInSeconds($tiempoInicioResolucion);
            $this->tiempoRestante = $ans->t_resolucion_segundos - $tiempoPasado;
            $this->tipoANS = 'solución';
        } else {
            $tiempoInicioAceptacion = $this->ticket->tiempo_inicio_aceptacion;

            $tiempoPasado = now()->diffInSeconds($tiempoInicioAceptacion);
            $this->tiempoRestante = $ans->t_aceptacion_segundos - $tiempoPasado;
            $this->tipoANS = 'aceptación';
        }

        // Asegúrate de que el tiempo restante no sea negativo
        if ($this->tiempoRestante < 0) {
            $this->tiempoRestante = 0;
        }
    }


    public function formatTiempoRestante($segundos)
    {
        $dias = floor($segundos / 86400); // 86400 segundos en un día
        $horas = floor(($segundos % 86400) / 3600); // 3600 segundos en una hora
        $minutos = floor(($segundos % 3600) / 60); // 60 segundos en un minuto
        $segundos = $segundos % 60;

        $tiempoFormateado = '';
        if ($dias > 0) {
            $tiempoFormateado .= $dias . 'd ';
        }
        if ($horas > 0 || $dias > 0) {
            $tiempoFormateado .= $horas . 'h ';
        }
        if ($minutos > 0 || $horas > 0 || $dias > 0) {
            $tiempoFormateado .= $minutos . 'm ';
        }
        $tiempoFormateado .= $segundos . 's';

        return $tiempoFormateado;
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
            'estado' => 'pendiente',
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

    // Marcar tarea como "en_progreso"
    public function marcarEnProgreso($tareaId)
    {
        $tarea = Tarea::find($tareaId);

        if ($tarea->user_id == auth()->id()) {
            $tarea->marcarEnProgreso();
            $this->ticket->load('tareas.user');
            $this->emit('tareaEnProgreso');
        } else {
            $this->emit('showToast', ['type' => 'error', 'message' => 'Solo el responsable puede marcar la tarea como en progreso']);
        }
        $this->ticket->asignado->notify(new EstadoTarea($tarea, $this->ticket));
    }

    // Marcar tarea como "completado"
    public function marcarCompletada($tareaId)
    {
        $tarea = Tarea::find($tareaId);

        if ($tarea->user_id == auth()->id()) {
            $tarea->completar();
            $this->ticket->load('tareas.user');
            $this->emit('tareaCompletada');
        } else {
            $this->emit('showToast', ['type' => 'error', 'message' => 'Solo el responsable puede marcar la tarea como completada']);
        }

        $this->ticket->asignado->notify(new EstadoTarea($tarea, $this->ticket));
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

        // Verificar si el aprobador funcional tiene el rol de "Usuario" y actualizar a "Aprobador"
        $aprobadorFuncional = User::find($this->selectedFuncional);
        if ($aprobadorFuncional->hasRole('Usuario')) {
            $aprobadorFuncional->syncRoles(['Aprobador']); // Cambia el rol a "Aprobador"
        }

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

    public function flujoCambio()
    {
        $this->validate([
            'selectedFuncional' => 'required|exists:users,id',
            'selectedTi' => 'required|exists:users,id',
        ]);

        $cambio = Cambios::create([
            'ticket_id' => $this->ticket->id,
            'aprobador_funcional_id' => $this->selectedFuncional,
            'aprobador_ti_id' => $this->selectedTi,
            'aprobador_final_ti_id' => $this->selectedTi, // Mismo valor que aprobador_ti_id
            'aprobador_user_id' => $this->ticket->usuario->id,
            'estado' => 'pendiente',
        ]);

        $this->ticket->update([
            'estado_id' => 8
        ]);

        // Verificar si el aprobador funcional tiene el rol de "Usuario" y actualizar a "Aprobador"
        $aprobadorFuncional = User::find($this->selectedFuncional);
        if ($aprobadorFuncional->hasRole('Usuario')) {
            $aprobadorFuncional->syncRoles(['Aprobador']); // Cambia el rol a "Aprobador"
        }

        // Asocia el archivo con el comentario recién creado si existe
        if ($this->newFileCambio) {
            $this->addFileCambio($cambio->id);
        }

        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => Auth::id(),
            'accion' => 'Inicio de flujo',
            'detalle' => "Se inicio el flujo de aprobación de cambios.",
        ]);

        Historial::create([
            'ticket_id' => $this->ticket_id,
            'user_id' => Auth::id(),
            'accion' => 'cambio de estado ticket',
            'detalle' => "El nuevo estado del ticket es: En espera.",
        ]);

        // Notificar al líder funcional
        $cambio->aprobadorFuncionalCambio->notify(new NotificacionAprobacion($cambio, $this->ticket));
        $this->ticket->usuario->notify(new CambioEstado($this->ticket));


        $this->emit('showToast', ['type' => 'success', 'message' => 'Se inicio el flujo correctamente']);
        $this->selectedTi = "";
        $this->selectedFuncional = "";
        $this->loadTicket();
    }


    public function addFileCambio($cambio_id = null)
    {
        $this->validate(['newFileCambio' => 'required|file|max:10240']);
        $nombre_original = $this->newFileCambio->getClientOriginalName();
        $nombre_sin_extension = pathinfo($nombre_original, PATHINFO_FILENAME);
        $extension = $this->newFileCambio->getClientOriginalExtension();
        $nombre_db = Str::slug($nombre_sin_extension);
        $nombre_a_guardar = $nombre_db . '.' . $extension;
        $path = $this->newFileCambio->storeAs('public/tickets', $nombre_a_guardar);
        // Guardar el archivo con la referencia al comentario (si existe) y al ticket
        $this->ticket->archivos()->create([
            'ruta' => $path,
            'cambio_id' => $cambio_id,
        ]);
        $this->newFileCambio = null;
        $this->loadTicket($this->ticket->id); // Refresh ticket data
    }

    public function AsignarNewAgente()
    {
        $usuario_old = User::find($this->ticket->asignado_a);

        $usuario = User::find($this->selectedNewAgente);

        if ($this->ticket->asignado_a == $usuario->id) {
            $this->emit('agenteExiste');
            return;
        }

        $this->validate([
            'selectedNewAgente' => 'required|exists:users,id',
        ]);

        $this->ticket->asignado_a = $usuario->id;

        $this->ticket->save();

        $usuario->notify(new TicketAsignado($this->ticket));

        $usuario_logueado = Auth::user();
        $usuario_reasigno = $usuario_logueado->hasRole('Admin') ? $usuario_logueado->name : $usuario_old->name;

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'Ticket reasignado',
            'detalle' => $usuario_reasigno . ' Reasigno el ticket manualmente a  ' . $usuario->name,
        ]);
        $this->emit('showToast', ['type' => 'success', 'message' => "Ticket reasignado a {$usuario->name}."]);
        $this->loadTicket();
        $this->asignar = false;
        $this->emit('redirectAfterDelay');
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
        $this->sociedad_id = $this->ticket->sociedad_id;
        $this->categoria_nombre = $this->ticket->categoria->nombre;
        $this->subcategoria_nombre = $this->ticket->subcategoria->nombre;
        $this->solicitud_nombre = $this->ticket->tipoSolicitud->nombre;
        $this->aplicacion_id = $this->ticket->aplicacion_id ? $this->ticket->aplicacion_id : 'NULL';
        $this->impacto_id = $this->ticket->impacto_id ? $this->ticket->impacto_id : 'NULL';
        $this->prioridad = $this->ticket->prioridad ? $this->ticket->prioridad : 'NULL';

        $this->emit('selectImpacto', $this->impacto_id);
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

    // public function newAsignado(){
    //     $this->asignar = !$this->asignar;
    // }

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
        // Validar campos obligatorios
        $this->validate([
            'categoria_id' => 'required',
            'subcategoria_id' => 'required',
            'solicitud_id' => 'required'
        ]);

        // Obtener la nueva subcategoría seleccionada
        $subcategoria = Subcategoria::find($this->subcategoria_id);

        // Asegurarse de que $subcategoria no es null
        if (!$subcategoria) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'Subcategoría no encontrada']);
            return;
        }

        // Lógica especial para "SOPORTE DE APLICACIONES"
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

                    // Lógica de vacaciones
                    if ($usuario->en_vacaciones) {
                        $backupAgente = $usuario->backups()->first(); // Obtener el primer agente de respaldo
                        if ($backupAgente) {
                            $usuario = $backupAgente;
                        } else {
                            $this->emit('showToast', ['type' => 'error', 'message' => 'El usuario está de vacaciones y no tiene un agente de respaldo asignado.']);
                            return;
                        }
                    }

                    if (!$usuario) {
                        $this->emit('showToast', ['type' => 'error', 'message' => 'No hay usuarios disponibles en el grupo de la aplicación seleccionada']);
                        return;
                    }

                    // Asignar al usuario con menos tickets en el grupo de la aplicación
                    $this->ticket->update([
                        'categoria_id' => $this->categoria_id,
                        'subcategoria_id' => $this->subcategoria_id,
                        'tipo_solicitud_id' => $this->solicitud_id,
                        'asignado_a' => $usuario->id,  // Asignar al nuevo usuario
                        'grupo_id' => $grupo->id,       // Actualizar el grupo
                    ]);

                    // Registrar en el historial la reasignación
                    Historial::create([
                        'ticket_id' => $this->ticket_id,
                        'user_id' => Auth::id(),
                        'accion' => 'Recategorizado y Reasignado',
                        'detalle' => "Ticket recategorizado y reasignado a {$usuario->name} en el grupo {$grupo->nombre} relacionado con la aplicación seleccionada.",
                    ]);

                    $this->emit('showToast', ['type' => 'success', 'message' => "Ticket reasignado a {$usuario->name} en el grupo de la aplicación seleccionada."]);
                    $this->emit('redirectAfterDelay');
                    return; // Terminamos aquí, ya que la lógica especial está resuelta.
                } else {
                    $this->emit('showToast', ['type' => 'error', 'message' => 'No hay grupo asociado a la aplicación seleccionada']);
                    return;
                }
            } else {
                $this->emit('showToast', ['type' => 'error', 'message' => 'No hay grupo o usuarios asignados a la aplicación seleccionada.']);
                return;
            }
        }

        // Lógica general para las subcategorías que no sean "SOPORTE DE APLICACIONES"
        // Obtener el grupo relacionado con la subcategoría, categoría y sociedad seleccionadas a través de la relación en el modelo Subcategoria
        $grupo = $subcategoria->gruposPorSociedad($this->sociedad_id, $this->categoria_id)->first();

        // Asegurarse de que $grupo no es null
        if (!$grupo) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'No hay grupo de atención asignado para esta combinación de sociedad, subcategoría y categoría.']);
            return;
        }

        // Obtener el usuario del grupo con menos tickets asignados
        $usuario = $grupo->usuarios()->withCount('ticketsAsignados')->orderBy('tickets_asignados_count', 'asc')->first();

        // Verificar si el usuario actual del ticket tiene un grupo asignado
        $usuarioAsignado = User::find($this->ticket->asignado_a);

        // Lógica de vacaciones
        if ($usuario->en_vacaciones) {
            $backupAgente = $usuario->backups()->first(); // Obtener el primer agente de respaldo
            if ($backupAgente) {
                $usuario = $backupAgente;
            } else {
                $this->emit('showToast', ['type' => 'error', 'message' => 'El usuario asignado está de vacaciones y no tiene un agente de respaldo.']);
                return;
            }
        }

        // Verificar si el grupo de la nueva subcategoría es diferente al grupo actual
        if ($usuarioAsignado && $usuarioAsignado->grupo_id !== $grupo->id) {
            if (!$usuario) {
                $this->emit('showToast', ['type' => 'error', 'message' => 'No hay usuarios disponibles en el nuevo grupo']);
                return;
            }

            // Emitir un evento para notificar sobre la reasignación del ticket
            $this->emit('showToast', ['type' => 'warning', 'message' => "El ticket será reasignado de {$usuarioAsignado->name} a {$usuario->name}."]);

            // Actualizar el ticket con la nueva información
            $this->ticket->update([
                'categoria_id' => $this->categoria_id,
                'subcategoria_id' => $this->subcategoria_id,
                'tipo_solicitud_id' => $this->solicitud_id,
                'asignado_a' => $usuario->id,  // Reasignar al nuevo usuario
                'grupo_id' => $grupo->id,       // Actualizar el grupo
            ]);

            // Registrar en el historial la recategorización y reasignación
            Historial::create([
                'ticket_id' => $this->ticket_id,
                'user_id' => Auth::id(),
                'accion' => 'Recategorizado y Reasignado',
                'detalle' => "Ticket recategorizado y reasignado de {$usuarioAsignado->name} a {$usuario->name} en el grupo {$grupo->nombre}.",
            ]);
            $this->emit('redirectAfterDelay');
        } else {
            // El grupo no cambió, solo actualizar la categoría y subcategoría
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
            $this->emit('redirectAfterDelay');
        }

        // Notificar al nuevo usuario
        $usuario->notify(new Reasignado($usuarioAsignado, $this->ticket));

        // Recargar los datos del ticket para reflejar los cambios
        $this->loadTicket();
    }


    public function actualizarImpacto()
    {
        $this->validate([
            'impacto_id' => 'required',
        ]);

        $ansCumplido = $this->tiempoRestante > 0;

        $this->ticket->update([
            'impacto_id' => $this->impacto_id
        ]);

        $this->ticket->load('impacto');


        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'Impacto',
            'detalle' => 'Se actualizó el impacto y el ANS Inicial ' . ($ansCumplido ? 'se cumplió' : 'no se cumplió'),
        ]);

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

            // Obtener el nuevo ANS basado en la prioridad
            $nuevoAns = ANS::where('solicitud_id', $this->ticket->tipo_solicitud_id)
                ->where('nivel', $prioridadCategoria)
                ->first();

            // Verificar si el tiempo de resolución ya ha sido iniciado
            if ($this->ticket->tiempo_inicio_resolucion === null) {
                // Si no ha sido iniciado, lo inicializamos ahora
                $this->ticket->update([
                    'tiempo_inicio_resolucion' => now()
                ]);
            }

            // Actualizar el ticket con la nueva prioridad y ANS
            $this->ticket->update([
                'prioridad' => $prioridadCategoria,
                'estado_id' => 3,
                'ans_id' => $nuevoAns ? $nuevoAns->id : $this->ticket->ans_id,  // Actualizar el ANS
                'ans_inicial_vencido' => $ansCumplido ? false : true,  // Actualizar el ANS
            ]);

            // Recalcular el tiempo restante en base al nuevo ANS
            $this->calcularTiempoRestante();
        }

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'Impacto',
            'detalle' =>  Auth::user()->name . ' Asigno el impacto ' . $this->ticket->impacto->nombre . ' lo que define la prioridad del ticket como ' . $prioridadCategoria,
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
        $this->validate(['newComment' => 'required|string', 'commentType' => 'required|integer|in:0,1,2,3,4,5,6,7']);

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
        // if ($this->commentType == 0) {
        //     $this->ticket->usuario->notify(new NuevoComentario($comentario));
        //     if ($this->ticket->colaboradors) {
        //         foreach ($this->ticket->colaboradors as $colaborador) {
        //             $colaborador->user->notify(new NuevoComentario($comentario));
        //         }
        //     }
        // }
        if($this->commentType == 1) {
            if ($this->ticket->colaboradors) {
                foreach ($this->ticket->colaboradors as $colaborador) {
                    $colaborador->user->notify(new NuevoComentarioPrivado($comentario));
                }
            }
        } elseif ($this->commentType == 2) {

            $ansCumplido = $this->tiempoRestante > 0;


            $this->ticket->update([
                'estado_id' => 6,
                'ans_vencido' => $ansCumplido ? 0 : 1,
                'notificadoSolucion' => true,
                'tiempo_inicio_aceptacion' => now()

            ]);

            Historial::create([
                'ticket_id' => $this->ticket->id,
                'user_id' => Auth::id(),
                'accion' => 'Cambio de estado',
                'detalle' => 'El sistema cambio el estado del ticket a: Por aceptación y el ANS de solución ' . ($ansCumplido ? 'se cumplió' : 'no se cumplió'),
            ]);

            $this->ticket->usuario->notify(new NuevoComentarioSolucion($comentario));
            // if ($this->ticket->colaboradors) {
            //     foreach ($this->ticket->colaboradors as $colaborador) {
            //         $colaborador->user->notify(new NuevoComentarioSolucion($comentario));
            //     }
            // }
        } elseif ($this->commentType == 5) {
            $this->ticket->update([
                'estado_id' => 11
            ]);

            Historial::create([
                'ticket_id' => $this->ticket->id,
                'user_id' => Auth::id(),
                'accion' => 'Cambio de estado',
                'detalle' => 'El sistema cambio el estado del ticket a: Pruebas de SET',
            ]);

            $this->ticket->usuario->notify(new PruebasSet($comentario));
            // if ($this->ticket->colaboradors) {
            //     foreach ($this->ticket->colaboradors as $colaborador) {
            //         $colaborador->user->notify(new NuevoComentario($comentario));
            //     }
            // }
        } elseif ($this->commentType == 6) {
            $this->ticket->update([
                'estado_id' => 12
            ]);

            Historial::create([
                'ticket_id' => $this->ticket->id,
                'user_id' => Auth::id(),
                'accion' => 'Cambio de estado',
                'detalle' =>  Auth::user()->name . ' Cambio el estado del ticket a: Pruebas en ambiente productivo',
            ]);

            $this->ticket->usuario->notify(new PruebasProductivo($comentario));
            $this->ticket->asignado->notify(new PruebasProductivo($comentario));
        } else {
            $this->ticket->update([
                'estado_id' => 16
            ]);

            Historial::create([
                'ticket_id' => $this->ticket->id,
                'user_id' => Auth::id(),
                'accion' => 'Cambio de estado',
                'detalle' =>  Auth::user()->name . ' Cambio el estado del ticket a: Prueba de acceso',
            ]);

            $this->ticket->usuario->notify(new PruebasAccesos($comentario));
            $this->ticket->asignado->notify(new PruebasAccesos($comentario));
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

    public function removeFileCambio()
    {
        // Remover el archivo temporal
        $this->reset('newFileCambio');
    }

    public function consultoria()
    {
        $this->ticket->update([
            'escalar' => true,
            'estado_id' => 9
        ]);

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'Escalado',
            'detalle' =>  Auth::user()->name . ' Cambió el estado del ticket a: Escalado a consultoría',
        ]);

        TicketHistorial::create([
            'ticket_id' => $this->ticket->id,
            'estado_id' => 9,
            'fecha_cambio' => now(),
        ]);

        $this->ticket->usuario->notify(new CambioEstado($this->ticket));

        $this->emit('showToast', ['type' => 'success', 'message' => 'Cambio de estado a: Escalado a consultoría']);
        $this->loadTicket($this->ticket->id);
    }

    public function consultoriaCambio()
    {
        if ($this->ticket->cambio && $this->ticket->cambio->check_aprobado_ti == true) {
            $this->ticket->update([
                'estado_id' => 14
            ]);

            Historial::create([
                'ticket_id' => $this->ticket->id,
                'user_id' => Auth::id(),
                'accion' => 'FinEscalado',
                'detalle' =>  Auth::user()->name . ' Cambió el estado del ticket a: SET Aprobado',
            ]);

            $this->ticket->usuario->notify(new CambioEstado($this->ticket));

            $this->emit('showToast', ['type' => 'success', 'message' => 'Ahora puedes dar gestión a este ticket']);
        } else {
            $this->ticket->update([
                'estado_id' => 3
            ]);

            Historial::create([
                'ticket_id' => $this->ticket->id,
                'user_id' => Auth::id(),
                'accion' => 'FinEscalado',
                'detalle' =>  Auth::user()->name . ' Cambió el estado del ticket a: En atención',
            ]);

            $this->ticket->usuario->notify(new CambioEstado($this->ticket));

            $this->emit('showToast', ['type' => 'success', 'message' => 'Cambio de estado a: En atención']);
        }



        $this->loadTicket($this->ticket->id);
    }

    public function mandarParaAprobacion($id)
    {
        $comentario = Comentario::find($id);
        // dd($comentario);
        $comentario->update([
            'check_comentario' => true
        ]);

        $this->ticket->update([
            'estado_id' => 10
        ]);

        $this->ticket->cambio->update([
            'check_aprobado' => true,
        ]);


        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'set ',
            'detalle' => 'Esperando la aprobación del set de pruebas',
        ]);

        $this->ticket->cambio->aprobadorTiCambio->notify(new AprobarSet($this->ticket));
        $this->emit('showToast', ['type' => 'success', 'message' => 'Enviado para aprobación']);
        $this->loadTicket();
    }

    public function render()
    {
        $this->tipos_solicitud = TipoSolicitud::where('estado', 0)->get();
        $this->categorias = Categoria::where('solicitud_id', $this->solicitud_id)->where('estado', 0)->get();
        $this->subcategorias = Subcategoria::where('categoria_id', $this->categoria_id)->where('estado', 0)->get();
        $this->aplicaciones = Aplicaciones::where('sociedad_id', $this->sociedad_id)->where('estado', 0)->get();
        $this->impactos = Impacto::all();
        $historial = Historial::where('ticket_id', $this->ticket_id)->orderBy('created_at', 'Asc')->get();
        return view('livewire.gestion.show', compact('historial'));
    }
}
