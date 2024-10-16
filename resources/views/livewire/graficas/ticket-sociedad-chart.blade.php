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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
            width: 23%;
            /* Para que se acomoden 4 tarjetas en la fila */
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            position: relative;
        }

        /* Colores basados en el gráfico Donut */
        .tarjeta-1 {
            background: #4e73df;
            /* Azul */
        }

        .tarjeta-2 {
            background: #1cc88a;
            /* Verde */
        }

        .tarjeta-3 {
            background: #f6c23e;
            /* Amarillo */
        }

        .tarjeta-4 {
            background: #4e73df;
            /* Se repite el Azul */
        }

        /* Ajuste del texto dentro de las tarjetas */
        .tarjeta h5 {
            font-size: 1.2rem;
            font-weight: normal;
            margin-bottom: 5px;
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
    </style>

    <div class="row mb-2">
        <div class="col-md-3">
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

    <!-- Tarjetas -->
    <div class="tarjetas">
        <div class="tarjeta tarjeta-1">
            <h5>Tiempo de Resolución Promedio</h5>
            <p>{{ $tiempoResolucionPromedio }} minutos</p>
        </div>
        <div class="tarjeta tarjeta-2">
            <h5>Tiempo de Respuesta Inicial Promedio</h5>
            <p>{{ $respuestaInicialPromedio }} minutos</p>
        </div>
        <div class="tarjeta tarjeta-3">
            <h5>Espacio disponible</h5>
            {{-- <p>{{ $tasaEscalamiento }}%</p> --}}
        </div>
        <div class="tarjeta tarjeta-4">
            <h5>Espacio disponible</h5>
            {{-- <p>{{ $tasaReapertura }}%</p> --}}
        </div>
    </div>



    <div class="angry-grid">
        <div id="item-0">
            <div id="totalTicketsChart" class="small-chart"></div>
        </div>
        <div id="item-1">
            <div id="satisfaccionUsuarioChart" class="small-chart"></div>
        </div>
        <div id="item-2">
            <div id="tasaEscalamientoChart" class="small-chart"></div>
        </div>
        <div id="item-3">
            <div id="tasaReaperturaChart" class="small-chart"></div>
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

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('livewire:load', function() {
                let charts = {};

                // Gráfico Radial para Total de Tickets
                function renderTotalTicketsChart(totalTickets) {
                    let options = {
                        chart: {
                            height: 250,
                            type: 'radialBar',
                        },
                        series: [totalTickets],
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
                                        label: 'Total Tickets',
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

                // Gráfico de Barras para Sociedad
                function renderBarChart(chartData, chartElementId) {
                    if (!chartData || !chartData.datasets) return;

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
                                horizontal: false,
                                columnWidth: '45%'
                            }
                        },
                        colors: ['#4e73df', '#1cc88a', '#f6c23e'],
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
                        colors: ['#4e73df', '#1cc88a', '#f6c23e'],
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
                                        label: 'Satisfacción de usuario',
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
                        tasaReapertura
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
                    } else {
                        renderBarChart(chartData, chartElementId);
                    }
                });

                // Renderizar los gráficos iniciales
                renderTotalTicketsChart(@json($totalTickets));
                renderBarChart(@json($chartDataSociedadEstado), 'ticketSociedadChart');
                renderDonutChart(@json($chartDataTipoSolicitud['datasets'][0]['data']), @json($chartDataTipoSolicitud['labels']), 'tipoSolicitudChart');
                renderBarChart(@json($chartDataEstado), 'ticketEstadoChart');
                renderBarChart(@json($chartDataCategoria), 'ticketCategoriaChart');
                renderBarChart(@json($chartDataVolumenTickets), 'volumenTicketsChart');
                renderSatisfaccionUsuarioChart(@json($chartDataSatisfaccionUsuario));
                renderRespuestaInicialPromedioChart(@json($respuestaInicialPromedio));
                renderResolucionPromedioChart(@json($tiempoResolucionPromedio));
                renderTasaEscalamientoChart(@json($tasaEscalamiento));
                renderTasaReaperturaChart(@json($tasaReapertura));
            });
        </script>
    @endpush
</div>
