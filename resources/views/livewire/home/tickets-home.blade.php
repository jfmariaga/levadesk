<div>
    <style>
        .card {
            border: none;
            border-radius: 10px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        .table-responsive {
            overflow-x: auto;
            overflow-y: auto;
        }

        .table thead th {
            border-bottom: none;
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .badge {
            font-size: 100%;
        }

        .dt-buttons {
            display: none !important;
        }

        /* === Overlay local sobre la tabla (solo al filtrar) === */
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
    </style>

    <div class="col-lg-12 col-md-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Todos los Tickets</h5>
            </div>

            <div class="card-body">
                <div wire:ignore class="card-body card-dashboard">
                    <div class="table-responsive table-wrapper-relative">
                        <!-- ✅ Overlay oculto por defecto; se muestra solo al click en Filtrar -->
                        <div id="loadingOverlay" class="loading-overlay d-none">
                            <div class="loader">
                                <div class="spinner-border" role="status" aria-hidden="true"></div>
                                <span>Aplicando filtros, cargando datos…</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-12 col-lg-12 mt-1">
                                <div class="d-flex align-items-center">
                                    <input type="text" class="datepicker form-control" id="fecha_desde"
                                        value="{{ $fecha_desde }}" style="width:150px;">
                                    <span class="mx-2">a</span>
                                    <input type="text" class="datepicker form-control mr-2" id="fecha_hasta"
                                        value="{{ $fecha_hasta }}" style="width:150px;">

                                    <select wire:ignore id="selectedSociedad" name="sociedades" class="select2">
                                        <option value="">Selecciona un sociedad</option>
                                        @foreach ($sociedades as $s)
                                            <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                                        @endforeach
                                    </select>

                                    <select wire:ignore name="estados" class="select2" id="SelectedEstado">
                                        <option value="">Selecciona un estado</option>
                                        <option value="">Todos los estados</option>
                                        @foreach ($estados as $e)
                                            <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                        @endforeach
                                    </select>

                                    <select wire:ignore id="selectedUsuario" name="usuarios" class="select2">
                                        <option value="">Seleccionar Usuario</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                        @endforeach
                                    </select>

                                    <select wire:ignore id="selectedAgente" name="agentes" class="select2">
                                        <option value="">Seleccionar Agente</option>
                                        @foreach ($agentes as $a)
                                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                                        @endforeach
                                    </select>

                                    <!-- ✅ id para detectar click y mostrar overlay -->
                                    <button id="btnFiltrar" class="btn btn-light mx-2" wire:click="cargarDatos()"
                                        style="height:40px;">
                                        <i class="fas fa-filter"></i>
                                    </button>

                                    <a href="javascript:exportTabla('excel')"
                                        class="btn btn-default text-success mx-1 shadow">
                                        <i class="far fa-file-excel"></i>
                                    </a>
                                    <a href="javascript:exportTabla('pdf')"
                                        class="btn btn-default text-danger mx-1 shadow">
                                        <i class="far fa-file-pdf"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped tabla_gestion_tickets d-none" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Código</th>
                                    <th>Titulo</th>
                                    <th>Prioridad</th>
                                    <th>Sociedad</th>
                                    <th>Tipo de Solicitud</th>
                                    <th>Categoría</th>
                                    <th>Subcategoría</th>
                                    <th>Aplicación</th>
                                    <th>Usuario</th>
                                    <th>Área</th>
                                    <th>Estado</th>
                                    <th>Agente</th>
                                    <th>Acc</th>
                                </tr>
                            </thead>
                            <tbody id="content_tabla_gestion_tickets"></tbody>
                        </table>
                    </div>

                    <div class="margin_20 loading_p">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>

                </div> <!-- /card-dashboard -->
            </div> <!-- /card-body -->
        </div>
    </div>

    @push('js')
        <script>
            let dt = null;
            let rowsBuffered = 0;

            document.addEventListener('livewire:load', function() {
                // Carga inicial SIN overlay
                @this.cargarDatos();

                $('.select2').select2();

                $('#fecha_desde').pickadate({
                    format: 'yyyy-mm-dd'
                });
                $('#fecha_hasta').pickadate({
                    format: 'yyyy-mm-dd'
                });

                $('.pickadate').pickadate({
                    format: 'dd/mm/yyyy',
                    formatSubmit: 'yyyy-mm-dd',
                    selectMonths: true,
                    selectYears: true,
                    today: 'Hoy',
                    clear: 'Limpiar',
                    close: 'Cerrar',
                    closeOnSelect: true,
                    container: 'body',
                    onOpen: function() {
                        $('.picker__holder').css('background', 'transparent');
                    }
                });

                // Sincronizar filtros con Livewire (el usuario da clic en el botón para recargar)
                $('#fecha_desde').on('change', function() {
                    @this.set('fecha_desde', this.value);
                });
                $('#fecha_hasta').on('change', function() {
                    @this.set('fecha_hasta', this.value);
                });

                $('#SelectedEstado').on('change', function() {
                    let v = $(this).val();
                    @this.set('SelectedEstado', v === '' ? [] : [v]);
                });
                $('#selectedUsuario').on('change', function() {
                    let v = $(this).val();
                    @this.set('selectedUsuario', v === '' ? null : v);
                });
                $('#selectedAgente').on('change', function() {
                    let v = $(this).val();
                    @this.set('selectedAgente', v === '' ? null : v);
                });
                $('#selectedSociedad').on('change', function() {
                    let v = $(this).val();
                    @this.set('selectedSociedad', v === '' ? null : v);
                });
            });

            // ✅ Mostrar overlay apenas el usuario hace click en "Filtrar"
            document.addEventListener('click', function(e) {
                if (e.target.closest('#btnFiltrar')) {
                    const ov = document.getElementById('loadingOverlay');
                    if (ov) ov.classList.remove('d-none');
                }
            });

            // ======= STREAMING DESDE LIVEWIRE (tu lógica tal cual) =======

            // Inicio: limpiar y preparar tabla
            Livewire.on('ticketsStreamingStart', () => {
                rowsBuffered = 0;

                if (dt) {
                    dt.clear().draw(false);
                }

                $('.tabla_gestion_tickets').addClass('d-none');
                $('.loading_p').removeClass('d-none');

                if (!dt) {
                    dt = $('.tabla_gestion_tickets').DataTable({
                        data: [],
                        columns: [{
                                title: 'Fecha'
                            },
                            {
                                title: 'Código'
                            },
                            {
                                title: 'Titulo'
                            },
                            {
                                title: 'Prioridad'
                            },
                            {
                                title: 'Sociedad'
                            },
                            {
                                title: 'Tipo de Solicitud'
                            },
                            {
                                title: 'Categoría'
                            },
                            {
                                title: 'Subcategoría'
                            },
                            {
                                title: 'Aplicación'
                            },
                            {
                                title: 'Usuario'
                            },
                            {
                                title: 'Área'
                            },
                            {
                                title: 'Estado'
                            },
                            {
                                title: 'Agente'
                            },
                            {
                                title: 'Acc'
                            }
                        ],
                        language: {
                            "decimal": "",
                            "emptyTable": "No hay información",
                            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                            "thousands": ",",
                            "lengthMenu": "Mostrar _MENU_ Entradas",
                            "loadingRecords": "Cargando...",
                            "processing": "Procesando...",
                            "search": "Buscar:",
                            "zeroRecords": "Sin resultados encontrados",
                            "paginate": {
                                "first": "Primero",
                                "last": "Último",
                                "next": "Siguiente",
                                "previous": "Anterior"
                            }
                        },
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'excelHtml5',
                                autoFilter: true,
                                title: 'Estados',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Estados',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                                },
                            }
                        ]
                    });
                }
            });

            // Append de cada lote
            Livewire.on('ticketsStreamingAppend', (payload) => {
                const rows = JSON.parse(payload);
                const mapped = mapearFilas(rows);

                if (mapped.length) {
                    dt.rows.add(mapped);
                    rowsBuffered += mapped.length;

                    // Dibuja cada ~2000 filas para no recalcular todo el tiempo
                    if (rowsBuffered >= 2000) {
                        dt.draw(false);
                        rowsBuffered = 0;
                    }
                }
            });

            // Fin: dibuja lo pendiente y muestra + oculta overlay
            Livewire.on('ticketsStreamingEnd', () => {
                dt.draw(false);
                $('.tabla_gestion_tickets').removeClass('d-none');
                $('.loading_p').addClass('d-none');

                const ov = document.getElementById('loadingOverlay');
                if (ov) ov.classList.add('d-none');
            });

            // Mapeo de objetos a columnas DataTables
            function mapearFilas(rows) {
                return rows.map(el => {
                    const id = el.id;
                    const href = `gestionar?ticket_id=${id}`;

                    // Función para envolver cada dato con un link
                    const linkWrap = (content) =>
                        `<a href="${href}" target="_blank" style="color: inherit; text-decoration: none;">${content}</a>`;

                    return [
                        linkWrap(el.created_at || ''),
                        linkWrap(el.nomenclatura || ''),
                        linkWrap(el.titulo || ''),
                        linkWrap((el.urgencia && el.urgencia.nombre) || ''),
                        linkWrap((el.sociedad && el.sociedad.nombre) || ''),
                        linkWrap((el.tipo_solicitud && el.tipo_solicitud.nombre) || ''),
                        linkWrap((el.categoria && el.categoria.nombre) || ''),
                        linkWrap((el.subcategoria && el.subcategoria.nombre) || ''),
                        linkWrap((el.aplicacion && el.aplicacion.nombre) || 'NO APLICA'),
                        linkWrap((el.usuario && el.usuario.name) || ''),
                        linkWrap((el.usuario && el.usuario.area) || 'Sin seleccionar'),
                        linkWrap((el.estado && el.estado.nombre) || ''),
                        linkWrap((el.asignado && el.asignado.name) || ''),
                        // Última columna con el botón de ver
                        `<a href="${href}" target="_blank" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Ver">
                <i class="far fa-eye"></i>
             </a>`
                    ];
                });
            }


            function exportTabla(tipo) {
                if (!dt) return;
                if (tipo === 'excel') {
                    $('.buttons-excel').click();
                } else {
                    $('.buttons-pdf').click();
                }
            }
        </script>
    @endpush
</div>
