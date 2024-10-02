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
        // Empieza la consulta base con los filtros de relaciones
        $tickets = Ticket::with('urgencia', 'estado', 'colaboradores', 'asignado');

        // Verifica si ambas fechas están presentes
        if ($this->fecha_desde && $this->fecha_hasta) {
            // Formatea las fechas en el formato 'Y-m-d'
            $fecha_desde = date('Y-m-d', strtotime($this->fecha_desde));
            // Agrega la hora final al día de la fecha_hasta
            $fecha_hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));

            // Aplica el filtro de fechas a la consulta
            $tickets = $tickets->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
        }

        // Ejecuta la consulta y obtiene los tickets
        $tickets = $tickets->get();

        // Envía los datos a la tabla usando el evento Livewire
        $this->emit('cargarGestioTicketTabla', json_encode($tickets));
    }

    // public function calcularHorasSoporte($tickets)
    // {
    //     // Aquí va la lógica para calcular las horas de soporte
    //     return 60; // Suponiendo que el cálculo retorna 60 horas
    // }

    // inicia las fechas el desde, según el primer dia del mes actual y el hasta sera el día actual
    public function iniciarFechas()
    {
        $this->fecha_desde = date('Y-m-1');
        $this->fecha_hasta = date('Y-m-d');
    }

    // public function updateStatus($ticketId, $statusId)
    // {
    //     $ticket = Ticket::find($ticketId);
    //     if ($ticket && $ticket->asignado_a == Auth::id()) {
    //         $ticket->estado_id = $statusId;
    //         $ticket->save();
    //         $this->tickets = Ticket::where('asignado_a', Auth::id())->get();
    //     }
    // }

    public function render()
    {
        if (!$this->fecha_desde && !$this->fecha_hasta) {
            $this->iniciarFechas();
        }
        return view('livewire.home.tickets-home');
    }
}
