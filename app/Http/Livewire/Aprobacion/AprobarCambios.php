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
                'detalle' => $this->estado_aprobacion === 'aprobado_funcional' ? 'Aprobado  por el lider funcioanal.' : 'Rechazado por el lider funcional',
            ]);

            if ($this->estado_aprobacion === 'aprobado_funcional') {

                Historial::create([
                    'ticket_id' => $this->ticket_id,
                    'user_id' => Auth::id(),
                    'accion' => 'Aprobación pendiente',
                    'detalle' => 'Aprobación pendiente por TI',
                ]);
                $this->ticket->cambio->aprobadorTiCambio->notify(new NotificacionAprobacion($this->ticket->cambio, $this->ticket));
            } else {
                $this->ticket->update(['estado_id' => 5]); // Estado cerrado si es rechazado funcionalmente
                Comentario::create([
                    'ticket_id' => $this->ticket->id,
                    'user_id' => 16,
                    'comentario' => 'Debido a que el lider funcional no aprobo la solicitud de acceso, se rechaza el ticket con una calificacion de 4/5⭐',
                    'calificacion' => 4,
                ]);

                $this->ticket->usuario->notify(new RechazoFlujoCambio($this->ticket));
                $this->ticket->asignado->notify(new RechazoFlujoCambio($this->ticket));
            }
            $this->emit('showToast', ['type' => 'success', 'message' => 'La aprobación funcional ha sido ' . $this->estado_aprobacion . '.']);
        } else {
            $this->emit('showToast', ['type' => 'error', 'message' => 'No se pudo actualizar la aprobación.']);
        }
        $this->verTicket();
    }

    public function aprobarTiCambio()
    {
        if ($this->estado_aprobacion === 'rechazado_ti' && empty($this->comentariosRechazo)) {
            $this->emit('showToast', ['type' => 'error', 'message' => 'El comentario es obligatorio para rechazar el acceso.']);
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
                'detalle' => $this->estado_aprobacion === 'aprobado_ti' ? 'Aprobación TI realizada.' : 'Rechazo TI: ' . $this->comentariosRechazo,
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
                $this->ticket->cambio->aprobadorFuncionalCambio->notify(new NotificacionRechazoCambio($this->ticket));
            }

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

    public function aprobarSet(){
        $this->ticket->update([
            'estado_id' => 3
        ]);

        $this->ticket->cambio->update([
            'check_aprobado_ti' => true,
        ]);

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'set ',
            'detalle' => 'Se aprobó el paso a producción',
        ]);

        $this->ticket->asignado->notify(new AprobarProductivo($this->ticket));
        $this->emit('showToast', ['type' => 'success', 'message' => 'Se aprobo el set de pruebas']);
        $this->verTicket();
    }

    public function rechazarSet($id){

        $comentario =  Comentario::find($id);
        $comentario->update([
            'check_comentario' => false,
        ]);

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'set ',
            'detalle' => 'No se aprobó el paso a producción',
        ]);

        Historial::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'accion' => 'cambio estado',
            'detalle' => 'Nuevo estado del ticket es: En atención',
        ]);

        $this->ticket->asignado->notify(new NoAprobarProductivo($this->ticket));
        $this->emit('showToast', ['type' => 'warning', 'message' => 'No se aprobo el paso a producción']);
        $this->verTicket();
    }

    public function render()
    {
        $historial = Historial::where('ticket_id', $this->ticket_id)->orderBy('created_at', 'Asc')->get();

        return view('livewire.aprobacion.aprobar-cambios', compact('historial'));
    }
}
