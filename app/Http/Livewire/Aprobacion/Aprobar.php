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
                'detalle' => $this->estado_aprobacion === 'aprobado_funcional' ? 'Aprobado  por el lider funcioanal.' : 'Rechazado por el lider funcional',
            ]);

            if ($this->estado_aprobacion === 'aprobado_funcional') {

                Historial::create([
                    'ticket_id' => $this->ticket_id,
                    'user_id' => Auth::id(),
                    'accion' => 'Aprobación pendiente',
                    'detalle' => 'Aprobación pendiente por TI',
                ]);
                $this->ticket->aprobacion->aprobadorTi->notify(new NotificacionAprobacion($this->ticket->aprobacion, $this->ticket));
            } else {
                $this->ticket->update(['estado_id' => 5]); // Estado cerrado si es rechazado funcionalmente
                Comentario::create([
                    'ticket_id' => $this->ticket->id,
                    'user_id' => 16,
                    'comentario' => 'Debido a que el lider funcional no aprobo la solicitud de acceso, se rechaza el ticket con una calificacion de 4/5⭐',
                    'calificacion' => 4,
                ]);

                $this->ticket->usuario->notify(new RechazoFlujo($this->ticket));
                $this->ticket->asignado->notify(new RechazoFlujo($this->ticket));
            }
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
                'detalle' => $this->estado_aprobacion === 'aprobado_ti' ? 'Aprobación TI realizada.' : 'Rechazo TI: ' . $this->comentariosRechazo,
            ]);

            if ($this->estado_aprobacion === 'aprobado_ti') {
                // Notificar al agente TI para validación con el usuario que generó el ticket

                $this->ticket->aprobacion->update([
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
                $this->ticket->asignado->notify(new FinFlujo($this->ticket));
            } else {
                $this->ticket->aprobacion->aprobadorFuncional->notify(new NotificacionRechazo($this->ticket));
            }

            $this->emit('showToast', ['type' => 'success', 'message' => 'La aprobación TI ha sido ' . $this->estado_aprobacion . '.']);
        } else {
            $this->emit('showToast', ['type' => 'error', 'message' => 'No se pudo actualizar la aprobación.']);
        }

        $this->verTicket();
    }


    public function render()
    {
        $historial = Historial::where('ticket_id', $this->ticket_id)->orderBy('created_at', 'Asc')->get();

        return view('livewire.aprobacion.aprobar', compact('historial'));
    }
}
