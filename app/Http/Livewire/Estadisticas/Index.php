<?php

namespace App\Http\Livewire\Estadisticas;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Asegúrate de que estás usando el modelo User
use App\Models\Sociedad;
use App\Models\TipoSolicitud;
use App\Models\Categoria;
use App\Models\Ticket;
use App\Models\Urgencia; // Si tienes un modelo para las prioridades
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $sociedadSeleccionada;
    public $asignadoASeleccionado;
    public $tipoSolicitudSeleccionado;
    public $categoriaSeleccionada;
    public $prioridadSeleccionada;
    public $startDate;
    public $endDate;
    public $sociedadesDisponibles;
    public $agentesDisponibles;
    public $tiposSolicitudDisponibles;
    public $categoriasDisponibles;
    public $prioridadesDisponibles;
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
    public $chartDataCumplimientoANS;
    public $chartDataCumplimientoANSInicial;
    public $chartDataEstadoPorMes;
    public $ticketsSolucionados;

    public function mount()
    {
        $this->sociedadesDisponibles = Sociedad::where('estado', 0)
            ->pluck('nombre', 'id')
            ->toArray();

        $this->agentesDisponibles = User::where('estado', 1)
            ->role(['Agente', 'Admin'])
            ->pluck('name', 'id')
            ->toArray();


        $this->tiposSolicitudDisponibles = TipoSolicitud::where('estado', 0)
            ->pluck('nombre', 'id')
            ->toArray();

        $this->categoriasDisponibles = Categoria::where('estado', 0)
            ->pluck('nombre', 'id')
            ->toArray();

        $this->prioridadesDisponibles = Urgencia::pluck('nombre', 'id')
            ->toArray();

        $this->asignadoASeleccionado = Auth::user()->id;
        // dd($this->asignadoASeleccionado);

        $this->ticketsSolucionados = Ticket::where('estado_id', 4)->where('asignado_a', Auth::user()->id)->get();


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

    public function updatedAsignadoASeleccionado()
    {
        $this->actualizarGraficas();
    }

    public function updatedTipoSolicitudSeleccionado()
    {
        $this->actualizarGraficas();
    }

    public function updatedCategoriaSeleccionada()
    {
        $this->actualizarGraficas();
    }

    public function updatedPrioridadSeleccionada()
    {
        $this->actualizarGraficas();
    }

    public function actualizarGraficas()
    {
        $this->totalTickets = $this->getTotalTickets();
        $this->chartDataSociedadEstado = $this->getChartDataSociedadEstado();
        $this->chartDataTipoSolicitud = $this->getChartDataTipoSolicitud();
        $this->chartDataEstado = $this->getChartDataEstado();
        $this->chartDataEstadoPorMes = $this->getChartDataEstadoPorMes();
        $this->chartDataCategoria = $this->getChartDataCategoria();
        $this->chartDataVolumenTickets = $this->getChartDataVolumenTickets();
        $this->chartDataSatisfaccionUsuario = $this->getChartDataSatisfaccionUsuario();
        $this->respuestaInicialPromedio = $this->getChartDataRespuestaInicialPromedio();
        $this->tiempoResolucionPromedio = $this->getChartDataTiempoResolucionPromedio();
        $this->tasaEscalamiento = $this->getTasaEscalamiento();
        $this->tasaReapertura = $this->getTasaReapertura();
        $this->chartDataCumplimientoANS = $this->getChartDataCumplimientoANS();
        $this->chartDataCumplimientoANSInicial = $this->getChartDataCumplimientoANSInicial();

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
            'treemapData' => $this->chartDataCategoria,
            'chartElementId' => 'ticketCategoriaChart',
            'chartType' => 'treemap',
        ]);
        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartData' => $this->chartDataVolumenTickets,
            'chartElementId' => 'volumenTicketsChart',
            'chartType' => 'line',
        ]);

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

        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartData' => $this->chartDataCumplimientoANS,
            'chartElementId' => 'cumplimientoANSChart',
            'chartType' => 'donut',
        ]);

        $this->dispatchBrowserEvent('chartDataUpdated', [
            'chartData' => $this->chartDataCumplimientoANSInicial,
            'chartElementId' => 'cumplimientoANSInicialChart',
            'chartType' => 'donut',
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

        if ($this->asignadoASeleccionado) {
            $query->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        if ($this->tipoSolicitudSeleccionado) {
            $query->where('tickets.tipo_solicitud_id', $this->tipoSolicitudSeleccionado);
        }

        if ($this->categoriaSeleccionada) {
            $query->where('tickets.categoria_id', $this->categoriaSeleccionada);
        }

        if ($this->prioridadSeleccionada) {
            $query->where('tickets.prioridad_id', $this->prioridadSeleccionada);
        }

        return $query->count();
    }

    public function getChartDataCumplimientoANS()
    {
        $queryCumplidos = DB::table('tickets')
            ->join('a_n_s', 'tickets.ans_id', '=', 'a_n_s.id')
            ->whereNotNull('tickets.tiempo_inicio_resolucion')
            ->whereRaw('TIMESTAMPDIFF(SECOND, tickets.tiempo_inicio_resolucion, NOW()) <= a_n_s.t_resolucion_segundos')
            ->whereNotNull('tickets.tiempo_inicio_aceptacion');

        if ($this->sociedadSeleccionada) {
            $queryCumplidos->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $queryCumplidos->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $queryCumplidos->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        if ($this->tipoSolicitudSeleccionado) {
            $queryCumplidos->where('tickets.tipo_solicitud_id', $this->tipoSolicitudSeleccionado);
        }

        if ($this->categoriaSeleccionada) {
            $queryCumplidos->where('tickets.categoria_id', $this->categoriaSeleccionada);
        }

        if ($this->prioridadSeleccionada) {
            $queryCumplidos->where('tickets.prioridad_id', $this->prioridadSeleccionada);
        }

        $cumplidos = $queryCumplidos->count();

        $queryNoCumplidos = DB::table('tickets')
            ->join('a_n_s', 'tickets.ans_id', '=', 'a_n_s.id')
            ->whereNotNull('tickets.tiempo_inicio_resolucion')
            ->where(function ($query) {
                $query->whereRaw('TIMESTAMPDIFF(SECOND, tickets.tiempo_inicio_resolucion, NOW()) > a_n_s.t_resolucion_segundos')
                    ->orWhereNull('tickets.tiempo_inicio_aceptacion');
            });

        if ($this->sociedadSeleccionada) {
            $queryNoCumplidos->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $queryNoCumplidos->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $queryNoCumplidos->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        if ($this->tipoSolicitudSeleccionado) {
            $queryNoCumplidos->where('tickets.tipo_solicitud_id', $this->tipoSolicitudSeleccionado);
        }

        if ($this->categoriaSeleccionada) {
            $queryNoCumplidos->where('tickets.categoria_id', $this->categoriaSeleccionada);
        }

        if ($this->prioridadSeleccionada) {
            $queryNoCumplidos->where('tickets.prioridad_id', $this->prioridadSeleccionada);
        }

        $noCumplidos = $queryNoCumplidos->count();

        return [
            'labels' => ['Cumplidos', 'No Cumplidos'],
            'datasets' => [
                [
                    'data' => [$cumplidos, $noCumplidos],
                ]
            ]
        ];
    }

    public function getChartDataCumplimientoANSInicial()
    {
        $queryCumplidos = DB::table('tickets')
            ->where('tickets.ans_inicial_vencido', 0);

        if ($this->sociedadSeleccionada) {
            $queryCumplidos->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $queryCumplidos->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $queryCumplidos->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        if ($this->tipoSolicitudSeleccionado) {
            $queryCumplidos->where('tickets.tipo_solicitud_id', $this->tipoSolicitudSeleccionado);
        }

        if ($this->categoriaSeleccionada) {
            $queryCumplidos->where('tickets.categoria_id', $this->categoriaSeleccionada);
        }

        if ($this->prioridadSeleccionada) {
            $queryCumplidos->where('tickets.prioridad_id', $this->prioridadSeleccionada);
        }

        // Contar los tickets que cumplieron el ANS inicial
        $cumplidos = $queryCumplidos->count();

        // Consulta para contar los tickets que NO cumplieron el ANS inicial (ans_inicial_vencido = 1)
        $queryNoCumplidos = DB::table('tickets')
            ->where('tickets.ans_inicial_vencido', 1); // ANS inicial no cumplido

        // Aplicar los mismos filtros
        if ($this->sociedadSeleccionada) {
            $queryNoCumplidos->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $queryNoCumplidos->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $queryNoCumplidos->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        if ($this->tipoSolicitudSeleccionado) {
            $queryNoCumplidos->where('tickets.tipo_solicitud_id', $this->tipoSolicitudSeleccionado);
        }

        if ($this->categoriaSeleccionada) {
            $queryNoCumplidos->where('tickets.categoria_id', $this->categoriaSeleccionada);
        }

        if ($this->prioridadSeleccionada) {
            $queryNoCumplidos->where('tickets.prioridad_id', $this->prioridadSeleccionada);
        }

        $noCumplidos = $queryNoCumplidos->count();

        return [
            'labels' => ['Cumplidos', 'No Cumplidos'],
            'datasets' => [
                [
                    'data' => [$cumplidos, $noCumplidos],
                ]
            ]
        ];
    }

    public function getChartDataRespuestaInicialPromedio()
    {
        $subconsulta = DB::table('tickets')
            ->join('comentarios', 'comentarios.ticket_id', '=', 'tickets.id')
            ->select(DB::raw('tickets.id AS ticket_id, MIN(TIMESTAMPDIFF(MINUTE, tickets.created_at, comentarios.created_at)) AS respuesta_inicial_minutos'))
            ->where('comentarios.user_id', DB::raw('tickets.asignado_a'))
            ->groupBy('tickets.id');

        if ($this->sociedadSeleccionada) {
            $subconsulta->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $subconsulta->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $subconsulta->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        $promedioRespuestaInicial = DB::table(DB::raw("({$subconsulta->toSql()}) as subconsulta"))
            ->mergeBindings($subconsulta)
            ->avg('respuesta_inicial_minutos');

        // Convertir el promedio de minutos a horas dividiéndolo por 60
        return round($promedioRespuestaInicial / 60, 2);
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

        if ($this->asignadoASeleccionado) {
            $query->where('asignado_a', $this->asignadoASeleccionado);
        }

        $promedioResolucion = $query->value('tiempo_resolucion_promedio');

        // Convertir el tiempo promedio de minutos a horas dividiéndolo por 60
        return round($promedioResolucion / 60, 2);
    }


    public function getTasaEscalamiento()
    {
        // Total de tickets en la tabla tickets
        $queryTotalTickets = DB::table('tickets');

        // Tickets que han sido escalados en ticket_historial con estado_id = 9
        $queryEscalados = DB::table('ticket_historial')
            ->join('tickets', 'ticket_historial.ticket_id', '=', 'tickets.id')
            ->where('ticket_historial.estado_id', 9); // Estado escalado

        // Aplicar los filtros
        if ($this->sociedadSeleccionada) {
            $queryTotalTickets->where('tickets.sociedad_id', $this->sociedadSeleccionada);
            $queryEscalados->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $queryTotalTickets->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
            $queryEscalados->whereBetween('ticket_historial.fecha_cambio', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $queryEscalados->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        // Contar el total de tickets y el número de tickets escalados
        $totalTickets = $queryTotalTickets->count();
        $totalEscalados = $queryEscalados->count();

        // Calcular y retornar la tasa de escalamiento
        return $totalTickets == 0 ? 0 : round(($totalEscalados / $totalTickets) * 100, 2);
    }


    public function getTasaReapertura()
    {
        // Total de tickets en la tabla tickets
        $queryTotalTickets = DB::table('tickets');

        // Tickets que han sido reabiertos en ticket_historial con estado_id = 7
        $queryReabiertos = DB::table('ticket_historial')
            ->join('tickets', 'ticket_historial.ticket_id', '=', 'tickets.id')
            ->where('ticket_historial.estado_id', 7); // Estado reabierto

        // Aplicar los filtros
        if ($this->sociedadSeleccionada) {
            $queryTotalTickets->where('tickets.sociedad_id', $this->sociedadSeleccionada);
            $queryReabiertos->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $queryTotalTickets->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
            $queryReabiertos->whereBetween('ticket_historial.fecha_cambio', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $queryReabiertos->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        // Contar el total de tickets y el número de tickets reabiertos
        $totalTickets = $queryTotalTickets->count();
        $totalReabiertos = $queryReabiertos->count();

        // Calcular y retornar la tasa de reapertura
        return $totalTickets == 0 ? 0 : round(($totalReabiertos / $totalTickets) * 100, 2);
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

        if ($this->asignadoASeleccionado) {
            $query->where('tickets.asignado_a', $this->asignadoASeleccionado);
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
            ->groupBy('sociedades.nombre')
            ->orderByDesc('total'); // Orden descendente

        if ($this->sociedadSeleccionada) {
            $query->where('sociedades.id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $query->where('tickets.asignado_a', $this->asignadoASeleccionado);
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
            ->groupBy('tipo_solicitudes.nombre')
            ->orderByDesc('total'); // Orden descendente

        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $query->where('tickets.asignado_a', $this->asignadoASeleccionado);
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
            ->groupBy('estados.nombre')
            ->orderBy('total'); // Orden descendente

        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $query->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        $result = $query->get();
        $labels = $result->pluck('estado')->toArray();
        $data = $result->pluck('total')->toArray();

        return ['labels' => $labels, 'datasets' => [['data' => $data]]];
    }

    public function getChartDataEstadoPorMes()
    {
        $query = DB::table('tickets')
            ->join('estados', 'tickets.estado_id', '=', 'estados.id')
            ->select(
                DB::raw('YEAR(tickets.created_at) as año'), // Año de creación del ticket
                DB::raw('MONTH(tickets.created_at) as mes'), // Mes de creación del ticket
                'estados.nombre as estado',
                DB::raw('COUNT(tickets.id) as total')
            )
            ->groupBy(DB::raw('YEAR(tickets.created_at), MONTH(tickets.created_at), estados.nombre'))
            ->orderBy('año', 'asc')
            ->orderBy('mes', 'asc');

        // Filtros adicionales
        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $query->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        $result = $query->get();

        // Agrupar los datos por mes y año
        $labels = [];
        $estadoData = [];

        foreach ($result as $row) {
            $mesAnyo = "{$row->mes}-{$row->año}";
            if (!in_array($mesAnyo, $labels)) {
                $labels[] = $mesAnyo;
            }

            if (!isset($estadoData[$mesAnyo])) {
                $estadoData[$mesAnyo] = [];
            }

            $estadoData[$mesAnyo][$row->estado] = $row->total;
        }

        // Si faltan estados para algún mes, inicializarlos en 0
        $estados = collect($result)->pluck('estado')->unique()->toArray();
        foreach ($estadoData as &$data) {
            foreach ($estados as $estado) {
                if (!isset($data[$estado])) {
                    $data[$estado] = 0;
                }
            }
        }

        // Crear los datasets para el gráfico
        $datasets = [];
        foreach ($estados as $estado) {
            $datasets[] = [
                'name' => $estado,
                'data' => array_map(function ($mesData) use ($estado) {
                    return $mesData[$estado];
                }, $estadoData)
            ];
        }
        // dd($labels,$datasets );

        return ['labels' => $labels, 'datasets' => $datasets];
    }


    public function getChartDataCategoria()
    {
        $query = DB::table('tickets')
            ->join('categorias', 'tickets.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre as categoria', DB::raw('COUNT(tickets.id) as total'))
            ->groupBy('categorias.nombre')
            ->orderByDesc('total'); // Orden descendente

        if ($this->sociedadSeleccionada) {
            $query->where('tickets.sociedad_id', $this->sociedadSeleccionada);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tickets.created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->asignadoASeleccionado) {
            $query->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        $result = $query->get();
        // $labels = $result->pluck('categoria')->toArray();
        // $data = $result->pluck('total')->toArray();

        // return ['labels' => $labels, 'datasets' => [['data' => $data]]];
        // Transformar los resultados a un formato compatible con ApexCharts (x, y)
        $treemapData = $result->map(function ($item) {
            return [
                'x' => $item->categoria, // Nombre de la categoría
                'y' => (int) $item->total, // Valor total
            ];
        });
        // dd($treemapData);

        return $treemapData->toArray();
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

        if ($this->asignadoASeleccionado) {
            $query->where('tickets.asignado_a', $this->asignadoASeleccionado);
        }

        $promedioSatisfaccion = $query->avg('comentarios.calificacion');
        return round($promedioSatisfaccion, 2);
    }

    public function render()
    {
        return view('livewire.estadisticas.index');
    }
}
