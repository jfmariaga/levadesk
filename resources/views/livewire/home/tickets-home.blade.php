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
    </style>
    <div class="col-lg-12 col-md-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h5>Todos los Tickets</h5>
            </div>
            <div class="card-body">
                <div wire:ignore class="card-body card-dashboard">
                    <div class="table-responsive">
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
                                    <button class="btn btn-light mx-2" wire:click="cargarDatos()" style="height:40px;">
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
                                    <th>Estado</th>
                                    <th>Agente</th>
                                    <th>Acc</th>
                                </tr>
                            </thead>
                            <tbody id="content_tabla_gestion_tickets">
                            </tbody>
                        </table>
                    </div>
                    <div class="margin_20 loading_p">
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
                @this.cargarDatos()
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

            Livewire.on('cargarGestioTicketTabla', data => {
                cargarTabla(data);
            });

            function cargarTabla(data) {
                $('.tabla_gestion_tickets').DataTable().destroy(); // destruimos la tabla
                $('.tabla_gestion_tickets').addClass('d-none'); // ocultamos la tabla
                $('.loading_p').removeClass('d-none'); // mostramos el loading
                $('#content_tabla_gestion_tickets').html(''); // limpiar la tabla
                llenarTabla(data).then(() => {
                    $('.tabla_gestion_tickets').DataTable({ // volver a inicializar DataTables
                        language: {
                            "decimal": "",
                            "emptyTable": "No hay información",
                            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                            "infoPostFix": "",
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
                                title: 'EStados',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Estados',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                                },
                            }
                        ]
                    });
                    $('.tabla_gestion_tickets').removeClass('d-none'); // mostrar la tabla
                    $('.loading_p').addClass('d-none');
                });
            }

            function llenarTabla(data) {
                data = JSON.parse(data);
                return new Promise((resolve) => {
                    let body = $('#content_tabla_gestion_tickets');
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        const {
                            id,
                            created_at,
                            nomenclatura,
                            titulo,
                            urgencia,
                            estado,
                            asignado,
                            categoria,
                            subcategoria,
                            usuario,
                            tipo_solicitud,
                            aplicacion,
                            sociedad
                        } = element;

                        // Formatear la fecha
                        let fecha = new Date(created_at);
                        let dia = ('0' + fecha.getDate()).slice(-2);
                        let mes = ('0' + (fecha.getMonth() + 1)).slice(-2);
                        let anio = fecha.getFullYear();
                        let fechaFormateada = `${anio}-${mes}-${dia}`;

                        let row = $(`<tr id="tr_${id}" class="clickable-row" data-href="gestionar?ticket_id=${id}" style="cursor: pointer;">
                                <td class="pointer">${fechaFormateada}</td>
                                <td class="pointer">${nomenclatura}</td>
                                <td class="pointer">${titulo}</td>
                                <td class="pointer">${urgencia ? urgencia.nombre :''}</td>
                                <td class="pointer">${sociedad ? sociedad.nombre :''}</td>
                                <td class="pointer">${tipo_solicitud ? tipo_solicitud.nombre : ''}</td>
                                <td class="pointer">${categoria.nombre}</td>
                                <td class="pointer">${subcategoria.nombre}</td>
                                <td class="pointer">${ aplicacion ?aplicacion.nombre:'NO APLICA'}</td>
                                <td class="pointer">${usuario.name}</td>
                                <td class="pointer">${estado ? estado.nombre:''}</td>
                                <td class="pointer">${asignado.name}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="gestionar?ticket_id=${id}" target="_blank" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"><i class="far fa-eye"></i></a>
                                    </div>
                                </td>
                            </tr>`);
                        // Agregar evento click a la fila para abrir en nueva pestaña
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
