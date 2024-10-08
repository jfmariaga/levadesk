<div>
    <style>
        .dt-buttons {
            display: none !important;
        }
    </style>
    <div class="card-content collapse show">
        <div wire:ignore class="card-body card-dashboard">
            <div class="col-md-6 col-sm-12 col-12">
                <a href="javascript:exportTabla('excel')" class="btn-lg btn-default text-success mx-1 shadow">
                    <i class="far fa-file-excel"></i>
                </a>
                <a href="javascript:exportTabla('pdf')" class="btn-lg btn-default text-danger mx-1 shadow">
                    <i class="far fa-file-pdf"></i>
                </a>
                <a href="#" data-toggle="modal" data-target="#form_ans" id="btn_form_ans"
                    class="btn-lg btn-default mx-1 shadow">
                    <i class="fas fa-plus"></i> ANSs
                </a>
            </div>
            <table class="table table-striped tabla_ans d-none" style="width:100%;">
                <thead>
                    <tr>
                        <th>Nivel</th>
                        <th>Horario de atención</th>
                        <th>Tiempo de asignación</th>
                        <th>Tiempo de resolución</th>
                        <th>Tiempo de aceptación</th>
                        <th>Tipo de solicitud</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="content_tabla_ans">
                </tbody>
            </table>
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
                @this.cargarAns()
            });

            Livewire.on('cargarAnsTabla', data => {
                cargarTabla(data);
            });

            function cargarTabla(data) {
                $('.tabla_ans').DataTable().destroy(); // destruimos la tabla
                $('.tabla_ans').addClass('d-none'); // ocultamos la tabla
                $('.loading_p').removeClass('d-none'); // mostramos el loading
                $('#content_tabla_ans').html(''); // limpiar la tabla
                llenarTabla(data).then(() => {
                    $('.tabla_ans').DataTable({ // volver a inicializar DataTables
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
                                title: 'ANS',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'ANS',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                },
                            }
                        ]
                    });
                    $('.tabla_ans').removeClass('d-none'); // mostrar la tabla
                    $('.loading_p').addClass('d-none');
                });
            }

            function llenarTabla(data) {
                data = JSON.parse(data);
                return new Promise((resolve) => {
                    let body = $('#content_tabla_ans');
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        const {
                            id,
                            nivel,
                            h_atencion,
                            t_asignacion_segundos,
                            t_resolucion_segundos,
                            t_aceptacion_segundos,
                            solicitud
                        } = element;

                        body.append(`<tr id="tr_${id}">
                            <td class="pointer">${nivel}</td>
                            <td class="pointer">${h_atencion}</td>
                            <td class="pointer">${t_asignacion_segundos / 60} minutos</td>
                            <td class="pointer">${t_resolucion_segundos / 3600} horas</td>
                            <td class="pointer">${t_aceptacion_segundos / 3600} horas</td>
                            <td class="pointer">${solicitud.nombre}</td>
                            <td>
                                <div class="d-flex">
                                    <button onclick="editar(${id})" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                         <i class="fa fa-lg fa-fw fa-pen"></i>
                                     </button>
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

            function editar(id) {
                $('#btn_form_ans').click();
                Livewire.emit('editAns', id);
            }
        </script>
    @endpush
</div>
