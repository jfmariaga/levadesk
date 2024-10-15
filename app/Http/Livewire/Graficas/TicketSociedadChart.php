<?php

namespace App\Http\Livewire\Graficas;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TicketSociedadChart extends Component
{
    public $sociedadSeleccionada;
    public $startDate;
    public $endDate;
    public $sociedadesDisponibles;
    public $totalTickets;
    public $chartDataSociedadEstado;
    public $chartDataTipoSolicitud;
    public $chartDataEstado;
    public $chartDataCategoria;
    public $chartDataVolumenTickets;

    public function mount()
    {
        $this->sociedadesDisponibles = DB::table('sociedades')->pluck('nombre', 'id')->toArray();
        $this->actualizarGraficas();
    }

    public function updatedSociedadSeleccionada()
    {
        $this->actualizarGraficas();
    }

    public function updatedStartDate()
    {
        $this->actualizarGraficas();
    }

    public function updatedEndDate()
    {
        $this->actualizarGraficas();
    }

    public function actualizarGraficas()
    {
        $this->totalTickets = $this->getTotalTickets();
        $this->chartDataSociedadEstado = $this->getChartDataSociedadEstado();
        $this->chartDataTipoSolicitud = $this->getChartDataTipoSolicitud();
        $this->chartDataEstado = $this->getChartDataEstado();
        $this->chartDataCategoria = $this->getChartDataCategoria();
        $this->chartDataVolumenTickets = $this->getChartDataVolumenTickets();

        // Disparar eventos para actualizar cada gráfico
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartData' => $this->chartDataSociedadEstado,
            'chartElementId' => 'ticketSociedadChart',
            'chartType' => 'bar',
            'totalTickets' => $this->totalTickets, // Pasar el total de tickets al evento
        ]);
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartData' => $this->chartDataTipoSolicitud,
            'chartElementId' => 'tipoSolicitudChart',
            'chartType' => 'donut',
        ]);
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartData' => $this->chartDataEstado,
            'chartElementId' => 'ticketEstadoChart',
            'chartType' => 'bar',
        ]);
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartData' => $this->chartDataCategoria,
            'chartElementId' => 'ticketCategoriaChart',
            'chartType' => 'bar',
        ]);
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartData' => $this->chartDataVolumenTickets,
            'chartElementId' => 'volumenTicketsChart',
            'chartType' => 'line',
        ]);

        // Evento específico para el gráfico de totalTickets
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartElementId' => 'totalTicketsChart',
            'totalTickets' => $this->totalTickets, // Pasar el total de tickets
        ]);
    }

    public function getTotalTickets()
    {
        $query = DB::table('tickets');

        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        return $query->count();
    }

    public function getChartDataVolumenTickets()
    {
        $query = DB::table('tickets')
            ->select(DB::raw('DATE(tickets.created_at) as fecha'), DB::raw('COUNT(tickets.id) as total'))
            ->groupBy(DB::raw('DATE(tickets.created_at)'));

        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        $result = $query->get();
        $labels = $result->pluck('fecha')->toArray();
        $data = [
            [
                'label' => 'Volumen de Tickets',
                'data' => $result->pluck('total')->toArray(),
            ],
        ];

        return ['labels' => $labels, 'datasets' => $data];
    }

    public function getChartDataSociedadEstado()
    {
        // Ajustar la consulta para que solo agrupe por sociedad
        $query = DB::table('tickets')
            ->join('sociedades', 'tickets.sociedad_id', '=', 'sociedades.id')
            ->select('sociedades.nombre as sociedad', DB::raw('COUNT(tickets.id) as total'))
            ->groupBy('sociedades.nombre');

        // Filtros opcionales
        if ($this->sociedadSeleccionada) {
            $query->where('sociedades.id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        // Obtener los resultados
        $result = $query->get();

        // Etiquetas de las sociedades
        $labels = $result->pluck('sociedad')->toArray();

        // Datos correspondientes a los totales de tickets por sociedad
        $data = [
            [
                'name' => 'Tickets',
                'data' => $result->pluck('total')->toArray(),
            ]
        ];

        return ['labels' => $labels, 'datasets' => $data];
    }


    public function getChartDataTipoSolicitud()
    {
        $query = DB::table('tickets')
            ->join('tipo_solicitudes', 'tickets.tipo_solicitud_id', '=', 'tipo_solicitudes.id')
            ->select('tipo_solicitudes.nombre as tipo_solicitud', DB::raw('COUNT(tickets.id) as total'))
            ->groupBy('tipo_solicitudes.nombre');

        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        $result = $query->get();
        $labels = $result->pluck('tipo_solicitud')->toArray();
        $data = $result->pluck('total')->toArray();

        return ['labels' => $labels, 'datasets' => [['data' => $data]]];
    }

    public function getChartDataEstado()
    {
        $query = DB::table('tickets')
            ->join('estados', 'tickets.estado_id', '=', 'estados.id')
            ->select('estados.nombre as estado', DB::raw('COUNT(tickets.id) as total'))
            ->groupBy('estados.nombre');

        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        $result = $query->get();
        $labels = $result->pluck('estado')->toArray();
        $data = $result->pluck('total')->toArray();

        return ['labels' => $labels, 'datasets' => [['data' => $data]]];
    }

    public function getChartDataCategoria()
    {
        $query = DB::table('tickets')
            ->join('categorias', 'tickets.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre as categoria', DB::raw('COUNT(tickets.id) as total'))
            ->groupBy('categorias.nombre');

        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        $result = $query->get();
        $labels = $result->pluck('categoria')->toArray();
        $data = $result->pluck('total')->toArray();

        return ['labels' => $labels, 'datasets' => [['data' => $data]]];
    }

    public function render()
    {
        return view('livewire.graficas.ticket-sociedad-chart');
    }
}
