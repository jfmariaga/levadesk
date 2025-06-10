<?php

namespace App\Http\Livewire\Aprobacion;

use App\Models\Comentario;
use App\Models\Historial;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AprobarProductivo;
use App\Notifications\CambioEstado;
use App\Notifications\FinFlujo;
use App\Notifications\FinFlujoCambio;
use App\Notifications\NoAprobarProductivo;
use App\Notifications\NotificacionAprobacion;
use App\Notifications\NotificacionRechazo;
use App\Notifications\NotificacionRechazoCambio;
use App\Notifications\NuevoComentario;
use App\Notifications\RechazoFlujo;
use App\Notifications\RechazoFlujoCambio;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use \Illuminate\Support\Facades\DB;



class AprobarCambios extends Component
{
    use WithFileUploads;
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
    public $ticket;
    public $urgencia;
    public $usuarioId;
    public $newComment;
    public $newFile;
    public $usuarios;
    public $modalId;
    public $prioridad;
    public $showTimelineTicket = false;
    public $impacto;
    public $ticket_id;
    public $idcomentario;
    public $comentariosRechazo;
    public $estado_aprobacion;
    public $estado_aprobacion_old;
    public $aprobador_funcional_id;
    public $aprobador_ti_id;
    public $flowData;


    protected $queryString = ['ticket_id'];

    public function mount()
    {
        $this->verTicket();
        $this->loadFlow();
        $this->usuarios = User::all();

    }

    public function toggleTimelineTicket()
    {
        $this->showTimelineTicket = !$this->showTimelineTicket;
    }
    
    public function verTicket()
    {
        $this->ticket = Ticket::with(['categoria', 'subcategoria'])->find($this->ticket_id);
        if (!$this->ticket) {
            abort(404, 'Ticket no encontrado.');
        }
        $this->nomenclatura = $this->ticket->nomenclatura;
        $this->sociedad_id = $this->ticket->sociedad_id;
        $this->tipo_solicitud_id = $this->ticket->tipo_solicitud_id;
        $this->categoria_id = $this->ticket->categoria_id;
        $this->subcategoria_id = $this->ticket->subcategoria_id;
        $this->titulo = $this->ticket->titulo;
        $this->descripcion = $this->ticket->descripcion ? $this->ticket->descripcion : '';
        $this->estado_id = $this->ticket->estado_id;
        $this->prioridad = $this->ticket->prioridad_id;
        $this->impacto = $this->ticket->impacto_id;
        $this->estado_aprobacion_old = $this->ticket->cambio->estado;
        $this->aprobador_funcional_id = $this->ticket->cambio->aprobador_funcional_id;
        $this->aprobador_ti_id = $this->ticket->cambio->aprobador_ti_id;
    }


    public function aprobarFuncionalCambio()
    {
        if ($this->estado_aprobacion === 'rechazado_funcional' && empty($this->comentariosRechazo)) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'El comentario es obligatorio para rechazar el acceso.']);
            return;
        }

        // Guardar los cambios en la aprobación
        $aprobacionActualizada = $this->ticket->cambio->update([
            'estado' => $this->estado_aprobacion,
            'comentarios_funcional' => $this->comentariosRechazo,
        ]);

        if ($aprobacionActualizada) {
            Historial::create([
                'ticket_id' => $this->ticket_id,
                'user_id' => Auth::id(),
                'accion' => 'Aprobación funcional',
                'detalle' => $this->estado_aprobacion === 'aprobado_funcional' ? 'Aprobado  por el líder funcional ' . Auth::user()->name : 'Rechazado por el líder funcional ' . Auth::user()->name,
            ]);

            if ($this->estado_aprobacion === 'aprobado_funcional') {

                Historial::create([
                    'ticket_id' => $this->ticket_id,
                    'user_id' => Auth::id(),
                    'accion' => 'Aprobación pendiente',
                    'detalle' => 'Aprobación pendiente por TI',
                ]);

                // Verificar si el aprobador funcional tiene el rol de "Usuario" y actualizar a "Aprobador"
                $aprobadorTI = User::find($this->ticket->cambio->aprobadorTiCambio->id);
                if ($aprobadorTI->hasRole('Usuario')) {
                    $aprobadorTI->syncRoles(['Aprobador']); // Cambia el rol a "Aprobador"
                }
                $this->ticket->cambio->aprobadorTiCambio->notify(new NotificacionAprobacion($this->ticket->cambio, $this->ticket));
            } else {
                $this->ticket->update(['estado_id' => 5]); // Estado cerrado si es rechazado funcionalmente
                Comentario::create([
                    'ticket_id' => $this->ticket->id,
                    'user_id' => 16,
                    'comentario' => 'Debido a que el líder funcional no aprobó la solicitud de cambios se rechaza el ticket con una calificación de 5/5⭐',
                    'calificacion' => 5,
                ]);

                $this->ticket->usuario->notify(new RechazoFlujoCambio($this->ticket));
                $this->ticket->asignado->notify(new RechazoFlujoCambio($this->ticket));
            }
            $this->emit('actualizarNotificaciones');

            $this->emit('showToast', ['type' => 'success', 'message' => 'La aprobación funcional ha sido ' . $this->estado_aprobacion . '.']);
        } else {
            $this->emit('showToast', ['type' => 'error', 'message' => 'No se pudo actualizar la aprobación.']);
        }
        $this->verTicket();
        $this->updateFlow();
    }

    public function aprobarTiCambio()
    {
        if ($this->estado_aprobacion === 'rechazado_ti' && empty($this->comentariosRechazo)) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'El comentario es obligatorio para rechazar el flujo.']);
            return;
        }

        // Guardar los cambios en la aprobación
        $aprobacionActualizada = $this->ticket->cambio->update([
            'estado' => $this->estado_aprobacion,
            'comentarios_ti' => $this->comentariosRechazo,
        ]);

        if ($aprobacionActualizada) {
            Historial::create([
                'ticket_id' => $this->ticket_id,
                'user_id' => Auth::id(),
                'accion' => 'Aprobación TI',
                'detalle' => $this->estado_aprobacion === 'aprobado_ti' ? 'Aprobación TI realizada por ' . Auth::user()->name : 'El aprobador TI ' . Auth::user()->name . ' No aprobó, motivo: ' . $this->comentariosRechazo,
            ]);

            if ($this->estado_aprobacion === 'aprobado_ti') {
                // Notificar al agente TI para validación con el usuario que generó el ticket

                $this->ticket->cambio->update([
                    'estado' => 'aprobado',
                ]);

                $this->ticket->update([
                    'estado_id' => 3
                ]);

                Historial::create([
                    'ticket_id' => $this->ticket_id,
                    'user_id' => Auth::id(),
                    'accion' => 'Cambio de estado',
                    'detalle' => 'El ticket cambio de estado En espera a En atención',
                ]);
                $this->ticket->usuario->notify(new CambioEstado($this->ticket));
                $this->ticket->asignado->notify(new FinFlujoCambio($this->ticket));
            } else {
                Comentario::create([
                    'ticket_id'  => $this->ticket->id,
                    'user_id'    => Auth::id(),
                    'comentario' => 'El aprobador TI. No aprobó, motivo: ' . $this->comentariosRechazo,
                    'tipo'       => 0,
                ]);

                $this->ticket->cambio->update([
                    'estado' => 'pendiente',
                ]);

                $this->ticket->cambio->aprobadorFuncionalCambio->notify(new NotificacionRechazoCambio($this->ticket));
            }
            $this->emit('actualizarNotificaciones');


            $this->emit('showToast', ['type' => 'success', 'message' => 'La aprobación TI ha sido ' . $this->estado_aprobacion . '.']);
        } else {
            $this->emit('showToast', ['type' => 'error', 'message' => 'No se pudo actualizar la aprobación.']);
        }

        $this->verTicket();
        $this->updateFlow();
    }

    public function addComment()
    {
        $this->validate(['newComment' => 'required|string']);

        // Crear el comentario y guardarlo en la variable $comentario
        $comentario = $this->ticket->comentarios()->create([
            'user_id' => auth()->id(),
            'comentario' => $this->newComment,
            'tipo' => 0,
        ]);

        // Asocia el archivo con el comentario recién creado si existe
        if ($this->newFile) {
            $this->addFile($comentario->id);
        }

        $this->ticket->asignado->notify(new NuevoComentario($comentario));

        if ($this->ticket->cambio->aprobadorFuncionalCambio) {
            $this->ticket->cambio->aprobadorFuncionalCambio->notify(new NuevoComentario($comentario));
        }

        if ($this->ticket->cambio->aprobadorTiCambio) {
            $this->ticket->cambio->aprobadorTiCambio->notify(new NuevoComentario($comentario));
        }

        // Limpiar el estado después de agregar el comentario
        $this->newComment = '';
        $this->verTicket('comentarios'); // Refresca los datos del ticket
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
        $this->verTicket($this->ticket->id); // Refresh ticket data
    }
    public function removeFile()
    {
        // Remover el archivo temporal
        $this->reset('newFile');
    }

    public function aprobarSet()
    {
        $this->ticket->update([
            'estado_id' => 14
        ]);

        $this->ticket->cambio->update([
            'check_aprobado_ti' => true,
        ]);

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'set aprobado',
            'detalle' => Auth::user()->name . ' Aprobó el paso a producción',
        ]);

        $this->ticket->asignado->notify(new AprobarProductivo($this->ticket));
        $this->emit('showToast', ['type' => 'success', 'message' => 'Se aprobó el set de pruebas']);
        $this->emit('actualizarNotificaciones');
        $this->verTicket();
        $this->updateFlow();
    }

    public function rechazarSet($id)
    {

        $comentario =  Comentario::find($id);
        $comentario->update([
            'check_comentario' => false,
        ]);

        $this->ticket->update([
            'estado_id' => 10
        ]);

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'set ',
            'detalle' => Auth::user()->name . ' No aprobó el paso a producción',
        ]);

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'cambio estado',
            'detalle' => 'Nuevo estado del ticket es: En atención',
        ]);

        $this->ticket->asignado->notify(new NoAprobarProductivo($this->ticket));
        $this->verTicket();
        $this->updateFlow();
    }

    public function loadFlow()
    {
        // Refrescar el modelo del ticket para obtener los datos más recientes
        $this->ticket = $this->ticket->fresh();

        $initialState = [
            'estado' => 'ASIGNADO',
            'visitado' => true
        ];
        // Definir las transiciones generales
        $transitions = [
            1 => ['RECATEGORIZAR', 'REASIGNAR', 'ASIGNAR IMPACTO'],
            2 => ['EN ATENCIÓN'],
            3 => ['REQUIERE CAMBIO', 'ESCALADO A CONSULTORÍA', 'PENDIENTE POR VALIDACIÓN DE USUARIO'],
            4 => [],
            5 => ['EN ESPERA', 'RECHAZADO', 'SET APROBADO'],
            6 => ['REABIERTO', 'FINALIZADO'],
            7 => ['REQUIERE CAMBIO', 'ESCALADO A CONSULTORÍA', 'SOLUCIÓN'],
            8 => ['EN PRUEBAS DE USUARIO', 'PRUEBAS AMBIENTE PRODUCTIVO'],
            9 => ['EN ATENCIÓN'],
            10 => function ($ticket) {
                $tarea = $ticket->tareas()->latest()->first(); // 👈 la última tarea
                // dd($tarea);
                if ($tarea->editar == true) {
                    return ['EDITAR TAREA'];
                } else {
                    return ['EN ESPERA DE APROBACIÓN PASO A PRODUCTIVO (Líder TI)'];
                }
            },            
            // 11 => [' 1. EN ESPERAS DE EVIDENCIAS SET DE PRUEBAS', '2. ADJUNTAR DOCUMENTACIÓN TÉCNICA', '3. PEDIR APROBACIÓN TRANSPORTE A PRODUCTIVO'],
            11 => function ($ticket) {
                // Verificar primero si existe el cambio
                if (!$ticket->cambio) {
                    return ['1. EN ESPERAS DE EVIDENCIAS SET DE PRUEBAS'];
                }

                // Lógica condicional mejorada
                if ($ticket->cambio->evidencia == false) {
                    return ['1. EN ESPERAS DE EVIDENCIAS SET DE PRUEBAS'];
                } elseif ($ticket->cambio->doc_tecnico == false) {
                    return ['2. ADJUNTAR DOCUMENTACIÓN TÉCNICA'];
                } else {
                    return ['3. PEDIR APROBACIÓN TRANSPORTE A PRODUCTIVO'];
                }
            },
            12 => ['EN ESPERAS DE EVIDENCIAS AMBIENTE PRODUCTIVO'],
            13 => ['1. AGREGAR COLABORADOR', '2. ASIGNAR TAREA DE TRANSPORTE', '3. ESPERAR APROBACION POR LIDER TI'],
            14 => ['1. APLICAR TRANSPORTE (colaborador)'],
            15 => ['CONFIGURAR ACCESOS'],
            16 => [' 1. EN ESPERAS DE EVIDENCIAS', '2. FINALIZAR TICKET'],
            17 => ['MARCAR COMO SOLUCIÓN'],
            18 => ['VALIDAR FALLAS EN PRODUCCIÓN', 'CONFIGURAR NUEVAMENTE EL SET DE PRUEBAS'],
        ];

        // Definir las transiciones específicas de los cambios
        $changeTransitions = [
            'pendiente' => ['EN ESPERA DE APROBACIÓN FUNCIONAL'],
            'rechazo_funcional' => ['RECHAZADO'],
            'aprobado_funcional' => ['POR APROBAR LÍDER TI'],
            'rechazo_ti' => ['ESPERA DE APROBACIÓN FUNCIONAL'],
            'aprobado' => ['ESCARLAR A CONSULTORIA', 'CONFIGURACIÓN DE SET DE PRUEBAS'], // Este es el paso intermedio
        ];

        // Definir las transiciones específicas para aprobaciones
        $approvalTransitions = [
            'pendiente' => ['EN ESPERA DE APROBACIÓN FUNCIONAL'],
            'rechazo_funcional' => ['RECHAZADO'],
            'aprobado_funcional' => ['POR APROBAR LÍDER TI'],
            'rechazo_ti' => ['ESPERA DE APROBACIÓN FUNCIONAL'],
            'aprobado' => ['CONFIGURACIÓN DE ACCESOS'], // Este es el paso intermedio
        ];

        // Obtener el estado actual del ticket
        $currentState = $this->ticket->estado->nombre;

        // Cargar los estados visitados desde la base de datos
        $ticketEstados = DB::table('ticket_estados')
            ->where('ticket_id', $this->ticket->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $visitedStates = [$initialState];

        $visitedStates = [];
        foreach ($ticketEstados as $estado) {
            $visitedStates[] = [
                'estado' => DB::table('estados')->where('id', $estado->estado_id)->value('nombre'),
                'visitado' => true,
            ];
        }

        // Verificar si hay un flujo de cambios asociado al ticket
        $nextStates = [];
        $cambio = $this->ticket->cambio;
        $aprobacion = $this->ticket->aprobacion; // Nueva lógica para aprobaciones

        if ($aprobacion) {
            if ($aprobacion->estado === 'aprobado') {
                // Si la aprobación está completa, sigue el flujo normal
                $nextStates = $transitions[$this->ticket->estado_id] ?? [];
            } else {
                // Si la aprobación no está completa, sigue las transiciones de la aprobación
                $nextStates = $approvalTransitions[$aprobacion->estado] ?? [];
            }
        } elseif ($cambio) {
            if ($cambio->estado === 'aprobado') {
                // Si el cambio está aprobado y el estado actual no es "EN ATENCIÓN", sigue el flujo del ticket
                if ($this->ticket->estado_id !== 3) { // 2 corresponde a "EN ATENCIÓN"
                    $nextStates = $transitions[$this->ticket->estado_id] ?? [];
                } else {
                    // Si el estado es "EN ATENCIÓN", sigue las transiciones del cambio
                    $nextStates = $changeTransitions[$cambio->estado] ?? [];
                }
            } else {
                // Si el cambio no está aprobado, sigue las transiciones del cambio
                $nextStates = $changeTransitions[$cambio->estado] ?? [];
            }
        } else {
            // Si no hay un cambio o aprobación asociado, sigue las transiciones generales del ticket
            $nextStates = $transitions[$this->ticket->estado_id] ?? [];
        }

        // Construir la estructura de datos para el frontend
        // $this->flowData = [
        //     'currentState' => $currentState,
        //     'nextStates' => $nextStates,
        //     'flowStates' => $visitedStates,
        // ];
        // dd($this->flowData);
        // Cambia esta parte al final del método:
        $this->flowData = [
            'currentState' => $currentState,
            'nextStates' => is_callable($nextStates) ? $nextStates($this->ticket) : $nextStates,
            'flowStates' => $visitedStates
        ];
    }


    public function updateFlow()
    {
        $this->loadFlow();
        $this->emit('updateFlowDiagram', $this->flowData);
    }

    public function render()
    {
        $historial = Historial::where('ticket_id', $this->ticket_id)->orderBy('created_at', 'Asc')->get();
        $this->updateFlow();
        return view('livewire.aprobacion.aprobar-cambios', compact('historial'));
    }
}
