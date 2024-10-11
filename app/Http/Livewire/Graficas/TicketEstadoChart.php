<?php

namespace App\Http\Livewire\Graficas;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TicketEstadoChart extends Component
{
    public $sociedadData = [];
    public $tipoSolicitudData = [];
    public $categoriaData = [];
    public $desde;
    public $hasta;

    public function mount()
    {
        $this->desde = now()->startOfMonth()->toDateString();
        $this->hasta = now()->endOfMonth()->toDateString();
        $this->loadData();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'desde' || $propertyName === 'hasta') {
            $this->loadData();
        }
    }

    public function loadData()
    {
        // Datos para Sociedad
        $this->sociedadData = DB::table('tickets')
            ->join('sociedades', 'tickets.sociedad_id', '=', 'sociedades.id')
            ->whereBetween('tickets.created_at', [$this->desde, $this->hasta])
            ->select('sociedades.nombre as sociedad_nombre', DB::raw('COUNT(tickets.id) as total_tickets'))
            ->groupBy('sociedades.nombre')
            ->get()
            ->toArray();

        // Datos para Tipo de Solicitud
        $this->tipoSolicitudData = DB::table('tickets')
            ->join('tipo_solicitudes', 'tickets.tipo_solicitud_id', '=', 'tipo_solicitudes.id')
            ->whereBetween('tickets.created_at', [$this->desde, $this->hasta])
            ->select('tipo_solicitudes.nombre as tipo_solicitud_nombre', DB::raw('COUNT(tickets.id) as total_tickets'))
            ->groupBy('tipo_solicitudes.nombre')
            ->get()
            ->toArray();

        // Datos para Categoría
        $this->categoriaData = DB::table('tickets')
            ->join('categorias', 'tickets.categoria_id', '=', 'categorias.id')
            ->whereBetween('tickets.created_at', [$this->desde, $this->hasta])
            ->select('categorias.nombre as categoria_nombre', DB::raw('COUNT(tickets.id) as total_tickets'))
            ->groupBy('categorias.nombre')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.graficas.ticket-estado-chart', [
            'sociedadData' => $this->sociedadData,
            'tipoSolicitudData' => $this->tipoSolicitudData,
            'categoriaData' => $this->categoriaData,
        ]);
    }
}
