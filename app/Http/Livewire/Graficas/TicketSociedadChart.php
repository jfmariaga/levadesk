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
    public $chartDataSatisfaccionUsuario;
    public $respuestaInicialPromedio;
    public $tiempoResolucionPromedio;
    public $tasaEscalamiento;
    public $tasaReapertura;

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
        $this->chartDataSatisfaccionUsuario = $this->getChartDataSatisfaccionUsuario();
        $this->respuestaInicialPromedio = $this->getChartDataRespuestaInicialPromedio();
        $this->tiempoResolucionPromedio = $this->getChartDataTiempoResolucionPromedio();
        $this->tasaEscalamiento = $this->getTasaEscalamiento();
        $this->tasaReapertura = $this->getTasaReapertura();

        // Disparar eventos para actualizar cada gráfico
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartData' => $this->chartDataSociedadEstado,
            'chartElementId' => 'ticketSociedadChart',
            'chartType' => 'bar',
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

        // Promedio de satisfacción del usuario
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'promedioSatisfaccion' => $this->chartDataSatisfaccionUsuario,
            'chartElementId' => 'satisfaccionUsuarioChart',
        ]);

        // Total Tickets
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'totalTickets' => $this->totalTickets,
            'chartElementId' => 'totalTicketsChart',
        ]);

        // Promedio de respuesta inicial
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'respuestaInicialPromedio' => $this->respuestaInicialPromedio,
            'chartElementId' => 'respuestaInicialPromedioChart',
        ]);

        // Promedio de resolución de tickets
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'tiempoResolucionPromedio' => $this->tiempoResolucionPromedio,
            'chartElementId' => 'tiempoResolucionPromedioChart',
        ]);

        // Tasa de Escalamiento
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'tasaEscalamiento' => $this->tasaEscalamiento,
            'chartElementId' => 'tasaEscalamientoChart',
        ]);

        // Tasa de Reapertura
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'tasaReapertura' => $this->tasaReapertura,
            'chartElementId' => 'tasaReaperturaChart',
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

    public function getChartDataRespuestaInicialPromedio()
    {
        // Construir la subconsulta y añadir los filtros de fechas y sociedad dentro de ella
        $query = DB::table(DB::raw('(
        SELECT tickets.id AS ticket_id,
               MIN(TIMESTAMPDIFF(MINUTE, tickets.created_at, comentarios.created_at)) AS respuesta_inicial_minutos
        FROM tickets
        JOIN comentarios ON comentarios.ticket_id = tickets.id
        WHERE comentarios.user_id = tickets.asignado_a'
            . ($this->sociedadSeleccionada ? ' AND tickets.sociedad_id = ' . $this->sociedadSeleccionada : '')
            . ($this->startDate && $this->endDate ? " AND tickets.created_at BETWEEN '" . $this->startDate . "' AND '" . $this->endDate . "'" : '') . '
        GROUP BY tickets.id
    ) as subconsulta'));

        // Calcular el promedio de respuesta inicial basado en la subconsulta
        $promedioRespuestaInicial = $query->avg('respuesta_inicial_minutos');

        return round($promedioRespuestaInicial, 2);
    }



    public function getChartDataTiempoResolucionPromedio()
    {
        $query = DB::table('tickets')
            ->whereIn('estado_id', ['4', '5']) // Estados finalizado o rechazado
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as tiempo_resolucion_promedio'));

        if ($this->sociedadSeleccionada) {
            $query->where('sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $promedioResolucion = $query->value('tiempo_resolucion_promedio');
        return round($promedioResolucion, 2);
    }

    public function getTasaEscalamiento()
    {
        $queryTotalTickets = DB::table('tickets');
        $queryEscalados = DB::table('ticket_historial')
            ->join('tickets', 'ticket_historial.ticket_id', '=', 'tickets.id')
            ->where('ticket_historial.estado_id', 9); // Estado escalado

        if ($this->sociedadSeleccionada) {
            $queryTotalTickets->where('tickets.sociedad_id', $this->sociedadSeleccionada);
            $queryEscalados->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $queryTotalTickets->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
            $queryEscalados->whereBetween('ticket_historial.fecha_cambio', [$this->startDate, $this->endDate]);
        }

        $totalTickets = $queryTotalTickets->count();
        $totalEscalados = $queryEscalados->count();

        return $totalTickets == 0 ? 0 : round(($totalEscalados / $totalTickets) * 100, 2);
    }

    public function getTasaReapertura()
    {
        $queryCerrados = DB::table('ticket_historial')
            ->join('tickets', 'ticket_historial.ticket_id', '=', 'tickets.id')
            ->whereIn('ticket_historial.estado_id', [4, 5]); // Estados cerrados o resueltos

        $queryReabiertos = DB::table('ticket_historial')
            ->join('tickets', 'ticket_historial.ticket_id', '=', 'tickets.id')
            ->where('ticket_historial.estado_id', 7); // Estado reabierto

        if ($this->sociedadSeleccionada) {
            $queryCerrados->where('tickets.sociedad_id', $this->sociedadSeleccionada);
            $queryReabiertos->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $queryCerrados->whereBetween('ticket_historial.fecha_cambio', [$this->startDate, $this->endDate]);
            $queryReabiertos->whereBetween('ticket_historial.fecha_cambio', [$this->startDate, $this->endDate]);
        }

        $totalCerrados = $queryCerrados->count();
        $totalReabiertos = $queryReabiertos->count();

        return $totalCerrados == 0 ? 0 : round(($totalReabiertos / $totalCerrados) * 100, 2);
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
        $query = DB::table('tickets')
            ->join('sociedades', 'tickets.sociedad_id', '=', 'sociedades.id')
            ->select('sociedades.nombre as sociedad', DB::raw('COUNT(tickets.id) as total'))
            ->groupBy('sociedades.nombre');

        if ($this->sociedadSeleccionada) {
            $query->where('sociedades.id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        $result = $query->get();
        $labels = $result->pluck('sociedad')->toArray();
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

    public function getChartDataSatisfaccionUsuario()
    {
        $query = DB::table('comentarios')
            ->join('tickets', 'comentarios.ticket_id', '=', 'tickets.id')
            ->join('sociedades', 'tickets.sociedad_id', '=', 'sociedades.id')
            ->whereNotNull('comentarios.calificacion');

        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        $promedioSatisfaccion = $query->avg('comentarios.calificacion');
        return round($promedioSatisfaccion, 2);
    }

    public function render()
    {
        return view('livewire.graficas.ticket-sociedad-chart');
    }
}
