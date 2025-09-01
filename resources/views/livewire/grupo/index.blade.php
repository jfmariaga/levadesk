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
                <a href="#" data-toggle="modal" data-target="#form_grupos" id="btn_form_grupos"
                    class="btn-lg btn-default mx-1 shadow">
                    <i class="fas fa-plus"></i> Grupo</a>
            </div>
            <table class="table table-striped tabla_grupos d-none" style="width:100%;">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Usuarios</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="content_tabla_grupos">
                </tbody>
            </table>
            <div class="margin_20 loading_p">
                <div class="centrar_todo w_100px">
                    <i class="la la-spinner spinner" style="font-size:30px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar relaciones -->
    <div class="modal fade" id="modalRelaciones" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalRelacionesTitulo"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="tablaRelaciones">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Sociedad</th>
                                <th>Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                @this.cargarGrupo()
            });

            Livewire.on('cargarGruposTabla', data => {
                cargarTabla(data);
            });


            Livewire.on('showRelationModal', data => {
                // Setear título
                document.getElementById('modalRelacionesTitulo').innerHTML = data.titulo;

                // Llenar tabla
                let tbody = document.querySelector("#tablaRelaciones tbody");
                tbody.innerHTML = "";
                data.detalles.forEach(item => {
                    tbody.innerHTML += `
                <tr>
                    <td>${item.tipo}</td>
                    <td>${item.sociedad}</td>
                    <td>${item.detalle}</td>
                </tr>
                    `;
                });

                // Mostrar modal
                $('#modalRelaciones').modal('show');
            });

            Livewire.on('showToast', (data) => {
                toastRight(data.type, data.message);
            });

            function cargarTabla(data) {
                $('.tabla_grupos').DataTable().destroy();
                $('.tabla_grupos').addClass('d-none');
                $('.loading_p').removeClass('d-none');
                $('#content_tabla_grupos').html('');
                llenarTabla(data).then(() => {
                    $('.tabla_grupos').DataTable({
                        language: {
                            "decimal": "",
                            "emptyTable": "No hay información",
                            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
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
                                title: 'Grupos',
                                exportOptions: {
                                    columns: [0, 1, 2]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Grupos',
                                exportOptions: {
                                    columns: [0, 1, 2]
                                },
                            }
                        ]
                    });
                    $('.tabla_grupos').removeClass('d-none');
                    $('.loading_p').addClass('d-none');
                });
            }

            function llenarTabla(data) {
                data = JSON.parse(data);
                return new Promise((resolve) => {
                    let body = $('#content_tabla_grupos');
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        const {
                            id,
                            nombre,
                            descripcion,
                            usuarios
                        } = element;
                        body.append(`<tr id="tr_${id}">
            <td class="pointer">${nombre}</td>
            <td class="pointer">${descripcion ? descripcion : ''}</td>
            <td class="pointer">${usuarios.map(usuario => usuario.name).join(', ')}</td>
            <td>
                <div class="d-flex">
                    <button onclick="editar(${id})" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </button>
                    <button onclick="confirmDelete(${id})" class="btn btn-xs btn-default text-danger mx-1 shadow"
                        title="Delete">
                        <i class="fas fa-trash-alt"></i>
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
                $('#btn_form_grupos').click();
                Livewire.emit('editGrupo', id);
            }

            function confirmDelete(id) {
                alertClickCallback('¿Eliminar grupo?', `El grupo se eliminará completamente`, 'warning',
                    'Si, Eliminar',
                    'Cancelar',
                    function() {
                        @this.deleteGrupo(id);
                    });
            }
        </script>
    @endpush
</div>
