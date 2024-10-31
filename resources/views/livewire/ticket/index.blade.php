<div>
    <style>
        .datepicker {
            width: 100%;
            max-width: 130px;
        }

        .btn {
            height: 40px;
        }

        @media (max-width: 576px) {
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }

        .loading_p {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .dt-buttons {
            display: none !important;
        }
    </style>
    <div class="container mb-3 mt-1">
        <div class="row justify-content-center align-items-center" wire:ignore>
            <div class="col-12 col-md-auto mb-2 mb-md-0 d-flex justify-content-center align-items-center">
                <input type="text" class="datepicker form-control me-2" id="fecha_desde" value="{{ $fecha_desde }}"
                    placeholder="Fecha desde" style="max-width: 130px;">
                <span class="mx-2">a</span>
                <input type="text" class="datepicker form-control ms-2" id="fecha_hasta" value="{{ $fecha_hasta }}"
                    placeholder="Fecha hasta" style="max-width: 130px;">
            </div>
            <div class="col-12 col-md-auto mb-2 mb-md-0">
                <select name="estados" class="select2" id="SelectedEstado">
                    <option value="">Selecciona un estado</option>
                    <option value="">Todos los estados</option>
                    @foreach ($estados as $e)
                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-auto mb-2 mb-md-0">
                <button class="btn btn-light" wire:click="cargarTickets()">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
            <div class="col-12 col-md-auto mb-2 mb-md-0 text-center">
                <a href="javascript:exportTabla('excel')" class="btn btn-default text-success shadow"><i
                        class="far fa-file-excel"></i></a>
            </div>
            <div class="col-12 col-md-auto mb-2 mb-md-0 text-center">
                <a href="javascript:exportTabla('pdf')" class="btn btn-default text-danger shadow"><i
                        class="far fa-file-pdf"></i></a>
            </div>
            <div class="col-12 col-md-auto mb-2 mb-md-0 text-center">
                <a href="#" data-toggle="modal" data-target="#form_ticket" id="btn_form_ticket"
                    class="btn btn-default shadow">
                    <i class="fas fa-plus"></i> Nuevo Ticket
                </a>
            </div>
            <div class="col-auto d-none">
                <a href="#" data-toggle="modal" data-target="#form_ver_ticket" id="btn_ver_ticket"
                    class="btn btn-default shadow"></a>
            </div>
        </div>
    </div>

    <div class="card-content collapse show">
        <div wire:ignore class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table table-striped tabla_tickets d-none" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Ticket</th>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Estado</th>
                            <th>Agente TI</th>
                            <th>Acc</th>
                        </tr>
                    </thead>
                    <tbody id="content_tabla_tickets">
                    </tbody>
                </table>
            </div>
            <div class="margin_20 loading_p d-flex justify-content-center">
                <i class="la la-spinner spinner" style="font-size:30px;"></i>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                @this.cargarTickets()

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
                    @this.set('SelectedEstado', estado === '' ? [] : estado.split(','));
                });


            });
            Livewire.on('cargarTicketsTabla', data => {
                cargarTabla(data);
            });

            function cargarTabla(data) {
                $('.tabla_tickets').DataTable().destroy(); // destruimos la tabla
                $('.tabla_tickets').addClass('d-none'); // ocultamos la tabla
                $('.loading_p').removeClass('d-none'); // mostramos el loading
                $('#content_tabla_tickets').html(''); // limpiar la tabla
                llenarTabla(data).then(() => {
                    $('.tabla_tickets').DataTable({ // volver a inicializar DataTables
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
                                title: 'Tickets',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Tickets',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                },
                            }
                        ]
                    });
                    $('.tabla_tickets').removeClass('d-none'); // mostrar la tabla
                    $('.loading_p').addClass('d-none');
                });
            }

            function llenarTabla(data) {
                data = JSON.parse(data);
                return new Promise((resolve) => {
                    let body = $('#content_tabla_tickets');
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        const {
                            id,
                            created_at,
                            titulo,
                            sociedad,
                            tipo_solicitud,
                            categoria,
                            subcategoria,
                            estado,
                            asignado,
                            nomenclatura,
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
                                        <td class="pointer">${categoria ? categoria.nombre : ''}</td>
                                        <td class="pointer">${subcategoria ? subcategoria.nombre : ''}</td>
                                        <td class="pointer">${estado ? estado.nombre : ''}</td>
                                        <td class="pointer">${asignado ? asignado.name : ''}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="verTicket?ticket_id=${id}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"><i class="far fa-eye"></i></a>
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
