<?php

namespace App\Http\Livewire\Graficas;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TicketEstadoChart extends Component
{
    public $chartData;
    public $estadoSeleccionado;
    public $estadosDisponibles;

    public function mount()
    {
        // Obtener todos los estados disponibles
        $this->estadosDisponibles = DB::table('estados')->pluck('nombre', 'id')->toArray();

        // Inicialmente mostrar todos los estados
        $this->estadoSeleccionado = null;

        // Cargar datos iniciales de la gráfica
        $this->chartData = $this->getChartData();
    }

    public function updatedEstadoSeleccionado()
    {
        // Cuando se selecciona un estado, recalculamos los datos y disparamos un evento para actualizar el gráfico
        $this->chartData = $this->getChartData();
        $this->dispatchBrowserEvent('chartDataUpdated', ['chartData' => $this->chartData]);
    }

    public function getChartData()
    {
        $query = DB::table('tickets')
            ->join('estados', 'tickets.estado_id', '=', 'estados.id');

        // Si hay un estado seleccionado, lo aplicamos al filtro
        if ($this->estadoSeleccionado) {
            $query->where('estados.id', $this->estadoSeleccionado);
        }

        // Agrupamos los tickets por estado
        $ticketsPorEstado = $query->select('estados.nombre', DB::raw('count(tickets.id) as total'))
            ->groupBy('estados.nombre')
            ->get();

        // Preparar datos para Chart.js
        $labels = $ticketsPorEstado->pluck('nombre')->toArray();
        $data = $ticketsPorEstado->pluck('total')->toArray();

        // Si no hay datos, devolvemos un gráfico vacío
        if (empty($data)) {
            return [
                'labels' => ['Sin Datos'],
                'datasets' => [
                    [
                        'label' => 'No hay tickets para mostrar',
                        'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                        'borderColor' => 'rgba(255, 99, 132, 1)',
                        'data' => [0],
                    ]
                ],
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Tickets por Estado',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'data' => $data,
                ],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.graficas.ticket-estado-chart');
    }
}
