<?php

namespace App\Http\Livewire\Ticket;

use App\Models\Comentario;
use App\Models\Historial;
use App\Models\Ticket;
use App\Models\TicketHistorial;
use App\Models\User;
use App\Notifications\Finalizado;
use App\Notifications\NoFuncionaProductivo;
use App\Notifications\NoSolucion;
use App\Notifications\NuevoComentario;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use \Illuminate\Support\Facades\DB;


class VerTicket extends Component
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
    public $commentType = 9;
    public $flowData;

    protected $queryString = ['ticket_id'];

    public function mount()
    {
        $this->verTicket();
        $this->loadFlow();
        $this->usuarios = User::all();
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
            7 => ['REQUIERE CAMBIO', 'ESCALADO A CONSULTORÍA', 'SOLUCIÓN', 'GESTIÓN DE ACCESO'],
            8 => ['EN PRUEBAS DE USUARIO', 'PRUEBAS AMBIENTE PRODUCTIVO'],
            9 => ['EN ATENCIÓN'],
            10 => ['EN ESPERA DE APROBACIÓN PASO A PRODUCTIVO (Líder TI)'],
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

        $this->loadFlow();

    }
    

    public function aceptarSolucion($comentarioId)
    {
        $this->emit('mostrarSistemaCalificacion', $comentarioId);
    }

    public function guardarCalificacion($comentarioId, $rating, $comentario)
    {
        $comentarioModel = Comentario::find($comentarioId);
        $comentarioModel->calificacion = $rating;
        $comentarioModel->comentario_calificacion = $comentario;
        $comentarioModel->save();
        // dd($comentarioModel);

        $this->ticket->estado_id = 4;  // Cambia el estado a "Cerrado" o el estado final que corresponda
        $this->ticket->save();

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'accion' => 'Calificación de solución',
            'detalle' => "El usuario calificó la solución con $rating estrellas.",
        ]);

        $this->ticket->asignado->notify(new Finalizado($comentarioModel));
        // if ($this->ticket->colaboradors) {
        //     foreach ($this->ticket->colaboradors as $colaborador) {
        //         $colaborador->user->notify(new Finalizado($comentarioModel));
        //     }
        // }

        $this->emit('showToast', ['type' => 'success', 'message' => 'Gracias por tu calificación.']);
        $this->verTicket();
    }


    public function rechazarSolucion($comentarioId)
    {
        $this->idcomentario = $comentarioId;
        // Emitir un evento para mostrar el SweetAlert de confirmación
        $this->emit('confirmarReapertura', ['comentarioId' => $comentarioId]);
    }

    public function reabrirTicket()
    {
        // dd($comentarioId);
        $comentario = Comentario::find($this->idcomentario);
        $comentario->tipo = 3;
        $comentario->save();

        // Lógica para reabrir el ticket
        $this->ticket->update([
            'estado_id' => 7,
            'tiempo_restante' => $this->ticket->ans->t_resolucion_segundos,
            'tiempo_inicio_aceptacion' => NULL,
        ]);
        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'accion' => 'No aceptación',
            'detalle' => 'El usuario no aceptó la solución.',
        ]);

        TicketHistorial::create([
            'ticket_id' => $this->ticket->id,
            'estado_id' => 7,
            'fecha_cambio' => now(),
        ]);

        $this->ticket->asignado->notify(new NoSolucion($comentario));
        if ($this->ticket->colaboradors) {
            foreach ($this->ticket->colaboradors as $colaborador) {
                $colaborador->user->notify(new NoSolucion($comentario));
            }
        }
        $this->emit('showToast', ['type' => 'success', 'message' => 'Ticket reabierto con éxito']);

        $this->verTicket();
    }

    public function addComment()
    {
        //verifica que el usuario adjunte la evidencia en el set de pruebas
        if ($this->ticket->estado_id == 11 && $this->ticket->cambio && $this->newFile == null) {
            $this->emit('faltaEvidencia');
            return;
        }
        
        $this->validate(['newComment' => 'required|string']);
        // Crear el comentario y guardarlo en la variable $comentario
        $comentario = $this->ticket->comentarios()->create([
            'user_id' => auth()->id(),
            'comentario' => $this->newComment,
            'tipo' => 0,
        ]);

        // if ($this->ticket->estado_id == 12) {
        //     if ($this->commentType == 9) {
        //         Historial::create([
        //             'ticket_id' => $this->ticket->id,
        //             'user_id' => auth()->id(),
        //             'accion' => 'Aceptación',
        //             'detalle' => 'El usuario indico que están funcionando los cambios en producción',
        //         ]);

        //         $this->ticket->update([
        //             'estado_id' => 17,
        //         ]);
        //     } else {
        //         Historial::create([
        //             'ticket_id' => $this->ticket->id,
        //             'user_id' => auth()->id(),
        //             'accion' => 'No aceptación',
        //             'detalle' => 'El usuario indico que NO están funcionando los cambios en producción.',
        //         ]);

        //         $this->ticket->update([
        //             'estado_id' => 14,
        //         ]);

        //         $ultimaTarea = $this->ticket->tareas()->latest()->first();

        //         if ($ultimaTarea && $ultimaTarea->user) {
        //             $ultimaTarea->user->notify(new NoFuncionaProductivo($this->ticket));
        //         }                

        //     }
        // }

        if ($this->ticket->estado_id == 12) {
            if ($this->commentType == 9) {
                Historial::create([
                    'ticket_id' => $this->ticket->id,
                    'user_id' => auth()->id(),
                    'accion' => 'Aceptación',
                    'detalle' => 'El usuario indico que están funcionando los cambios en producción',
                ]);

                $this->ticket->update([
                    'estado_id' => 17,
                ]);
            } else {
                Historial::create([
                    'ticket_id' => $this->ticket->id,
                    'user_id' => auth()->id(),
                    'accion' => 'No aceptación',
                    'detalle' => 'El usuario indico que NO están funcionando los cambios en producción.',
                ]);

                $this->ticket->update([
                    'estado_id' => 18,
                ]);

                $this->ticket->cambio->update([
                    'check_aprobado_ti' => false,
                    'evidencia'         => false,
                    'doc_tecnico'       => false,
                ]);

                foreach ($this->ticket->comentarios as $comen) {
                    if ($comen->check_comentario == true) {
                        $comen->update([
                            'check_comentario' => false,
                            'check_aprobado' => false,
                        ]);
                    }
                }
            }
        }


        if ($this->ticket->estado_id == 11) {
            if ($this->commentType == 9) {
                Historial::create([
                    'ticket_id' => $this->ticket->id,
                    'user_id' => auth()->id(),
                    'accion' => 'Aceptación',
                    'detalle' => 'El usuario indico que están funcionando el set de pruebas',
                ]);

                $this->ticket->cambio->update([
                    'evidencia' => true,
                ]);
            } else {
                Historial::create([
                    'ticket_id' => $this->ticket->id,
                    'user_id' => auth()->id(),
                    'accion' => 'No aceptación',
                    'detalle' => 'El usuario indico que NO está funcionando el set de pruebas.',
                ]);

                $this->ticket->update([
                    'estado_id' => 3,
                ]);


            }
        }



        // Asocia el archivo con el comentario recién creado si existe
        if ($this->newFile) {
            $this->addFile($comentario->id);
        }

        $this->ticket->asignado->notify(new NuevoComentario($comentario));
        if ($this->ticket->colaboradors) {
            foreach ($this->ticket->colaboradors as $colaborador) {
                $colaborador->user->notify(new NuevoComentario($comentario));
            }
        }

        // Limpiar el estado después de agregar el comentario
        $this->newComment = '';
        $this->verTicket('comentarios'); // Refresca los datos del ticket
        $this->updateFlow();
        $this->loadFlow();
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

    public function render()
    {
        $historial = Historial::where('ticket_id', $this->ticket_id)->orderBy('created_at', 'Asc')->get();
        return view('livewire.ticket.ver-ticket', compact('historial'));
    }
}
