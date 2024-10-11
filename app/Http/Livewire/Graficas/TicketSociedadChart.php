<?php

namespace App\Http\Livewire\Graficas;

use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TicketSociedadChart extends Component
{
    public $tipoSolicitudData;
    public $estadoData;
    public $startDate;
    public $endDate;
    public $sociedadData, $categoriaData,$ticketsTotal;

    protected $listeners = ['refreshCharts' => 'loadData'];

    public function mount()
    {
        // Inicializa las fechas predeterminadas (últimos 30 días)
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');

        $this->loadData();
    }

    public function loadData()
    {
        // Valida que haya fechas seleccionadas
        if (!$this->startDate || !$this->endDate) {
            return;
        }
        $this->ticketsTotal = Ticket::count(); // Cuenta todos los tickets

        $this->tipoSolicitudData    = DB::table('tickets')
            ->join('tipo_solicitudes', 'tickets.tipo_solicitud_id', '=', 'tipo_solicitudes.id')
            ->select('tipo_solicitudes.nombre as tipo_solicitud_nombre', DB::raw('COUNT(tickets.id) as total_tickets'))
            // ->whereBetween('tickets.created_at', [$this->startDate, $this->endDate])
            ->groupBy('tipo_solicitudes.nombre')
            ->get();


        $this->estadoData = DB::table('tickets')
            ->join('estados', 'tickets.estado_id', '=', 'estados.id')
            ->select('estados.nombre as estado_nombre', DB::raw('COUNT(tickets.id) as total_tickets'))
            ->groupBy('estados.nombre')
            ->get();

        $this->sociedadData = DB::table('tickets')
            ->join('sociedades', 'tickets.sociedad_id', '=', 'sociedades.id')
            ->select('sociedades.nombre as sociedad_nombre', DB::raw('COUNT(tickets.id) as total_tickets'))
            ->groupBy('sociedades.nombre')
            ->get();

        $this->categoriaData = DB::table('tickets')
            ->join('categorias', 'tickets.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre as categoria_nombre', DB::raw('COUNT(tickets.id) as total_tickets'))
            ->groupBy('categorias.nombre')
            ->get();
    }

    public function updatedStartDate()
    {
        $this->loadData();
        $this->emit('refreshCharts');
    }

    public function updatedEndDate()
    {
        $this->loadData();
        $this->emit('refreshCharts');
    }

    public function render()
    {
        return view('livewire.graficas.ticket-sociedad-chart', [
            'tipoSolicitudChartData' => [
                'labels' => $this->tipoSolicitudData->pluck('tipo_solicitud_nombre')->toArray(),
                'data' => $this->tipoSolicitudData->pluck('total_tickets')->toArray()
            ],
            'estadoChartData' => [
                'labels' => $this->estadoData->pluck('estado_nombre')->toArray(),
                'data' => $this->estadoData->pluck('total_tickets')->toArray()
            ],
            'sociedadChartData' => [
                'labels' => $this->sociedadData->pluck('sociedad_nombre')->toArray(),
                'data' => $this->sociedadData->pluck('total_tickets')->toArray()
            ]
            ,
            'categoriaChartData' => [
                'labels' => $this->categoriaData->pluck('categoria_nombre')->toArray(),
                'data' => $this->categoriaData->pluck('total_tickets')->toArray()
            ]
        ]);
    }
}
