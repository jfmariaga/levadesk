<?php

namespace App\Http\Livewire\Ticket;

use App\Models\Historial;
use App\Models\Ticket;
use App\Models\User;
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
    public $ticket_old;
    public $urgencia;
    public $usuarioId;
    public $newComment;
    public $newFile;
    public $usuarios;
    public $modalId;

    protected $listeners = ['verTicket'];

    public function mount()
    {
        $this->usuarios = User::all();
    }

    public function verTicket($id)
    {
        $this->modalId = $id;
        $this->ticket_old = Ticket::with('historial')->find($id);
        $this->nomenclatura = $this->ticket_old->nomenclatura;
        $this->sociedad_id = $this->ticket_old->sociedad_id;
        $this->tipo_solicitud_id = $this->ticket_old->tipo_solicitud_id;
        $this->categoria_id = $this->ticket_old->categoria_id;
        $this->subcategoria_id = $this->ticket_old->subcategoria_id;
        $this->titulo = $this->ticket_old->titulo;
        $this->descripcion = $this->ticket_old->descripcion ? $this->ticket_old->descripcion : '';
        $this->estado_id = $this->ticket_old->estado_id;
    }


    public function addComment()
    {
        $this->validate(['newComment' => 'required|string|max:255']);
        $this->ticket_old->comentarios()->create([
            'user_id' => auth()->id(),
            'comentario' => $this->newComment,
        ]);
        $this->newComment = '';
        $this->verTicket($this->ticket_old->id); // Refresh ticket data
    }

    public function addFile()
    {
        $this->validate(['newFile' => 'required|file|max:10240']);
        $nombre_original = $this->newFile->getClientOriginalName();
        $nombre_sin_extension = pathinfo($nombre_original, PATHINFO_FILENAME);
        $extension = $this->newFile->getClientOriginalExtension();
        $nombre_db = Str::slug($nombre_sin_extension);
        $nombre_a_guardar = $nombre_db . '.' . $extension;
        $path = $this->newFile->storeAs('public/tickets', $nombre_a_guardar);
        $this->ticket_old->archivos()->create([
            'ruta' => $path,
        ]);
        $this->newFile = null;
        $this->verTicket($this->ticket_old->id); // Refresh ticket data
    }

    public function resetForm()
    {
        $this->reset(['newComment', 'newFile']);
    }

    public function render()
    {
        $historial = Historial::where('ticket_id', $this->modalId)->orderBy('created_at', 'Asc')->get();

        return view('livewire.ticket.ver-ticket', compact('historial'));
    }
}
