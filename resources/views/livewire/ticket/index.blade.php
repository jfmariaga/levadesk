<div>
    <style>
        .dt-buttons {
            display: none !important;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
    <div class="card-content collapse show">
        <div wire:ignore class="card-body card-dashboard">
            <div class="col-md-6 col-sm-12 col-12 mb-3">
                <a href="javascript:exportTabla('excel')" class="btn-lg btn-default text-success mx-1 shadow "><i
                        class="far fa-file-excel"></i></a>
                <a href="javascript:exportTabla('pdf')" class="btn-lg btn-default text-danger mx-1 shadow "><i
                        class="far fa-file-pdf"></i></a>
                <a href="#" data-toggle="modal" data-target="#form_ticket" id="btn_form_ticket"
                    class="btn-lg btn-default mx-1 shadow">
                    <i class="fas fa-plus"></i> Nuevo Ticket</a>
                <a href="#" data-toggle="modal" data-target="#form_ver_ticket" id="btn_ver_ticket"
                    class="btn-lg btn-default mx-1 shadow d-none"></a>
            </div>
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
            <div class="margin_20 loading_p">
                <div class="centrar_todo w_100px">
                    <i class="la la-spinner spinner" style="font-size:30px;"></i>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                @this.cargarTickets()
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
