<div>
    <style>
        /* Estructura de la grilla */
        .angry-grid {
            display: grid;
            grid-template-rows: 1fr 1fr 1fr 1fr;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 5px;
            /* Sin espacio entre los elementos */
            height: 100%;
        }

        .tarjetas {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .tarjeta {
            color: #fff;
            background: linear-gradient(145deg, #4e73df, #224abe);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
            /* Se redujo el padding para hacerlas más pequeñas */
            border-radius: 15px;
            width: 23%;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            /* Se redujo el tamaño de la fuente */
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .tarjeta:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        .icono {
            font-size: 2rem;
            /* Se redujo el tamaño del icono */
            margin-bottom: 10px;
        }

        .tarjeta i {
            display: inline-block;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Diferentes colores para cada tarjeta */
        .tarjeta-1 {
            background: linear-gradient(145deg, #4e73df, #224abe);
            /* Azul */
        }

        .tarjeta-2 {
            background: linear-gradient(145deg, #1cc88a, #138f64);
            /* Verde */
        }

        .tarjeta-3 {
            background: linear-gradient(145deg, #f6c23e, #e0a30b);
            /* Amarillo */
        }

        .tarjeta-4 {
            background: linear-gradient(145deg, #f34646, #c93030);
            /* Rojo */
        }

        .tarjeta h5 {
            font-size: 1.2rem;
            font-weight: normal;
            margin-bottom: 10px;
        }

        .tarjeta p {
            font-size: 1.5rem;
            margin: 0;
        }


        /* Estilos para cada celda de la grilla */
        #item-0 {
            background-color: #fff;
            grid-row-start: 1;
            grid-column-start: 1;
            grid-row-end: 2;
            grid-column-end: 2;
        }

        #item-1 {
            background-color: #fff;
            grid-row-start: 1;
            grid-column-start: 2;
            grid-row-end: 2;
            grid-column-end: 3;
        }

        #item-2 {
            background-color: #fff;
            grid-row-start: 1;
            grid-column-start: 3;
            grid-row-end: 2;
            grid-column-end: 4;
        }

        #item-3 {
            background-color: #fff;
            grid-row-start: 1;
            grid-column-start: 4;
            grid-row-end: 2;
            grid-column-end: 5;
        }

        #item-4 {
            background-color: #fff;
            grid-row-start: 2;
            grid-column-start: 1;
            grid-row-end: 3;
            grid-column-end: 3;
        }

        #item-5 {
            background-color: #fff;
            grid-row-start: 2;
            grid-column-start: 3;
            grid-row-end: 3;
            grid-column-end: 5;
        }

        #item-6 {
            background-color: #fff;
            grid-row-start: 3;
            grid-column-start: 1;
            grid-row-end: 4;
            grid-column-end: 3;
        }

        #item-7 {
            background-color: #fff;
            grid-row-start: 3;
            grid-column-start: 3;
            grid-row-end: 4;
            grid-column-end: 5;
        }

        #item-8 {
            background-color: #fff;
            grid-row-start: 4;
            grid-column-start: 1;
            grid-row-end: 5;
            grid-column-end: 5;
        }

        /* Carrusel */
        .carousel-inner {
            border-radius: 10px;
            overflow: hidden;
        }

        .carousel-item {
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        /* Card Styling */
        .carousel-item .inner {
            border-radius: 10px;
        }

        .carousel-item .p-4 {
            padding: 2rem;
        }

        /* Añadir sombras y bordes redondeados */
        .carousel-item .shadow-lg {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        /* Botones de control */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
        }

        .carousel-indicators li {
            background-color: #007bff;
        }

        .carousel-indicators .active {
            background-color: #0056b3;
        }

        /* Diseño para la estrella de calificación */
        .text-warning i {
            color: #ffcc00;
        }
    </style>

    <div class="row mb-2">
        <div class="col-md-3">
            <label for="startDate">Fecha de Inicio:</label>
            <input type="date" wire:model="startDate" id="startDate" class="form-control shadow-sm">
        </div>

        <div class="col-md-3">
            <label for="endDate">Fecha de Fin:</label>
            <input type="date" wire:model="endDate" id="endDate" class="form-control shadow-sm">
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 mb-4 mt-4">
            <div class="inner">
                <div id="ticketsSolucionadosCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Contenido del carrusel -->
                    <div class="carousel-inner">
                        @foreach ($ticketsSolucionados as $index => $ticket)
                            @php
                                $comentarioCalificado = $ticket->comentarios()->whereNotNull('calificacion')->first();
                            @endphp
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="d-flex justify-content-center align-items-center p-4 bg-white rounded-lg shadow-lg text-dark"
                                    style="height: 180px;">
                                    <div class="text-center w-100">
                                        <!-- Nomenclatura -->
                                        <h5 class="badge badge-info mb-2">{{ $ticket->nomenclatura }}</h5>

                                        <!-- Nombre del usuario -->
                                        <p class="m-0 mb-2 text-muted" style="font-size: 1.1em;">
                                            {{ $ticket->usuario->name }}</p>

                                        <!-- Comentario Calificado -->
                                        <p class="text-muted">
                                            <i>
                                                @isset($comentarioCalificado)
                                                    {{ $comentarioCalificado->comentario_calificacion : 'Sin comentario'}}
                                                @else
                                                    Sin comentario
                                                @endisset
                                            </i>
                                        </p>

                                        <!-- Calificación -->
                                        <p class="text-warning mt-2">
                                            <i class="fas fa-star"></i>
                                            {{ $comentarioCalificado->calificacion ?? 0 }}/5
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Controles del carrusel -->
                    <a class="carousel-control-prev" href="#ticketsSolucionadosCarousel" role="button"
                        data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#ticketsSolucionadosCarousel" role="button"
                        data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Siguiente</span>
                    </a>

                    <!-- Indicadores -->
                    <ol class="carousel-indicators">
                        @foreach ($ticketsSolucionados as $index => $ticket)
                            <li data-target="#ticketsSolucionadosCarousel" data-slide-to="{{ $index }}"
                                class="{{ $index == 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>

    </div>


    <div class="tarjetas">
        <div class="tarjeta tarjeta-1">
            <div class="icono">
                <i class="fas fa-clock"></i>
            </div>
            <h5>Tiempo de Respuesta Inicial Promedio</h5>
            <p>{{ $respuestaInicialPromedio }} Horas</p>
        </div>
        <div class="tarjeta tarjeta-2">
            <div class="icono">
                <i class="fas fa-stopwatch"></i>
            </div>
            <h5>Tiempo de Resolución Promedio</h5>
            <p>{{ $tiempoResolucionPromedio }} Horas</p>
        </div>
        <div class="tarjeta tarjeta-3">
            <div class="icono">
                <i class="fas fa-chart-line"></i>
            </div>
            <h5>Tasa de Escalamiento</h5>
            <p>{{ $tasaEscalamiento }}%</p>
        </div>
        <div class="tarjeta tarjeta-4">
            <div class="icono">
                <i class="fas fa-redo"></i>
            </div>
            <h5>Tasa de Reapertura</h5>
            <p>{{ $tasaReapertura }}%</p>
        </div>
    </div>

    <div class="angry-grid">
        <div id="item-0">
            <div class="card-header text-center">Total tickets</div>
            <div id="totalTicketsChart" class="small-chart"></div>
        </div>
        <div id="item-1">
            <div class="card-header text-center">Satisfacción de usuario</div>
            <div id="satisfaccionUsuarioChart" class="small-chart"></div>
        </div>
        <div id="item-2">
            <div class="card-header text-center">Cumplimiento de ANS de inicial</div>
            <div id="cumplimientoANSInicialChart" class="small-chart"></div>
        </div>
        <div id="item-3">
            <div class="card-header text-center">Cumplimiento de ANS de solución</div>
            <div id="cumplimientoANSChart" class="small-chart"></div>
        </div>
        <div id="item-4">
            <div class="card-header text-center">Tickets por Tipo de Solicitud</div>
            <div id="tipoSolicitudChart" class="large-chart"></div>
        </div>
        <div id="item-5">
            <div class="card-header text-center">Tickets por Sociedad</div>
            <div id="ticketSociedadChart" class="large-chart"></div>
        </div>
        <div id="item-6">
            <div class="card-header text-center">Tickets por Estado</div>
            <div id="ticketEstadoChart" class="large-chart"></div>
        </div>
        <div id="item-7">
            <div class="card-header text-center">Tickets por Categoría</div>
            <div id="ticketCategoriaChart" class="large-chart"></div>
        </div>
        <div id="item-8">
            <div class="card-header text-center">Volumen de Tickets</div>
            <div id="volumenTicketsChart" class="large-chart"></div>
        </div>
    </div>
    {{-- <div class="row justify-content-center">
        <!-- Tarjeta con carrusel -->
        <div class="col-lg-10 col-md-12 col-sm-12 mb-2 mt-2">
            <div class="inner">
                <div id="ticketsSolucionadosCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Contenido del carrusel -->
                    <div class="carousel-inner">
                        @foreach ($ticketsSolucionados as $index => $ticket)
                            @php
                                $comentarioCalificado = $ticket->comentarios()->whereNotNull('calificacion')->first();
                            @endphp
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="item-container p-3 bg-white rounded text-dark">
                                    <h5 class="badge badge-info">{{ $ticket->nomenclatura }}</h5>
                                    <p class="m-0">{{ $ticket->usuario->name }}</p>
                                    <p><i>{{ $comentarioCalificado->comentario_calificacion ? $comentarioCalificado->comentario_calificacion : 'Sin comentarios' }}</i>
                                    </p>
                                    <p class="text-warning">
                                        <i class="fas fa-star"></i>
                                        {{ $comentarioCalificado->calificacion ?? 0 }}/5
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Controles del carrusel -->
                    <a class="carousel-control-prev" href="#ticketsSolucionadosCarousel" role="button"
                        data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#ticketsSolucionadosCarousel" role="button"
                        data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Siguiente</span>
                    </a>

                    <!-- Indicadores -->
                    <ol class="carousel-indicators">
                        @foreach ($ticketsSolucionados as $index => $ticket)
                            <li data-target="#ticketsSolucionadosCarousel" data-slide-to="{{ $index }}"
                                class="{{ $index == 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div> --}}

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                let charts = {};

                function renderTotalTicketsChart(totalTickets) {
                    let options = {
                        chart: {
                            height: 250,
                            type: 'radialBar',
                        },
                        series: [totalTickets],
                        colors: ['#4e73df'], // Azul
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        fontSize: '14px'
                                    },
                                    value: {
                                        fontSize: '14px'
                                    },
                                    total: {
                                        show: true,
                                        label: '',
                                        formatter: function() {
                                            return totalTickets;
                                        }
                                    }
                                }
                            }
                        },
                        labels: ['Tickets']
                    };

                    if (charts["totalTicketsChart"]) {
                        charts["totalTicketsChart"].destroy();
                    }

                    charts["totalTicketsChart"] = new ApexCharts(document.querySelector("#totalTicketsChart"), options);
                    charts["totalTicketsChart"].render();
                }

                function renderBarChart(chartData, chartElementId) {
                    if (!chartData || !chartData.datasets) return;

                    let isHorizontal = chartElementId === 'ticketSociedadChart';

                    // Invertir el orden de las categorías y los datos
                    chartData.labels.reverse();
                    chartData.datasets.forEach(dataset => {
                        dataset.data.reverse();
                    });

                    let options = {
                        chart: {
                            type: 'bar',
                            height: 300
                        },
                        series: chartData.datasets.map(dataset => ({
                            name: dataset.name,
                            data: dataset.data
                        })),
                        xaxis: {
                            categories: chartData.labels,
                            title: {
                                text: ''
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: isHorizontal, // Ajuste para gráfico horizontal si es 'ticketSociedadChart'
                                columnWidth: '45%'
                            }
                        },
                        colors: ['#4e73df', '#1cc88a', '#f6c23e', '#f34646'], // Azul, Verde, Amarillo, Rojo
                        dataLabels: {
                            enabled: true,
                            formatter: function(val) {
                                return val;
                            }
                        },
                        legend: {
                            position: 'top'
                        }
                    };

                    if (charts[chartElementId]) {
                        charts[chartElementId].destroy();
                    }

                    charts[chartElementId] = new ApexCharts(document.querySelector(`#${chartElementId}`), options);
                    charts[chartElementId].render();
                }

                function renderAreaChart(chartData, chartElementId) {
                    if (!chartData || !chartData.datasets) return;

                    let options = {
                        chart: {
                            type: 'area',
                            height: 300
                        },
                        series: chartData.datasets.map(dataset => ({
                            name: dataset.name,
                            data: dataset.data
                        })),
                        xaxis: {
                            categories: chartData.labels,
                            title: {
                                text: ''
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false, // Ajuste para gráfico horizontal si es 'ticketSociedadChart'
                                columnWidth: '45%'
                            }
                        },
                        colors: ['#4e73df', '#1cc88a', '#f6c23e', '#f34646'], // Azul, Verde, Amarillo, Rojo
                        dataLabels: {
                            enabled: true,
                            formatter: function(val) {
                                return val;
                            }
                        },
                        legend: {
                            position: 'top'
                        }
                    };

                    if (charts[chartElementId]) {
                        charts[chartElementId].destroy();
                    }

                    charts[chartElementId] = new ApexCharts(document.querySelector(`#${chartElementId}`), options);
                    charts[chartElementId].render();
                }



                // Gráfico Donut para Tipo de Solicitud
                function renderDonutChart(data, labels, chartElementId) {
                    let options = {
                        chart: {
                            type: 'donut',
                            height: 350
                        },
                        series: data,
                        labels: labels,
                        colors: ['#4e73df', '#1cc88a', '#f6c23e', '#f34646'], // Azul, Verde, Amarillo, Rojo
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 350
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    if (charts[chartElementId]) {
                        charts[chartElementId].destroy();
                    }

                    charts[chartElementId] = new ApexCharts(document.querySelector(`#${chartElementId}`), options);
                    charts[chartElementId].render();
                }

                // Gráfico Radial para el Promedio de Satisfacción del Usuario
                function renderSatisfaccionUsuarioChart(promedioSatisfaccion) {
                    let percentage = (promedioSatisfaccion / 5) * 100;

                    let options = {
                        chart: {
                            height: 250,
                            type: 'radialBar',
                        },
                        series: [percentage],
                        colors: ['#1cc88a'], // Verde
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        fontSize: '14px'
                                    },
                                    value: {
                                        fontSize: '14px'
                                    },
                                    total: {
                                        show: true,
                                        label: '',
                                        formatter: function() {
                                            return `${promedioSatisfaccion}/5`;
                                        }
                                    }
                                }
                            }
                        },
                        labels: ['Satisfacción']
                    };

                    if (charts["satisfaccionUsuarioChart"]) {
                        charts["satisfaccionUsuarioChart"].destroy();
                    }

                    charts["satisfaccionUsuarioChart"] = new ApexCharts(document.querySelector(
                        "#satisfaccionUsuarioChart"), options);
                    charts["satisfaccionUsuarioChart"].render();
                }

                // Gráfico Radial para el Tiempo de Respuesta Inicial Promedio
                function renderRespuestaInicialPromedioChart(promedioRespuesta) {
                    let options = {
                        chart: {
                            height: 250,
                            type: 'radialBar',
                        },
                        series: [promedioRespuesta],
                        colors: ['#f6c23e'], // Amarillo
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        fontSize: '14px'
                                    },
                                    value: {
                                        fontSize: '14px'
                                    },
                                    total: {
                                        show: true,
                                        label: 'Promedio de Respuesta',
                                        formatter: function() {
                                            return `${promedioRespuesta} minutos`;
                                        }
                                    }
                                }
                            }
                        },
                        labels: ['Respuesta Inicial']
                    };

                    if (charts["respuestaInicialPromedioChart"]) {
                        charts["respuestaInicialPromedioChart"].destroy();
                    }

                    charts["respuestaInicialPromedioChart"] = new ApexCharts(document.querySelector(
                        "#respuestaInicialPromedioChart"), options);
                    charts["respuestaInicialPromedioChart"].render();
                }

                // Gráfico Radial para el Tiempo de Resolución Promedio
                function renderResolucionPromedioChart(promedioResolucion) {
                    let options = {
                        chart: {
                            height: 250,
                            type: 'radialBar',
                        },
                        series: [promedioResolucion],
                        colors: ['#4e73df'], // Azul
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        fontSize: '14px'
                                    },
                                    value: {
                                        fontSize: '14px'
                                    },
                                    total: {
                                        show: true,
                                        label: 'Tiempo de Resolución',
                                        formatter: function() {
                                            return `${promedioResolucion} minutos`;
                                        }
                                    }
                                }
                            }
                        },
                        labels: ['Tiempo de Resolución']
                    };

                    if (charts["resolucionPromedioChart"]) {
                        charts["resolucionPromedioChart"].destroy();
                    }

                    charts["resolucionPromedioChart"] = new ApexCharts(document.querySelector(
                        "#resolucionPromedioChart"), options);
                    charts["resolucionPromedioChart"].render();
                }

                // Gráfico Radial para Tasa de Escalamiento
                function renderTasaEscalamientoChart(tasaEscalamiento) {
                    let options = {
                        chart: {
                            height: 250,
                            type: 'radialBar',
                        },
                        series: [tasaEscalamiento],
                        colors: ['#f34646'], // Rojo
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: '70%',
                                }
                            }
                        },
                        labels: ['Tasa de Escalamiento']
                    };

                    if (charts["tasaEscalamientoChart"]) {
                        charts["tasaEscalamientoChart"].destroy();
                    }

                    charts["tasaEscalamientoChart"] = new ApexCharts(document.querySelector("#tasaEscalamientoChart"),
                        options);
                    charts["tasaEscalamientoChart"].render();
                }

                // Gráfico Radial para Tasa de Reapertura
                function renderTasaReaperturaChart(tasaReapertura) {
                    let options = {
                        chart: {
                            height: 250,
                            type: 'radialBar',
                        },
                        series: [tasaReapertura],
                        colors: ['#f34646'], // Rojo
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: '70%',
                                }
                            }
                        },
                        labels: ['Tasa de Reapertura']
                    };

                    if (charts["tasaReaperturaChart"]) {
                        charts["tasaReaperturaChart"].destroy();
                    }

                    charts["tasaReaperturaChart"] = new ApexCharts(document.querySelector("#tasaReaperturaChart"),
                        options);
                    charts["tasaReaperturaChart"].render();
                }

                // Gráfico Treemap
                function renderTreemapChart(data, treemapData) {
                    let options = {
                        chart: {
                            height: 400,
                            type: 'treemap'
                        },
                        series: [{
                            data: data
                        }],
                        colors: ['#4e73df', '#1cc88a', '#f6c23e', '#f34646'],
                        labels: {
                            show: true
                        }
                    };

                    if (charts["ticketCategoriaChart"]) {
                        charts["ticketCategoriaChart"].destroy();
                    }

                    charts["ticketCategoriaChart"] = new ApexCharts(document.querySelector("#ticketCategoriaChart"),
                        options);
                    charts["ticketCategoriaChart"].render();
                }

                // Escuchar el evento de actualización de datos para actualizar los gráficos
                window.addEventListener('chartDataUpdated', event => {
                    const {
                        chartData,
                        chartElementId,
                        chartType = 'bar',
                        totalTickets,
                        promedioSatisfaccion,
                        respuestaInicialPromedio,
                        resolucionPromedio,
                        tasaEscalamiento,
                        tasaReapertura,
                        treemapData

                    } = event.detail;

                    if (chartElementId === 'totalTicketsChart') {
                        renderTotalTicketsChart(totalTickets);
                    } else if (chartElementId === 'satisfaccionUsuarioChart') {
                        renderSatisfaccionUsuarioChart(promedioSatisfaccion);
                    } else if (chartElementId === 'respuestaInicialPromedioChart') {
                        renderRespuestaInicialPromedioChart(respuestaInicialPromedio);
                    } else if (chartElementId === 'resolucionPromedioChart') {
                        renderResolucionPromedioChart(resolucionPromedio);
                    } else if (chartElementId === 'tasaEscalamientoChart') {
                        renderTasaEscalamientoChart(tasaEscalamiento);
                    } else if (chartElementId === 'tasaReaperturaChart') {
                        renderTasaReaperturaChart(tasaReapertura);
                    } else if (chartType === 'donut') {
                        renderDonutChart(chartData.datasets[0].data, chartData.labels, chartElementId);
                    } else if (chartType === 'treemap') {
                        renderTreemapChart(treemapData);
                    } else if (chartElementId === 'cumplimientoANSChart') {
                        renderDonutChart(chartData.datasets[0].data, chartData.labels, chartElementId);
                    } else if (chartElementId === 'cumplimientoANSInicialChart') {
                        renderDonutChart(chartData.datasets[0].data, chartData.labels, chartElementId);
                    } else if (chartElementId === 'volumenTicketsChart') {
                        renderAreaChart(chartData, chartElementId);
                    } else {
                        renderBarChart(chartData, chartElementId);
                    }
                });

                // Renderizar los gráficos iniciales
                renderTotalTicketsChart(@json($totalTickets));
                renderBarChart(@json($chartDataSociedadEstado), 'ticketSociedadChart');
                renderDonutChart(@json($chartDataTipoSolicitud['datasets'][0]['data']), @json($chartDataTipoSolicitud['labels']), 'tipoSolicitudChart');
                renderBarChart(@json($chartDataEstado), 'ticketEstadoChart');
                renderTreemapChart(@json($chartDataCategoria), 'ticketCategoriaChart');
                renderAreaChart(@json($chartDataVolumenTickets), 'volumenTicketsChart');
                renderSatisfaccionUsuarioChart(@json($chartDataSatisfaccionUsuario));
                renderRespuestaInicialPromedioChart(@json($respuestaInicialPromedio));
                renderResolucionPromedioChart(@json($tiempoResolucionPromedio));
                renderTasaEscalamientoChart(@json($tasaEscalamiento));
                renderTasaReaperturaChart(@json($tasaReapertura));
                renderDonutChart(@json($chartDataCumplimientoANS['datasets'][0]['data']), @json($chartDataCumplimientoANS['labels']), 'cumplimientoANSChart');
                renderDonutChart(@json($chartDataCumplimientoANSInicial['datasets'][0]['data']), @json($chartDataCumplimientoANSInicial['labels']),
                    'cumplimientoANSInicialChart');
            });
        </script>
    @endpush
</div>
