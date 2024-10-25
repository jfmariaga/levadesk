<?php

namespace App\Http\Livewire\Home;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketsHome extends Component
{
    public $tickets;
    public $ticketsSolucionados;
    public $ticketsEnProceso;
    public $ticketsPorIniciar;
    public $totalHorasSoporte;
    public $fecha_desde, $fecha_hasta;
    public $item;

    protected $listeners = ['cargarDatos', 'gestionTicket'];
    protected $queryString = ['fecha_desde', 'fecha_hasta'];


    public function mount()
    {
        $this->iniciarFechas();
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $tickets = Ticket::with('urgencia', 'estado', 'colaboradores', 'asignado','categoria','subcategoria','usuario');

        if ($this->fecha_desde && $this->fecha_hasta) {
            $fecha_desde = date('Y-m-d', strtotime($this->fecha_desde));
            $fecha_hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
            $tickets = $tickets->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
        }

        $tickets = $tickets->get();

        $this->emit('cargarGestioTicketTabla', json_encode($tickets));
    }

    public function iniciarFechas()
    {
        $this->fecha_desde = date('Y-m-1');
        $this->fecha_hasta = date('Y-m-d');
    }

    public function render()
    {
        if (!$this->fecha_desde && !$this->fecha_hasta) {
            $this->iniciarFechas();
        }
        return view('livewire.home.tickets-home');
    }
}
