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
        <div class="card" id="supervisor_tickets_section">
            <div class="card-header">
                <h5>Supervisión de Tickets</h5>
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
                                    <button class="btn btn-light mx-2" wire:click="cargarDatosSupervisor()"
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
                        <table class="table table-striped tabla_gestion_supervisores d-none" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Codigo</th>
                                    <th>Titulo</th>
                                    <th>Prioridad</th>
                                    <th>Estado</th>
                                    <th>Agente</th>
                                    <th>Acc</th>
                                </tr>
                            </thead>
                            <tbody id="content_tabla_gestion_supervisores">
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
    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                @this.cargarDatosSupervisor()

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

            Livewire.on('cargarSupervisorTabla', data => {
                const tickets = JSON.parse(data);
                if (tickets.length > 0) {
                    cargarTablaSuper(data);
                    document.getElementById('supervisor_tickets_section').style.display = 'block';
                } else {
                    document.getElementById('supervisor_tickets_section').style.display = 'none';
                }
            });

            function cargarTablaSuper(data) {
                $('.tabla_gestion_supervisores').DataTable().destroy();
                $('.tabla_gestion_supervisores').addClass('d-none');
                $('.loading_p').removeClass('d-none');
                $('#content_tabla_gestion_supervisores').html('');
                llenarTablaSuper(data).then(() => {
                    $('.tabla_gestion_supervisores').DataTable({
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
                    $('.tabla_gestion_supervisores').removeClass('d-none');
                    $('.loading_p').addClass('d-none');
                });
            }

            function llenarTablaSuper(data) {
                data = JSON.parse(data);
                return new Promise((resolve) => {
                    let body = $('#content_tabla_gestion_supervisores');
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
                        } = element;

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

                                <td class="pointer">${asignado.name}</td>
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
