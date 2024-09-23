<?php

namespace App\Http\Livewire\Graficas;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TicketSociedadChart extends Component
{
    public $chartData;
    public $sociedadSeleccionada;
    public $sociedadesDisponibles;

    public function mount()
    {
        // Obtener todas las sociedades disponibles
        $this->sociedadesDisponibles = DB::table('sociedades')->pluck('nombre', 'id')->toArray();

        // Inicialmente mostrar todos los tickets sin filtrar por sociedad
        $this->sociedadSeleccionada = null;

        // Cargar datos iniciales de la gráfica
        $this->chartData = $this->getChartData();
    }

    public function updatedSociedadSeleccionada()
    {
        // Cuando se selecciona una sociedad, recalculamos los datos y disparamos un evento para actualizar el gráfico
        $this->chartData = $this->getChartData();
        $this->dispatchBrowserEvent('chartDataUpdatedSociedad', ['chartData' => $this->chartData]);
    }

    public function getChartData()
    {
        // Consulta base con el join a sociedades y estados
        $query = DB::table('tickets')
            ->join('sociedades', 'tickets.sociedad_id', '=', 'sociedades.id')
            ->join('estados', 'tickets.estado_id', '=', 'estados.id');

        // Si hay una sociedad seleccionada, la aplicamos al filtro
        if ($this->sociedadSeleccionada) {
            $query->where('sociedades.id', $this->sociedadSeleccionada);
        }

        // Agrupamos los tickets por sociedad y estado
        $ticketsPorSociedadYEstado = $query->select('sociedades.nombre as sociedad', 'estados.nombre as estado', DB::raw('count(tickets.id) as total'))
            ->groupBy('sociedades.nombre', 'estados.nombre')
            ->get();

        // Verificar si hay datos
        if ($ticketsPorSociedadYEstado->isEmpty()) {
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

        // Preparar colores estáticos para las sociedades
        $sociedades = $ticketsPorSociedadYEstado->pluck('sociedad')->unique()->toArray();
        $estados = $ticketsPorSociedadYEstado->pluck('estado')->unique()->toArray();

        // Definir colores estáticos específicos para cada sociedad
        $staticColors = [
            'PANAL' => 'rgba(75, 192, 192, 0.5)',   // verde agua
            'LEVAPAN' => 'rgba(255, 99, 132, 0.5)', // rojo
            'LEVACOL' => 'rgba(54, 162, 235, 0.5)', // azul
            'TULUÁ' => 'rgba(153, 102, 255, 0.5)',  // morado
            'ECUADOR' => 'rgba(255, 159, 64, 0.5)', // naranja
            'REPÚBLICA DOMINICANA' => 'rgba(255, 205, 86, 0.5)', // amarillo
        ];

        $data = [];
        foreach ($sociedades as $sociedad) {
            $sociedadData = [];
            foreach ($estados as $estado) {
                $ticketCount = $ticketsPorSociedadYEstado->where('sociedad', $sociedad)->where('estado', $estado)->first();
                $sociedadData[] = $ticketCount ? $ticketCount->total : 0;
            }
            // Usar un color estático específico basado en el nombre de la sociedad
            $data[] = [
                'label' => $sociedad,
                'data' => $sociedadData,
                'backgroundColor' => $staticColors[$sociedad], // Usar color específico
            ];
        }

        return [
            'labels' => $estados,
            'datasets' => $data,
        ];
    }


    public function render()
    {
        return view('livewire.graficas.ticket-sociedad-chart');
    }
}
