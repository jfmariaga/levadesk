<div>
    <div class="mb-3">
        <label for="estadoSelect">Selecciona un Estado:</label>
        <select wire:model="estadoSeleccionado" id="estadoSelect" class="form-control">
            <option value="">Todos los Estados</option>
            @foreach($estadosDisponibles as $id => $nombre)
                <option value="{{ $id }}">{{ $nombre }}</option>
            @endforeach
        </select>
    </div>

    <!-- Contenedor para el gráfico de estados -->
    <div style="width: 100%; height: 400px;">
        <canvas id="ticketEstadoChart"></canvas>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            let ticketEstadoChart = null;

            function renderEstadoChart(chartData) {
                const ctx = document.getElementById('ticketEstadoChart').getContext('2d');

                // Destruir el gráfico si ya existe
                if (ticketEstadoChart) {
                    ticketEstadoChart.destroy();
                }

                // Crear el nuevo gráfico de estados
                ticketEstadoChart = new Chart(ctx, {
                    type: 'bar',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0  // Forzar números enteros en el eje Y
                                }
                            },
                            x: {
                                grid: {
                                    display: false  // Quitar las líneas verticales en el eje X
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.raw + " tickets";
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Renderizar la gráfica de estados inicialmente
            renderEstadoChart(@json($chartData));

            // Escuchar los eventos de Livewire para cuando los datos cambian en la gráfica de estados
            window.addEventListener('chartDataUpdated', event => {
                renderEstadoChart(event.detail.chartData);
            });
        });
    </script>
</div>
