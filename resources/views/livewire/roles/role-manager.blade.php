<div>
    <style>
        .dt-buttons {
            display: none !important;
        }
        /* Ajuste de ancho de columnas */
        .col-nombre {
            width: 20%;
        }
        .col-permisos {
            width: 60%;
        }
        .col-acciones {
            width: 20%;
        }
    </style>
    <div class="card-content collapse show">
        <div wire:ignore class="card-body card-dashboard">
            <div class="col-md-6 col-sm-12 col-12">
                <a href="javascript:exportTabla('excel')" class="btn-lg btn-default text-success mx-1 shadow ">
                    <i class="far fa-file-excel"></i>
                </a>
                <a href="javascript:exportTabla('pdf')" class="btn-lg btn-default text-danger mx-1 shadow ">
                    <i class="far fa-file-pdf"></i>
                </a>
                <a href="#" data-toggle="modal" data-target="#form_roles" id="btn_form_roles"
                    class="btn-lg btn-default mx-1 shadow">
                    <i class="fas fa-plus"></i> Rol
                </a>
            </div>
            <table class="table table-striped tabla_roles d-none" style="width:100%;">
                <thead>
                    <tr>
                        <th class="col-nombre">Nombre</th>
                        <th class="col-permisos">Permisos</th>
                        <th class="col-acciones">Acciones</th>
                    </tr>
                </thead>
                <tbody id="content_tabla_roles">
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
                @this.cargarRoles(); // Cargamos los roles al cargar el componente
            });

            Livewire.on('cargarRolesTabla', data => {
                cargarTabla(data); // Llamamos a la función para llenar la tabla
            });

            function cargarTabla(data) {
                $('.tabla_roles').DataTable().destroy(); // Destruimos la tabla actual
                $('.tabla_roles').addClass('d-none'); // Ocultamos la tabla
                $('.loading_p').removeClass('d-none'); // Mostramos el loading
                $('#content_tabla_roles').html(''); // Limpiamos la tabla

                llenarTabla(data).then(() => {
                    $('.tabla_roles').DataTable({ // Re-inicializamos la tabla
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
                                title: 'Roles y permisos',
                                exportOptions: {
                                    columns: [0, 1]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Roles y permisos',
                                exportOptions: {
                                    columns: [0, 1]
                                },
                            }
                        ]
                    });
                    $('.tabla_roles').removeClass('d-none'); // Mostramos la tabla
                    $('.loading_p').addClass('d-none'); // Ocultamos el loading
                });
            }

            function llenarTabla(data) {
                data = JSON.parse(data);
                return new Promise((resolve) => {
                    let body = $('#content_tabla_roles');
                    body.empty(); // Limpiamos el contenido actual de la tabla

                    data.forEach(role => {
                        const {
                            id,
                            name,
                            permissions
                        } = role;

                        // Creamos la lista de permisos
                        let permisosHtml = '';
                        if (permissions && permissions.length > 0) {
                            permissions.forEach(permission => {
                                permisosHtml +=
                                    `<span class="badge badge-info">${permission.name}</span> `;
                            });
                        } else {
                            permisosHtml = 'Sin permisos';
                        }

                        // Añadimos la fila correspondiente
                        body.append(`
                            <tr id="tr_${id}">
                                <td class="pointer">${name}</td>
                                <td class="pointer">${permisosHtml}</td>
                                <td>
                                    <div class="d-flex">
                                        <button onclick="editar(${id})" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar">
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });

                    resolve(body);
                });
            }

            function exportTabla(tipo) {
                if (tipo === 'excel') {
                    $('.buttons-excel').click();
                } else {
                    $('.buttons-pdf').click();
                }
            }

            function editar(id) {
                $('#btn_form_roles').click(); // Mostramos el modal para editar roles
                 @this.editRole(id)
            }
        </script>
    @endpush
</div>
