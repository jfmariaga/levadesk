<div>
    <style>
        .dt-buttons {
            display: none !important;
        }

        /* Estilo uniforme de las tarjetas */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            color: black;
            position: relative;
            overflow: hidden;
            background: #ffffff;
        }

        .card .inner h3 {
            font-size: 2rem;
            margin: 0;
            font-weight: bold;
        }

        .card .inner p {
            font-size: 1rem;
            margin: 5px 0 0;
            font-weight: 400;
        }

        .card .icon {
            font-size: 3rem;
            opacity: 0.2;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        /* Estilos específicos de tarjetas */
        .card-carrusel {
            background: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Mantén una sombra ligera */
        }

        .card-en-proceso {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .card-por-iniciar {
            background: linear-gradient(135deg, #fd7e14, #e55300);
            color: white;
        }

        .card-horas-soporte {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
        }

        .card-mas {
            background: linear-gradient(135deg, #6a1b9a, #4527a0);
            /* Degradado púrpura a azul oscuro */
            color: white;
        }

        /* Estilos del carrusel */
        .carousel-inner {
            border-radius: 10px;
            background: #f9f9f9;
            padding: 10px;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: black;
            border-radius: 50%;
        }

        .carousel-indicators li {
            background-color: black;
        }

        /* Hover para botones interactivos */
        .card:hover {
            cursor: pointer;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
            padding: 10px;
            border-bottom: 2px solid #ddd;
        }

        .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .table tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        /* Forzar que los encabezados se alineen con las columnas */
        .dataTables_scrollHeadInner {
            width: 100% !important;
        }

        .dataTables_scrollHeadInner table {
            width: 100% !important;
        }

        .dataTables_scrollBody {
            overflow-x: auto !important;
        }

        /* Loader */
        .loading_p {
            text-align: center;
            margin: 20px 0;
        }

        .visible-buttons {
            display: none;
            /* Los botones estarán ocultos por defecto */
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <!-- Tarjetas con filtros -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2 mt-2">
                <div class="card card-en-proceso" wire:click="filtrarEnProceso">
                    <div class="inner">
                        <h3>{{ $ticketsEnProceso }}</h3>
                        <p>Solicitudes en Atención</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-headphones-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2 mt-2">
                <div class="card card-por-iniciar" wire:click="filtrarPorIniciar">
                    <div class="inner">
                        <h3>{{ $ticketsPorIniciar }}</h3>
                        <p>Solicitudes por Iniciar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2 mt-2">
                <div class="card card-horas-soporte" wire:click="filtrarSolucionados">
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
                <a href="{{ route('estadisticas') }}">
                    <div class="card card-mas">
                        <div class="inner">
                            <h3><i class="fas fa-arrow-right"></i></h3>
                            <p>Ver todas mis estadísticas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- Tickets Solucionados y Tabla de Tickets -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                <div class="card">
                    <div wire:ignore class="card-body card-dashboard">
                        <div class="table-responsive">
                            <div class="row mb-2">
                                <div class="col-md-8 col-lg-8 mt-1">
                                    <div class="d-flex align-items-center">
                                        <input type="text" class="datepicker form-control" id="fecha_desde"
                                            value="{{ $fecha_desde }}" style="width:150px;">
                                        <span class="mx-2">a</span>
                                        <input type="text" class="datepicker form-control mr-2" id="fecha_hasta"
                                            value="{{ $fecha_hasta }}" style="width:150px;">
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
                            <div class="col-md-12">
                                <button id="organizarTablaBtn" class="btn btn-sm btn-secondary mb-1">Seleccionar
                                    columnas para mostrar <i class="fas fa-chevron-down ml-1"></i></button>
                            </div>
                            <div class="visible-buttons">
                                <button class="btn btn-light mx-1 toggle-column" data-column="0">Fecha</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="1">Código</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="2">Titulo</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="3">Prioridad</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="4">Sociedad </button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="5">Tipo de
                                    Solicitud</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="6">Categoría</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="7">Subcategoría</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="8">Aplicación</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="9">Usuario</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="10">Estado</button>
                                <button class="btn btn-light mx-1 toggle-column" data-column="11">Rol</button>
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
                                        <th>Rol</th>
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

                document.getElementById("organizarTablaBtn").addEventListener("click", function() {
                    const buttons = document.querySelector('.visible-buttons'); // Selector de todos los botones
                    if (buttons.style.display === '' || buttons.style.display === 'none') {
                        buttons.style.display = 'block'; // Mostrar los botones
                    } else {
                        buttons.style.display = 'none'; // Ocultar los botones
                    }
                });



                // Funcionalidad para cambiar la visibilidad de las columnas
                $('.toggle-column').on('click', function() {
                    const column = $('.tabla_gestion_tickets').DataTable().column($(this).data('column'));
                    column.visible(!column.visible()); // Cambiar visibilidad de la columna
                    $(this).toggleClass('btn-secondary btn-light'); // Cambiar el estilo del botón
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
                        scrollY: 400, // Altura máxima del scroll
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
                                title: 'Estados',
                                exportOptions: {
                                    columns: function(idx, data, node) {
                                        // Solo exportar columnas visibles
                                        return $(node).css('display') !== 'none';
                                    }
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Estados',
                                exportOptions: {
                                    columns: function(idx, data, node) {
                                        // Solo exportar columnas visibles
                                        return $(node).css('display') !== 'none';
                                    }
                                }
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
                            sociedad,
                            rol,
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
                                <td class="pointer">${sociedad ? sociedad.nombre :''}</td>
                                <td class="pointer">${tipo_solicitud ? tipo_solicitud.nombre : ''}</td>
                                <td class="pointer">${categoria.nombre}</td>
                                <td class="pointer">${subcategoria.nombre}</td>
                                <td class="pointer">${ aplicacion ?aplicacion.nombre:'NO APLICA'}</td>
                                <td class="pointer">${usuario.name}</td>
                                <td class="pointer">${estado ? estado.nombre:''}</td>
                        <td class="pointer">${rol}</td>
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
