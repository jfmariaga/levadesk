<?php

namespace App\Http\Livewire\Home;

use App\Models\Estado;
use App\Models\Sociedad;
use Livewire\Component;
use App\Models\Ticket;
use App\Models\User;
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
    public $estados, $SelectedEstado;
    public $usuarios, $selectedUsuario;
    public $agentes, $selectedAgente;
    public $sociedades, $selectedSociedad;

    protected $listeners = ['cargarDatos', 'gestionTicket'];
    protected $queryString = ['fecha_desde', 'fecha_hasta'];

    public function mount()
    {
        $this->iniciarFechas();
        $this->cargarDatos();
        $this->estados = Estado::all();
        $this->usuarios = User::all();
        $this->agentes = User::role(['Agente', 'Admin'])->get();
        $this->sociedades = Sociedad::all();
    }

    public function filtrarPorIniciar()
    {
        // Definir el estado de "Por Iniciar"
        $this->SelectedEstado = [1];
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $tickets = Ticket::with('urgencia', 'estado', 'colaboradores', 'asignado','categoria','subcategoria','usuario','tipoSolicitud','aplicacion', 'sociedad');
        if ($this->fecha_desde && $this->fecha_hasta) {
            $fecha_desde = date('Y-m-d', strtotime($this->fecha_desde));
            $fecha_hasta = date('Y-m-d 23:59:59', strtotime($this->fecha_hasta));
            $tickets = $tickets->whereBetween('created_at', [$fecha_desde, $fecha_hasta]);
        }

        // Aplicar filtro de estado si SelectedEstado no está vacío
        if (is_array($this->SelectedEstado) && !empty($this->SelectedEstado)) {
            $tickets->whereIn('estado_id', $this->SelectedEstado);
        }

        if ($this->selectedUsuario) {
            $tickets->where('usuario_id', $this->selectedUsuario);
        }

        if ($this->selectedAgente) {
            $tickets->where('asignado_a', $this->selectedAgente);
        }

        if ($this->selectedSociedad) {
            $tickets->where('sociedad_id', $this->selectedSociedad);
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
