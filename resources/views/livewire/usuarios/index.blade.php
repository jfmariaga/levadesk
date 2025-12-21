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
                <a href="#" data-toggle="modal" data-target="#form_usuarios" id="btn_form_usuarios"
                    class="btn-lg btn-default mx-1 shadow d-none">
                    <i class="fas fa-plus"></i> usuarios
                </a>
            </div>
            <table class="table table-striped tabla_ans d-none" style="width:100%;">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Sociedad</th>
                        <th>Área</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="content_tabla_ans">
                </tbody>
            </table>
            <div class="margin_20 loading_p">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                @this.cargarUsuarios()
            });

            Livewire.on('cargarUsuariosTabla', data => {
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
                                title: 'Usuarios',
                                exportOptions: {
                                    columns: [0, 1, ]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Usuarios',
                                exportOptions: {
                                    columns: [0, 1, ]
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
                console.log(data);
                
                return new Promise((resolve) => {
                    let body = $('#content_tabla_ans');
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        const {
                            id,
                            name,
                            last_name,
                            email,
                            area,
                            estado,
                            roles,
                            sociedad
                        } = element;

                        // Aquí manejamos los roles como una cadena separada por comas
                        const rolesString = roles.map(role => role.name).join(', ');

                        body.append(`<tr id="tr_${id}">
                            <td class="pointer">${name} ${last_name? last_name : ''}</td>
                            <td class="pointer">${email}</td>
                            <td class="pointer">${rolesString ? rolesString : 'Sin definir'}</td>
                            <td class="pointer">${sociedad ? sociedad.nombre : 'Sin definir'}</td>
                            <td class="pointer">${area ? area : 'Sin definir'}</td>
                             <td class="pointer">${estado == 1 ?  '<span style="color: green;">✔</span>' : '<span style="color: red;">✘</span>'}</td>
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
                $('#btn_form_usuarios').click();
                Livewire.emit('editUsuarios', id);
            }
        </script>
    @endpush
</div>
