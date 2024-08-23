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
    <div class="container-fluid">
        <!-- Tarjetas de estadísticas -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2 mt-2">
                <div class="small-box card card-total-solucionados">
                    <div class="inner">
                        <h3>{{ $ticketsSolucionados->count() }}</h3>
                        <p>Total de Tickets Solucionados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2 mt-2">
                <div class="small-box card card-solicitudes-en-proceso">
                    <div class="inner">
                        <h3>{{ $ticketsEnProceso }}</h3>
                        <p>Solicitudes en atención</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-headphones-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2 mt-2">
                <div class="small-box card card-solicitudes-por-iniciar">
                    <div class="inner">
                        <h3>{{ $ticketsPorIniciar }}</h3>
                        <p>Solicitudes por iniciar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2 mt-2">
                <div class="small-box card card-horas-soporte">
                    <div class="inner">
                        <h3>{{ $totalHorasSoporte }}</h3>
                        <p>Horas de soporte / mes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Solucionados y Tabla de Tickets -->
        <div class="row">
            <div class="col-lg-4 col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Tickets Solucionados</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($ticketsSolucionados as $ticket)
                                @php
                                    $comentarioCalificado = $ticket
                                        ->comentarios()
                                        ->whereNotNull('calificacion')
                                        ->first();
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="badge solucionado-badge">{{$ticket->nomenclatura}}</strong>
                                        <p>{{ $ticket->usuario->name }} - {{ $ticket->created_at->format('d M, Y') }}
                                        </p>
                                    </div>
                                    @if ($comentarioCalificado)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star" style="color: gold;"></i>
                                            <span class="ml-1">{{ $comentarioCalificado->calificacion }}/5</span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star text-muted"></i>
                                            <span class="ml-1">0/5</span>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Tickets</h5>
                    </div>
                    <div class="card-body">
                        <div wire:ignore class="card-body card-dashboard">
                            <div class="table-responsive">
                                <div class="row mb-2">
                                    <div class="col-md-6 col-lg-6 mt-1">
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="datepicker form-control" id="fecha_desde"
                                                value="{{ $fecha_desde }}" style="width:150px;">
                                            <span class="mx-2">a</span>
                                            <input type="text" class="datepicker form-control" id="fecha_hasta"
                                                value="{{ $fecha_hasta }}" style="width:150px;">
                                            <button class="btn btn-light mx-2" wire:click="cargarDatos()"
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
                                            <th>Codigo</th>
                                            <th>Titulo</th>
                                            <th>Prioridad</th>
                                            <th>Estado</th>
                                            <th>Acc</th>
                                        </tr>
                                    </thead>
                                    <tbody id="content_tabla_gestion_tickets">
                                    </tbody>
                                </table>
                            </div>
                            <div class="margin_20 loading_p">
                                <div class="centrar_todo w_100px">
                                    <i class="la la-spinner spinner" style="font-size:30px;"></i>
                                </div>
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
                                    columns: [0, 1, 2, 3, 4]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Estados',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
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
                        } = element;

                        // Formatear la fecha
                        let fecha = new Date(created_at);
                        let dia = ('0' + fecha.getDate()).slice(-2);
                        let mes = ('0' + (fecha.getMonth() + 1)).slice(-2);
                        let anio = fecha.getFullYear();
                        let fechaFormateada = `${dia}-${mes}-${anio}`;

                        body.append(`<tr id="tr_${id}">
                        <td class="pointer">${fechaFormateada}</td>
                        <td class="pointer">${nomenclatura}</td>
                        <td class="pointer">${titulo}</td>
                        <td class="pointer">${urgencia ? urgencia.nombre :''}</td>
                        <td class="pointer">${estado ? estado.nombre:''}</td>
                        <td>
                            <div class="d-flex">
                                <a href="gestionar?ticket_id=${id}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"><i class="far fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>`);
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
