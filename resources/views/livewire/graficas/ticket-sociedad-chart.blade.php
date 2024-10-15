<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-2">
            <label for="sociedadSelect">Selecciona una Sociedad:</label>
            <select wire:model="sociedadSeleccionada" id="sociedadSelect" class="form-control shadow-sm">
                <option value="">Todas las Sociedades</option>
                @foreach ($sociedadesDisponibles as $id => $nombre)
                    <option value="{{ $id }}">{{ $nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="startDate">Fecha de Inicio:</label>
            <input type="date" wire:model="startDate" id="startDate" class="form-control shadow-sm">
        </div>
        <div class="col-md-2">
            <label for="endDate">Fecha de Fin:</label>
            <input type="date" wire:model="endDate" id="endDate" class="form-control shadow-sm">
        </div>
    </div>

    <!-- Tarjeta con gráfica radial de resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm p-3 mb-5 bg-white rounded">
                <div id="totalTicketsChart"></div>
            </div>
        </div>
        <div class="col-md-3 col-md-4 mb-4">
            <div class="card shadow-sm p-3 mb-5 bg-white rounded">
                <div class="card-header text-center">Tickets por Tipo de Solicitud</div>
                <div class="card-body">
                    <div id="tipoSolicitudChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 mb-4">
            <div class="card shadow-sm p-3 mb-5 bg-white rounded">
                <div class="card-header text-center">Tickets por Sociedad</div>
                <div class="card-body">
                    <div id="ticketSociedadChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">


        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow-sm p-3 mb-5 bg-white rounded">
                <div class="card-header text-center">Tickets por Estado</div>
                <div class="card-body">
                    <div id="ticketEstadoChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow-sm p-3 mb-5 bg-white rounded">
                <div class="card-header text-center">Tickets por Categoría</div>
                <div class="card-body">
                    <div id="ticketCategoriaChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card shadow-sm p-3 mb-5 bg-white rounded">
                <div class="card-header text-center">Volumen de Tickets</div>
                <div class="card-body">
                    <div id="volumenTicketsChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:load', function() {
            let charts = {};

            // Gráfico Radial para Total de Tickets
            function renderTotalTicketsChart(totalTickets) {
                let options = {
                    chart: {
                        height: 350,
                        type: 'radialBar',
                    },
                    series: [totalTickets],
                    plotOptions: {
                        radialBar: {
                            dataLabels: {
                                name: {
                                    fontSize: '18px',
                                },
                                value: {
                                    fontSize: '16px',
                                },
                                total: {
                                    show: true,
                                    label: 'Total Tickets',
                                    formatter: function () {
                                        return totalTickets;
                                    }
                                }
                            }
                        }
                    },
                    labels: ['Tickets']
                };

                if (charts["totalTicketsChart"]) {
                    charts["totalTicketsChart"].destroy(); // Destruir el gráfico anterior si existe
                }

                charts["totalTicketsChart"] = new ApexCharts(document.querySelector("#totalTicketsChart"), options);
                charts["totalTicketsChart"].render();
            }

            // Gráfico de Barras para Sociedad
            function renderBarChart(chartData, chartElementId) {
                let options = {
                    chart: {
                        type: 'bar',
                        height: 400,
                    },
                    series: chartData.datasets.map(dataset => ({
                        name: dataset.name,
                        data: dataset.data
                    })),
                    xaxis: {
                        categories: chartData.labels,
                        title: {
                            text: 'Sociedades',
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '45%',
                        },
                    },
                    colors: ['#4e73df', '#1cc88a', '#f6c23e'], // Colores más suaves y consistentes
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val;
                        }
                    },
                    legend: {
                        position: 'top',
                    }
                };

                if (charts[chartElementId]) {
                    charts[chartElementId].destroy(); // Destruir el gráfico anterior si existe
                }

                charts[chartElementId] = new ApexCharts(document.querySelector(`#${chartElementId}`), options);
                charts[chartElementId].render();
            }

            // Gráfico de Donut para Tipo de Solicitud
            function renderDonutChart(data, labels, chartElementId) {
                let options = {
                    chart: {
                        type: 'donut',
                        height: 250
                    },
                    series: data,
                    labels: labels,
                    colors: ['#4e73df', '#1cc88a', '#f6c23e'],
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 250
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                if (charts[chartElementId]) {
                    charts[chartElementId].destroy(); // Destruir el gráfico anterior si existe
                }

                charts[chartElementId] = new ApexCharts(document.querySelector(`#${chartElementId}`), options);
                charts[chartElementId].render();
            }

            // Escuchar el evento 'chartDataUpdated' para actualizar los gráficos
            window.addEventListener('chartDataUpdated', event => {
                const { chartData, chartElementId, chartType = 'bar', totalTickets } = event.detail;

                if (chartElementId === 'totalTicketsChart') {
                    renderTotalTicketsChart(totalTickets); // Actualizar el total de tickets
                } else if (chartType === 'donut') {
                    renderDonutChart(chartData.datasets[0].data, chartData.labels, chartElementId);
                } else {
                    renderBarChart(chartData, chartElementId);
                }
            });

            // Renderizar gráficos iniciales
            renderTotalTicketsChart(@json($totalTickets));
            renderBarChart(@json($chartDataSociedadEstado), 'ticketSociedadChart');
            renderDonutChart(@json($chartDataTipoSolicitud['datasets'][0]['data']), @json($chartDataTipoSolicitud['labels']), 'tipoSolicitudChart');
            renderBarChart(@json($chartDataEstado), 'ticketEstadoChart');
            renderBarChart(@json($chartDataCategoria), 'ticketCategoriaChart');
            renderBarChart(@json($chartDataVolumenTickets), 'volumenTicketsChart');
        });
    </script>
@endpush
