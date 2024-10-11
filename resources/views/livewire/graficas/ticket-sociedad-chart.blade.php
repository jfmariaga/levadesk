<div>
    <div class="container-fluid">
        <!-- Filtros por Fechas -->
        {{-- <div class="row mb-4">
            <div class="col-md-3">
                <label for="startDate" class="form-label">Fecha de Inicio</label>
                <input type="date" id="startDate" wire:model="startDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="endDate" class="form-label">Fecha de Fin</label>
                <input type="date" id="endDate" wire:model="endDate" class="form-control">
            </div>
        </div> --}}

        <!-- Sección de Gráficas -->
        <div class="row g-4" >
            <div class="col-md-2">
                <div class="card small-box color-azul">
                    <div class="inner">
                        <h3>{{ $ticketsTotal }}</h3>
                        <p>Total tickets</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <!-- Gráfica de Tickets por Tipo de Solicitud (Donut) -->
            <div class="col-lg-12 col-xl-6 mb-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0 text-center">Tickets por Tipo de Solicitud</h5>
                    </div>
                    <div class="card-body">
                        <div id="tipoSolicitudChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>


            <!-- Gráfica de Tickets por Estado (Bar) -->
            <div class="col-lg-12 col-xl-6 mb-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0 text-center">Tickets por Estado</h5>
                    </div>
                    <div class="card-body">
                        <div id="estadoChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <!-- Gráfica de Tickets por Sociedad -->
            <div class="col-lg-12 col-xl-6 mb-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0 text-center">Tickets por Sociedad</h5>
                    </div>
                    <div class="card-body">
                        <div id="sociedadChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>
            <!-- Gráfica de Tickets por Categoría -->
            <div class="col-lg-12 col-xl-6 mb-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0 text-center">Tickets por Categoría</h5>
                    </div>
                    <div class="card-body">
                        <div id="categoriaChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Listado de Tickets -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card-body">
                    @livewire('home.tickets-home')
                </div>
            </div>
        </div>

        <!-- Scripts para las Gráficas -->
        <script>
            document.addEventListener('livewire:load', function() {
                var tipoSolicitudChart, estadoChart, sociedadChart, categoriaChart;

                function renderTipoSolicitudChart(data, labels) {
                    var options = {
                        chart: {
                            type: 'donut',
                            height: 250
                        },
                        series: data,
                        labels: labels,
                        colors: ['#4e73df', '#1cc88a', '#36b9cc'],
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
                    tipoSolicitudChart = new ApexCharts(document.querySelector("#tipoSolicitudChart"), options);
                    tipoSolicitudChart.render();
                }

                function renderEstadoChart(data, labels) {
                    var options = {
                        chart: {
                            type: 'bar',
                            height: 250
                        },
                        series: [{
                            data: data
                        }],
                        xaxis: {
                            categories: labels
                        },
                        colors: ['#f6c23e', '#e74a3b', '#858796'],
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
                    estadoChart = new ApexCharts(document.querySelector("#estadoChart"), options);
                    estadoChart.render();
                }

                function renderSociedadChart(data, labels) {
                    var options = {
                        chart: {
                            type: 'bar',
                            height: 250
                        },
                        series: [{
                            data: data
                        }],
                        xaxis: {
                            categories: labels
                        },
                        colors: ['#f1c40f', '#3498db', '#2ecc71'],
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
                    sociedadChart = new ApexCharts(document.querySelector("#sociedadChart"), options);
                    sociedadChart.render();
                }

                function renderCategoriaChart(data, labels) {
                    var options = {
                        chart: {
                            type: 'bar',
                            height: 250
                        },
                        series: [{
                            data: data
                        }],
                        xaxis: {
                            categories: labels
                        },
                        colors: ['#e74c3c', '#2ecc71', '#9b59b6'],
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
                    categoriaChart = new ApexCharts(document.querySelector("#categoriaChart"), options);
                    categoriaChart.render();
                }

                // Inicialización de las gráficas
                renderTipoSolicitudChart(@json($tipoSolicitudChartData['data']), @json($tipoSolicitudChartData['labels']));
                renderEstadoChart(@json($estadoChartData['data']), @json($estadoChartData['labels']));
                renderSociedadChart(@json($sociedadChartData['data']), @json($sociedadChartData['labels']));
                renderCategoriaChart(@json($categoriaChartData['data']), @json($categoriaChartData['labels']));
            });
        </script>

    </div>
