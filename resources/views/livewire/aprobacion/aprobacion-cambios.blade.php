<div>
    <style>
        .dt-buttons {
            display: none !important;
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
    </style>
    <div class="ml-2 mt-1 mr-2 mb-1 mb_vista_agrupada">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a id="btnFuncional" class="btn btn-secondary mr-2" onclick="mostrarTablaFuncional()">Ver Aprobaciones
                    Funcionales</a>
            </li>
            <li class="nav-item">
                <a id="btnTi" class="btn btn-secondary" onclick="mostrarTablaTi()">Ver Aprobaciones TI</a>
            </li>
        </ul>
    </div>
    <div class="container-fluid">
        <div wire:ignore>
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
                        <button class="btn btn-light mx-2" wire:click="loadAprobacionesCambios()"
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
        </div>
        <div id="tablaFuncional" class="d-none">
            <!-- Aquí va la tabla de aprobaciones funcional -->
            <div class="card-content collapse show">
                <div wire:ignore class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-striped tabla_aprobaciones_funcional d-none" style="width:100%;">
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
                                    <th>Usuario</th>
                                    <th>Agente</th>
                                    <th>Estado Flujo</th>
                                    <th>Estado Ticket</th>
                                    <th>Acc</th>
                                </tr>
                            </thead>
                            <tbody id="content_tabla_aprobaciones_funcional">
                            </tbody>
                        </table>
                    </div>
                    <div class="margin_20 loading_p_funcional">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tablaTi" class="d-none">
            <!-- Aquí va la tabla de aprobaciones TI -->
            <div class="card-content collapse show">
                <div wire:ignore class="card-body card-dashboard">
                    <div class="table-responsive">

                        <table class="table table-striped tabla_aprobaciones_ti d-none" style="width:100%;">
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
                                    <th>Usuario</th>
                                    <th>Agente</th>
                                    <th>Estado Flujo</th>
                                    <th>Estado Ticket</th>
                                    <th>Acc</th>
                                </tr>
                            </thead>
                            <tbody id="content_tabla_aprobaciones_ti">
                            </tbody>
                        </table>
                    </div>
                    <div class="margin_20 loading_p_ti">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                @this.loadAprobacionesCambios()

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

                $('#fecha_desde').on('change', function() {
                    @this.set('fecha_desde', this.value)
                })
                $('#fecha_hasta').on('change', function() {
                    @this.set('fecha_hasta', this.value)
                })

                $('#SelectedEstado').on('change', function() {
                    let estado = $(this).val();
                    @this.set('SelectedEstado', estado === '' ? [] : [estado]);
                });

                $('#selectedUsuario').on('change', function() {
                    let selectedUsuario = $(this).val();
                    @this.set('selectedUsuario', selectedUsuario === '' ? [] : [selectedUsuario]);
                });

                $('#selectedAgente').on('change', function() {
                    let selectedAgente = $(this).val();
                    @this.set('selectedAgente', selectedAgente === '' ? [] : [selectedAgente]);
                });

                $('#selectedSociedad').on('change', function() {
                    let selectedSociedad = $(this).val();
                    @this.set('selectedSociedad', selectedSociedad === '' ? [] : [selectedSociedad]);
                });
            });

            let tablaVisible = 'funcional'; // Variable para recordar la tabla activa

            function mostrarTablaFuncional() {
                document.getElementById('tablaFuncional').classList.remove('d-none');
                document.getElementById('tablaTi').classList.add('d-none');
                document.getElementById('btnFuncional').classList.add('btn-primary');
                document.getElementById('btnFuncional').classList.remove('btn-secondary');
                document.getElementById('btnTi').classList.add('btn-secondary');
                document.getElementById('btnTi').classList.remove('btn-primary');
                tablaVisible = 'funcional';
            }

            function mostrarTablaTi() {
                document.getElementById('tablaTi').classList.remove('d-none');
                document.getElementById('tablaFuncional').classList.add('d-none');
                document.getElementById('btnTi').classList.add('btn-primary');
                document.getElementById('btnTi').classList.remove('btn-secondary');
                document.getElementById('btnFuncional').classList.add('btn-secondary');
                document.getElementById('btnFuncional').classList.remove('btn-primary');
                tablaVisible = 'ti';
            }

            Livewire.hook('message.processed', () => {
                if (tablaVisible === 'funcional') {
                    mostrarTablaFuncional();
                } else {
                    mostrarTablaTi();
                }
            });

            Livewire.on('cargarAprobacionesFuncionalTablaCambios', data => {
                cargarTabla(data, 'funcional');
            });

            Livewire.on('cargarAprobacionesTiTablaCambios', data => {
                cargarTabla(data, 'ti');
            });

            function cargarTabla(data, tipo) {
                const tablaClass = tipo === 'funcional' ? '.tabla_aprobaciones_funcional' : '.tabla_aprobaciones_ti';
                const loadingClass = tipo === 'funcional' ? '.loading_p_funcional' : '.loading_p_ti';
                const contentId = tipo === 'funcional' ? '#content_tabla_aprobaciones_funcional' :
                    '#content_tabla_aprobaciones_ti';

                $(tablaClass).DataTable().destroy(); // destruir la tabla
                $(tablaClass).addClass('d-none'); // ocultar la tabla
                $(loadingClass).removeClass('d-none'); // mostrar el loading
                $(contentId).html(''); // limpiar la tabla
                llenarTabla(data, contentId).then(() => {
                    $(tablaClass).DataTable({ // volver a inicializar DataTables
                        language: {
                            // configuración de idioma...
                        },
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'excelHtml5',
                                autoFilter: true,
                                title: 'Aprobaciones',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Aprobaciones',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                                },
                            }
                        ]
                    });
                    $(tablaClass).removeClass('d-none'); // mostrar la tabla
                    $(loadingClass).addClass('d-none');
                });
            }

            function llenarTabla(data, contentId) {
                data = JSON.parse(data);
                return new Promise((resolve) => {
                    let body = $(contentId);
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        const {
                            id,
                            nomenclatura,
                            usuario,
                            agente_ti,
                            estado,
                            estado_ticket,
                            fecha,
                            sociedad,
                            tipo_solicitud,
                            titulo,
                            categoria,
                            subcategoria,
                            prioridad
                        } = element;
                        let row = $(`<tr id="tr_${id}" class="clickable-row" data-href="cambio?ticket_id=${id}" style="cursor: pointer;">
                            <td class="pointer">${fecha}</td>
                            <td class="pointer">${nomenclatura}</td>
                            <td class="pointer">${titulo}</td>
                            <td class="pointer">${prioridad}</td>
                            <td class="pointer">${sociedad}</td>
                            <td class="pointer">${tipo_solicitud}</td>
                            <td class="pointer">${categoria}</td>
                            <td class="pointer">${subcategoria}</td>
                            <td class="pointer">${usuario}</td>
                            <td class="pointer">${agente_ti}</td>
                            <td class="pointer">${estado}</td>
                            <td class="pointer">${estado_ticket}</td>

                            <td>
                                <div class="d-flex">
                                    <a href="cambio?ticket_id=${id}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"><i class="far fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>`);
                        row.on("click", function(event) {
                            if (!$(event.target).closest("a").length) {
                                window.open($(this).data("href"), '_blank');
                            }
                        });

                        body.append(row);

                    }
                    resolve(body);
                });
            }

            function exportTabla(tipo) {
                if (tipo == 'excel') {
                    $('.buttons-excel').click();
                } else {
                    $('.buttons-pdf').click();
                }
            }
        </script>
    @endpush
</div>
