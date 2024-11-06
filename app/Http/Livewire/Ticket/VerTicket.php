<?php

namespace App\Http\Livewire\Ticket;

use App\Models\Comentario;
use App\Models\Historial;
use App\Models\Ticket;
use App\Models\TicketHistorial;
use App\Models\User;
use App\Notifications\Finalizado;
use App\Notifications\NoSolucion;
use App\Notifications\NuevoComentario;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

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

        // $this->ticket->asignado->notify(new NuevoComentario($comentario));
        // if ($this->ticket->colaboradors) {
        //     foreach ($this->ticket->colaboradors as $colaborador) {
        //         $colaborador->user->notify(new NuevoComentario($comentario));
        //     }
        // }

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
        return view('livewire.ticket.ver-ticket', compact('historial'));
    }
}
