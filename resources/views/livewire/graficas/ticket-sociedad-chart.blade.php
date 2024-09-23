<div>
    <div class="mb-3">
        <label for="sociedadSelect">Selecciona una Sociedad:</label>
        <select wire:model="sociedadSeleccionada" id="sociedadSelect" class="form-control">
            <option value="">Todas las Sociedades</option>
            @foreach ($sociedadesDisponibles as $id => $nombre)
                <option value="{{ $id }}">{{ $nombre }}</option>
            @endforeach
        </select>
    </div>

    <!-- Contenedor para el gráfico -->
    <div style="width: 100%; height: 400px;">
        <canvas id="ticketSociedadChart"></canvas>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            let ticketSociedadChart = null;

            function renderChart(chartData) {
                const ctx = document.getElementById('ticketSociedadChart').getContext('2d');

                // Destruir el gráfico si ya existe
                if (ticketSociedadChart) {
                    ticketSociedadChart.destroy();
                }

                // Crear el nuevo gráfico
                ticketSociedadChart = new Chart(ctx, {
                    type: 'bar',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0 // Forzar números enteros en el eje Y
                                }
                            },
                            x: {
                                grid: {
                                    display: false // Quitar las líneas verticales en el eje X
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
                }); // Aquí faltaba el cierre de este paréntesis
            }

            // Renderizar la gráfica inicialmente
            renderChart(@json($chartData));

            // Escuchar los eventos de Livewire para cuando los datos cambian
            window.addEventListener('chartDataUpdatedSociedad', event => {
                renderChart(event.detail.chartData);
            });
        });
    </script>

</div>
