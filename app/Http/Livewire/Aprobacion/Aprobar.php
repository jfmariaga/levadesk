<?php

namespace App\Http\Livewire\Aprobacion;

use App\Models\Comentario;
use App\Models\Historial;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\CambioEstado;
use App\Notifications\FinFlujo;
use App\Notifications\NotificacionAprobacion;
use App\Notifications\NotificacionRechazo;
use App\Notifications\NuevoComentario;
use App\Notifications\RechazoFlujo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;


class Aprobar extends Component
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

    protected $queryString = ['ticket_id'];

    public function mount()
    {
        $this->verTicket();
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
        $this->estado_aprobacion_old = $this->ticket->aprobacion->estado;
        $this->aprobador_funcional_id = $this->ticket->aprobacion->aprobador_funcional_id;
        $this->aprobador_ti_id = $this->ticket->aprobacion->aprobador_ti_id;
    }


    public function aprobarFuncional()
    {
        if ($this->estado_aprobacion === 'rechazado_funcional' && empty($this->comentariosRechazo)) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'El comentario es obligatorio para rechazar el acceso.']);
            return;
        }


        // Guardar los cambios en la aprobación
        $aprobacionActualizada = $this->ticket->aprobacion->update([
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
                    'detalle' => 'Aprobación pendiente del aprobador TI',
                ]);

                // Verificar si el aprobador funcional tiene el rol de "Usuario" y actualizar a "Aprobador"
                $aprobadorTI = User::find($this->ticket->aprobacion->aprobadorTi->id);
                if ($aprobadorTI->hasRole('Usuario')) {
                    $aprobadorTI->syncRoles(['Aprobador']); // Cambia el rol a "Aprobador"
                }
                $this->ticket->aprobacion->aprobadorTi->notify(new NotificacionAprobacion($this->ticket->aprobacion, $this->ticket));
            } else {
                $this->ticket->update(['estado_id' => 5]); // Estado cerrado si es rechazado funcionalmente
                Comentario::create([
                    'ticket_id' => $this->ticket->id,
                    'user_id' => 16,
                    'comentario' => 'Debido a que el líder funcional no aprobó la solicitud de acceso, se rechaza el ticket con una calificación de 5/5⭐',
                    'calificacion' => 5,
                ]);

                $this->ticket->usuario->notify(new RechazoFlujo($this->ticket));
                $this->ticket->asignado->notify(new RechazoFlujo($this->ticket));
            }

            // Emitir un evento de Livewire para que el componente de notificaciones actualice las aprobaciones
            $this->emit('actualizarNotificaciones');
            $this->emit('showToast', ['type' => 'success', 'message' => 'La aprobación funcional ha sido ' . $this->estado_aprobacion . '.']);
        } else {
            $this->emit('showToast', ['type' => 'error', 'message' => 'No se pudo actualizar la aprobación.']);
        }
        $this->verTicket();
    }

    public function aprobarTi()
    {
        if ($this->estado_aprobacion === 'rechazado_ti' && empty($this->comentariosRechazo)) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'El comentario es obligatorio para rechazar el acceso.']);
            return;
        }

        // Guardar los cambios en la aprobación
        $aprobacionActualizada = $this->ticket->aprobacion->update([
            'estado' => $this->estado_aprobacion,
            'comentarios_ti' => $this->comentariosRechazo,
        ]);

        if ($aprobacionActualizada) {
            Historial::create([
                'ticket_id' => $this->ticket_id,
                'user_id' => Auth::id(),
                'accion' => 'Aprobación TI',
                'detalle' => $this->estado_aprobacion === 'aprobado_ti' ? 'Aprobación TI realizada por '.Auth::user()->name :'El aprobador TI '. Auth::user()->name. ' No aprobó, motivo: ' . $this->comentariosRechazo,
            ]);

            if ($this->estado_aprobacion === 'aprobado_ti') {
                // Notificar al agente TI para validación con el usuario que generó el ticket

                $this->ticket->aprobacion->update([
                    'estado' => 'aprobado',
                ]);

                $this->ticket->update([
                    'estado_id' => 15
                ]);

                Historial::create([
                    'ticket_id' => $this->ticket_id,
                    'user_id' => Auth::id(),
                    'accion' => 'Cambio de estado',
                    'detalle' => 'El ticket cambio de estado En espera a configuración de acceso',
                ]);
                $this->ticket->usuario->notify(new CambioEstado($this->ticket));
                $this->ticket->asignado->notify(new FinFlujo($this->ticket));
            } else {
                $this->ticket->aprobacion->aprobadorFuncional->notify(new NotificacionRechazo($this->ticket));
            }
            $this->emit('actualizarNotificaciones');

            $this->emit('showToast', ['type' => 'success', 'message' => 'La aprobación TI ha sido ' . $this->estado_aprobacion . '.']);
        } else {
            $this->emit('showToast', ['type' => 'error', 'message' => 'No se pudo actualizar la aprobación.']);
        }

        $this->verTicket();
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

        if ($this->ticket->aprobacion->aprobadorFuncional) {
            $this->ticket->aprobacion->aprobadorFuncional->notify(new NuevoComentario($comentario));
        }

        if ($this->ticket->aprobacion->aprobadorTi) {
            $this->ticket->aprobacion->aprobadorTi->notify(new NuevoComentario($comentario));
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

    public function render()
    {
        $historial = Historial::where('ticket_id', $this->ticket_id)->orderBy('created_at', 'Asc')->get();

        return view('livewire.aprobacion.aprobar', compact('historial'));
    }
}
