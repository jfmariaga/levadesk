<?php

namespace App\Http\Livewire\Ticket;

use App\Models\Estado;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $fecha_desde, $fecha_hasta;
    public $estados, $SelectedEstado;


    protected $listeners = ['cargarTickets'];
    protected $queryString = ['fecha_desde', 'fecha_hasta'];

    public function mount()
    {
        $this->iniciarFechas();
        $this->estados = Estado::all();

        // Obtener los estados de la sesión y convertirlos en array si no lo son
        $this->SelectedEstado = session()->pull('estadoIds', []);

        // Asegurarse de que SelectedEstado sea siempre un array
        if (!is_array($this->SelectedEstado)) {
            $this->SelectedEstado = explode(',', $this->SelectedEstado);
        }

        $this->cargarTickets();
    }


    public function iniciarFechas()
    {
        $this->fecha_desde = date('2024-11-1');
        $this->fecha_hasta = date('Y-m-d');
    }

    public function render()
    {
        if (!$this->fecha_desde && !$this->fecha_hasta) {
            $this->iniciarFechas();
        }

        return view('livewire.ticket.index');
    }

    // public function cargarTickets()
    // {
    //     $userId = Auth::id();
    //     if ($this->SelectedEstado) {
    //         $tickets = Ticket::where('usuario_id', $userId)->where('estado_id', $this->SelectedEstado)->with(['usuario', 'asignado', 'sociedad', 'tipoSolicitud', 'categoria', 'subcategoria', 'estado']);
    //     } else {
    //         $tickets = Ticket::where('usuario_id', $userId)->with(['usuario', 'asignado', 'sociedad', 'tipoSolicitud', 'categoria', 'subcategoria', 'estado']);
    //     }

    //     if ($this->fecha_desde && $this->fecha_hasta) {
    //         $fecha_desde = date('Y-m-d', strtotime($this->fecha_desde));
    //         $fecha_hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
    //         $tickets = $tickets->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
    //     }

    //     $tickets = $tickets->get();
    //     $this->emit('cargarTicketsTabla', json_encode($tickets));
    // }

    public function cargarTickets()
    {
        $userId = Auth::id();

        // Si SelectedEstado es un array vacío, mostrar todos los tickets sin aplicar filtro de estado
        if (is_array($this->SelectedEstado) && !empty($this->SelectedEstado)) {
            $tickets = Ticket::where('usuario_id', $userId)
                ->whereIn('estado_id', $this->SelectedEstado)
                ->with(['usuario', 'asignado', 'sociedad', 'tipoSolicitud', 'categoria', 'subcategoria', 'estado']);
        } else {
            // Si SelectedEstado está vacío, cargar todos los tickets sin filtro de estado
            $tickets = Ticket::where('usuario_id', $userId)
                ->with(['usuario', 'asignado', 'sociedad', 'tipoSolicitud', 'categoria', 'subcategoria', 'estado']);
        }

        if ($this->fecha_desde && $this->fecha_hasta) {
            $fecha_desde = date('Y-m-d', strtotime($this->fecha_desde));
            $fecha_hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
            $tickets = $tickets->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
        }

        $tickets = $tickets->get();
        $this->emit('cargarTicketsTabla', json_encode($tickets));
    }
}
