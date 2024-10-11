<div x-data="dataalpine">
    @push('js_extra')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Estadísticas de Tickets</h5>

            <div class="row mb-3">
                <div class="col">
                    <label for="desde">Desde:</label>
                    <input type="date" id="desde" wire:model="desde" class="form-control">
                </div>
                <div class="col">
                    <label for="hasta">Hasta:</label>
                    <input type="date" id="hasta" wire:model="hasta" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div id="chart-sociedad" style="height: 300px;"></div>
                </div>
                <div class="col-md-6">
                    <div id="chart-tipo-solicitud" style="height: 300px;"></div>
                </div>
                <div class="col-md-6">
                    <div id="chart-categoria" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            Alpine.data('dataalpine', () => ({
                init() {
                    this.loadCharts();
                },

                loadCharts() {
                    this.loadSociedadChart();
                    this.loadTipoSolicitudChart();
                    this.loadCategoriaChart();
                },

                loadSociedadChart() {
                    const data = @json($sociedadData);
                    const series = data.map(item => item.total_tickets);
                    const labels = data.map(item => item.sociedad_nombre);

                    const options = {
                        series: series,
                        chart: {
                            type: 'pie',
                            height: 300
                        },
                        labels: labels,
                    };

                    const chart = new ApexCharts(document.querySelector("#chart-sociedad"), options);
                    chart.render();
                },

                loadTipoSolicitudChart() {
                    const data = @json($tipoSolicitudData);
                    const series = data.map(item => item.total_tickets);
                    const labels = data.map(item => item.tipo_solicitud_nombre);

                    const options = {
                        series: series,
                        chart: {
                            type: 'bar',
                            height: 300
                        },
                        xaxis: {
                            categories: labels,
                        },
                    };

                    const chart = new ApexCharts(document.querySelector("#chart-tipo-solicitud"), options);
                    chart.render();
                },

                loadCategoriaChart() {
                    const data = @json($categoriaData);
                    const series = data.map(item => item.total_tickets);
                    const labels = data.map(item => item.categoria_nombre);

                    const options = {
                        series: series,
                        chart: {
                            type: 'line',
                            height: 300
                        },
                        xaxis: {
                            categories: labels,
                        },
                    };

                    const chart = new ApexCharts(document.querySelector("#chart-categoria"), options);
                    chart.render();
                }
            }));
        </script>
    @endsection
</div>
