<div>
    <style>
        .table-responsive {
            overflow-x: auto;
            overflow-y: auto;
        }

        .table thead th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .loading-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.7);
            z-index: 50;
        }

        .loading-overlay.d-none {
            display: none !important;
        }

        .loading-overlay .loader {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-weight: 600;
            color: #4b5563;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: .75rem 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .table-wrapper-relative {
            position: relative;
        }

        .dt-buttons {
            display: none !important;
        }

        td.dt-control {
            cursor: pointer;
        }

        tr.shown td.dt-control:before {
            content: "▼ ";
        }

        td.dt-control:before {
            content: "► ";
            font-weight: bold;
            margin-right: 5px;
        }
    </style>

    <!-- TARJETAS -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-white" style="background:#3b82f6; border-radius:12px;">
                <div class="card-body text-center">
                    <i class="fas fa-truck fa-2x mb-2"></i>
                    <h6 class="mb-1">Tareas con Transporte</h6>
                    <h4><strong>{{ $totalTransportes }}</strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-white" style="background:#10b981; border-radius:12px;">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h6 class="mb-1">Completadas</h6>
                    <h4><strong>{{ $transportesCompletadas }}</strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-white" style="background:#f59e0b; border-radius:12px;">
                <div class="card-body text-center">
                    <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                    <h6 class="mb-1">Pendientes / En curso</h6>
                    <h4><strong>{{ $transportesPendientes }}</strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-white" style="background:#ef4444; border-radius:12px;">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h6 class="mb-1">Rechazados</h6>
                    <h4><strong>{{ $transportesRechazados }}</strong></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA -->
    <div class="col-lg-12 col-md-12 mb-3">
        <div class="card">
            <div class="card-header"><h5>Transportes</h5></div>
            <div class="card-body">
                <div wire:ignore class="card-body card-dashboard">
                    <div class="table-responsive table-wrapper-relative">

                        <!-- overlay -->
                        <div id="loadingOverlay" class="loading-overlay d-none">
                            <div class="loader">
                                <div class="spinner-border" role="status" aria-hidden="true"></div>
                                <span>Aplicando filtros, cargando datos…</span>
                            </div>
                        </div>

                        <!-- filtros -->
                        <div class="row mb-2">
                            <div class="col-md-12 d-flex align-items-center">
                                <input type="text" class="datepicker form-control" id="fecha_desde"
                                    value="{{ $fecha_desde }}" style="width:150px;">
                                <span class="mx-2">a</span>
                                <input type="text" class="datepicker form-control mr-2" id="fecha_hasta"
                                    value="{{ $fecha_hasta }}" style="width:150px;">

                                <select wire:ignore id="selectedSociedad" class="select2 mx-1">
                                    <option value="">Selecciona un sociedad</option>
                                    @foreach ($sociedades as $s)
                                        <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                                    @endforeach
                                </select>

                                <select wire:ignore id="SelectedEstado" class="select2 mx-1">
                                    <option value="">Selecciona un estado</option>
                                    @foreach ($estados as $e)
                                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                    @endforeach
                                </select>

                                <select wire:ignore id="selectedUsuario" class="select2 mx-1">
                                    <option value="">Seleccionar Usuario</option>
                                    @foreach ($usuarios as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>

                                <select wire:ignore id="selectedAgente" class="select2 mx-1">
                                    <option value="">Seleccionar Agente</option>
                                    @foreach ($agentes as $a)
                                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                                    @endforeach
                                </select>

                                <button id="btnFiltrar" class="btn btn-light mx-2" wire:click="cargarDatos()" style="height:40px;">
                                    <i class="fas fa-filter"></i>
                                </button>

                                <a href="javascript:exportTabla('excel')" class="btn btn-default text-success mx-1 shadow">
                                    <i class="far fa-file-excel"></i>
                                </a>
                                <a href="javascript:exportTabla('pdf')" class="btn btn-default text-danger mx-1 shadow">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>

                        <!-- tabla -->
                        <table class="table table-striped tabla_transportes d-none" style="width:100%;">
                            <thead>
                                <tr>
                                    <th></th> <!-- flechita -->
                                    <th>Fecha</th>
                                    <th>Ticket</th>
                                    <th>Prioridad</th>
                                    <th>Sociedad</th>
                                    <th>País</th>
                                    <th>Estado del Ticket</th>
                                    <th>Transportes</th>
                                    <th>Agente</th>
                                    <th>Acc</th>
                                </tr>
                            </thead>
                            <tbody id="content_tabla_transportes"></tbody>
                        </table>
                    </div>

                    <div class="margin_20 loading_p">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        let dt = null;
        let rowsBuffered = 0;

        document.addEventListener('livewire:load', function() {
            @this.cargarDatos();
            $('.select2').select2();
            $('#fecha_desde').pickadate({ format: 'yyyy-mm-dd' });
            $('#fecha_hasta').pickadate({ format: 'yyyy-mm-dd' });

            $('#fecha_desde').on('change', function() { @this.set('fecha_desde', this.value); });
            $('#fecha_hasta').on('change', function() { @this.set('fecha_hasta', this.value); });
            $('#SelectedEstado').on('change', function() { @this.set('SelectedEstado', this.value ? [this.value] : []); });
            $('#selectedUsuario').on('change', function() { @this.set('selectedUsuario', this.value || null); });
            $('#selectedAgente').on('change', function() { @this.set('selectedAgente', this.value || null); });
            $('#selectedSociedad').on('change', function() { @this.set('selectedSociedad', this.value || null); });
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('#btnFiltrar')) {
                document.getElementById('loadingOverlay').classList.remove('d-none');
            }
        });

        Livewire.on('ticketsStreamingStart', () => {
            rowsBuffered = 0;
            if (dt) dt.clear().draw(false);
            $('.tabla_transportes').addClass('d-none');
            $('.loading_p').removeClass('d-none');

            if (!dt) {
                dt = $('.tabla_transportes').DataTable({
                    data: [],
                    columns: [
                        { className: 'dt-control', orderable: false, data: null, defaultContent: '' },
                        { title: 'Fecha', data: 'created_at' },
                        { title: 'Ticket', data: 'nomenclatura' },
                        { title: 'Prioridad', data: 'urgencia' },
                        { title: 'Sociedad', data: 'sociedad' },
                        { title: 'País', data: 'pais' },
                        { title: 'Estado del Ticket', data: 'estado' },
                        { title: 'Transportes', data: 'transportes' },
                        { title: 'Agente', data: 'asignado' },
                        { title: 'Acc', data: 'acc', orderable: false, searchable: false },

                        // ocultas para exportar
                        { title: 'Título tarea', data: 'tarea_titulo', visible: false },
                        { title: 'Descripción', data: 'tarea_desc', visible: false },
                        { title: 'Estado de la tarea', data: 'tarea_estado', visible: false },
                        { title: 'Responsable de la tarea', data: 'tarea_responsable', visible: false },
                        { title: 'Fecha creación tarea', data: 'tarea_created_at', visible: false },
                        { title: 'Última modificación', data: 'tarea_updated_at', visible: false },
                        { title: 'Tipo Solicitud', data: 'tipo', visible: false },
                        { title: 'Categoría', data: 'categoria', visible: false },
                        { title: 'Subcategoría', data: 'subcategoria', visible: false },
                        { title: 'Aplicación', data: 'aplicacion', visible: false },
                        { title: 'Usuario', data: 'usuario', visible: false },
                        { title: 'Área', data: 'area', visible: false }
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'Transportes',
                            exportOptions: {
                                columns: ':visible:not(:last-child),:hidden'
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Transportes',
                            exportOptions: {
                                columns: ':visible:not(:last-child),:hidden'
                            }
                        }
                    ],
                    order: [[1, 'desc']]
                });

                $('.tabla_transportes tbody').on('click', 'td.dt-control', function() {
                    const tr = $(this).closest('tr');
                    const row = dt.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                    }
                });
            }
        });

        function format(row) {
            // console.log(row);
            return `
                <div class="p-2">
                    <strong>Título de la tarea:</strong> ${row.tarea_titulo || ''}<br>
                    <strong>Descripción:</strong> ${row.tarea_desc || ''}<br>
                    <strong>Transportes:</strong> ${row.transportes || ''}<br>
                    <strong>Fecha creación de tarea:</strong> ${row.tarea_created_at || ''}<br>
                    <strong>Fecha limite de cumplimiento:</strong> ${row.tarea_fecha_cumplimiento || ''}<br>
                    <strong>Última modificación:</strong> ${row.tarea_updated_at || ''}<br>
                    <strong>Estado de la tarea:</strong> ${row.tarea_estado || ''}<br>
                    <strong>Responsable de la tarea:</strong> ${row.tarea_responsable || 'Sin asignar'}<br><br>
                    <strong>Información adicional del ticket</strong><br>
                    <strong>Tipo Solicitud:</strong> ${row.tipo || ''}<br>
                    <strong>Categoría:</strong> ${row.categoria || ''}<br>
                    <strong>Subcategoría:</strong> ${row.subcategoria || ''}<br>
                    <strong>Aplicación:</strong> ${row.aplicacion || 'NO APLICA'}<br>
                    <strong>Usuario:</strong> ${row.usuario || ''}<br>
                    <strong>Área:</strong> ${row.area || 'Sin seleccionar'}
                </div>
            `;
        }

        Livewire.on('ticketsStreamingAppend', (payload) => {
            const rows = JSON.parse(payload);
            const mapped = rows.map(el => {
                const href = `gestionar?ticket_id=${el.id}`;
                return {
                    ...el,
                    acc: `<a href="${href}" target="_blank" class="btn btn-xs btn-default text-primary mx-1 shadow">
                            <i class="far fa-eye"></i></a>`
                };
            });
            if (mapped.length) {
                dt.rows.add(mapped);
                rowsBuffered += mapped.length;
                if (rowsBuffered >= 2000) {
                    dt.draw(false);
                    rowsBuffered = 0;
                }
            }
        });

        Livewire.on('ticketsStreamingEnd', () => {
            dt.draw(false);
            $('.tabla_transportes').removeClass('d-none');
            $('.loading_p').addClass('d-none');
            document.getElementById('loadingOverlay').classList.add('d-none');
        });

        function exportTabla(tipo) {
            if (!dt) return;
            if (tipo === 'excel') $('.buttons-excel').click();
            else $('.buttons-pdf').click();
        }
    </script>
    @endpush
</div>
