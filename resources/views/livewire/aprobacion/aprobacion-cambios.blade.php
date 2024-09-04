<div>
    <style>
        .dt-buttons {
            display: none !important;
        }
    </style>
     <div class="ml-2 mt-1 mr-2 mb-1 mb_vista_agrupada">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a id="btnFuncional" class="btn btn-secondary mr-2" onclick="mostrarTablaFuncional()">Ver Aprobaciones
                    Funcionales</a>
            </li>
            <li class="nav-item">
                <a id="btnTi" class="btn btn-secondary" onclick="mostrarTablaTi()">Ver Aprobaciones TI</a>
            </li>
        </ul>
    </div>
    <div class="container-fluid">
        <div id="tablaFuncional" class="d-none">
            <!-- Aquí va la tabla de aprobaciones funcional -->
            <div class="card-content collapse show">
                <div wire:ignore class="card-body card-dashboard">
                    <div class="col-md-6 col-sm-12 col-12">
                        <a href="javascript:exportTabla('excel')" class="btn-lg btn-default text-success mx-1 shadow">
                            <i class="far fa-file-excel"></i>
                        </a>
                        <a href="javascript:exportTabla('pdf')" class="btn-lg btn-default text-danger mx-1 shadow">
                            <i class="far fa-file-pdf"></i>
                        </a>
                    </div>
                    <table class="table table-striped tabla_aprobaciones_funcional d-none" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Usuario</th>
                                <th>Agente TI</th>
                                <th>Estado</th>
                                <th>Acc</th>
                            </tr>
                        </thead>
                        <tbody id="content_tabla_aprobaciones_funcional">
                        </tbody>
                    </table>
                    <div class="margin_20 loading_p_funcional">
                        <div class="centrar_todo w_100px">
                            <i class="la la-spinner spinner" style="font-size:30px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tablaTi" class="d-none">
            <!-- Aquí va la tabla de aprobaciones TI -->
            <div class="card-content collapse show">
                <div wire:ignore class="card-body card-dashboard">
                    <div class="col-md-6 col-sm-12 col-12">
                        <a href="javascript:exportTabla('excel')" class="btn-lg btn-default text-success mx-1 shadow">
                            <i class="far fa-file-excel"></i>
                        </a>
                        <a href="javascript:exportTabla('pdf')" class="btn-lg btn-default text-danger mx-1 shadow">
                            <i class="far fa-file-pdf"></i>
                        </a>
                    </div>
                    <table class="table table-striped tabla_aprobaciones_ti d-none" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Usuario</th>
                                <th>Agente TI</th>
                                <th>Estado</th>
                                <th>Acc</th>
                            </tr>
                        </thead>
                        <tbody id="content_tabla_aprobaciones_ti">
                        </tbody>
                    </table>
                    <div class="margin_20 loading_p_ti">
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
                @this.loadAprobacionesCambios()
            });

            function mostrarTablaFuncional() {
                document.getElementById('tablaFuncional').classList.remove('d-none');
                document.getElementById('tablaTi').classList.add('d-none');
                document.getElementById('btnFuncional').classList.add('btn-primary');
                document.getElementById('btnFuncional').classList.remove('btn-secondary');
                document.getElementById('btnTi').classList.add('btn-secondary');
                document.getElementById('btnTi').classList.remove('btn-primary');
            }

            function mostrarTablaTi() {
                document.getElementById('tablaTi').classList.remove('d-none');
                document.getElementById('tablaFuncional').classList.add('d-none');
                document.getElementById('btnTi').classList.add('btn-primary');
                document.getElementById('btnTi').classList.remove('btn-secondary');
                document.getElementById('btnFuncional').classList.add('btn-secondary');
                document.getElementById('btnFuncional').classList.remove('btn-primary');
            }


            Livewire.on('cargarAprobacionesFuncionalTablaCambios', data => {
                cargarTabla(data, 'funcional');
            });

            Livewire.on('cargarAprobacionesTiTablaCambios', data => {
                cargarTabla(data, 'ti');
            });

            function cargarTabla(data, tipo) {
                const tablaClass = tipo === 'funcional' ? '.tabla_aprobaciones_funcional' : '.tabla_aprobaciones_ti';
                const loadingClass = tipo === 'funcional' ? '.loading_p_funcional' : '.loading_p_ti';
                const contentId = tipo === 'funcional' ? '#content_tabla_aprobaciones_funcional' :
                    '#content_tabla_aprobaciones_ti';

                $(tablaClass).DataTable().destroy(); // destruir la tabla
                $(tablaClass).addClass('d-none'); // ocultar la tabla
                $(loadingClass).removeClass('d-none'); // mostrar el loading
                $(contentId).html(''); // limpiar la tabla
                llenarTabla(data, contentId).then(() => {
                    $(tablaClass).DataTable({ // volver a inicializar DataTables
                        language: {
                            // configuración de idioma...
                        },
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'excelHtml5',
                                autoFilter: true,
                                title: 'Aprobaciones',
                                exportOptions: {
                                    columns: [0, 1, 2, 3]
                                },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Aprobaciones',
                                exportOptions: {
                                    columns: [0, 1, 2, 3]
                                },
                            }
                        ]
                    });
                    $(tablaClass).removeClass('d-none'); // mostrar la tabla
                    $(loadingClass).addClass('d-none');
                });
            }

            function llenarTabla(data, contentId) {
                data = JSON.parse(data);
                return new Promise((resolve) => {
                    let body = $(contentId);
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        const {
                            id,
                            nomenclatura,
                            usuario,
                            agente_ti,
                            estado
                        } = element;
                        console.log('ID:', id, 'Nomenclatura:', nomenclatura, 'Usuario:', usuario, 'Agente TI:',
                            agente_ti, 'Estado:', estado);
                        body.append(`<tr id="tr_${id}">
                <td class="pointer">${nomenclatura}</td>
                <td class="pointer">${usuario}</td>
                <td class="pointer">${agente_ti}</td>
                <td class="pointer">${estado}</td>
                <td>
                    <div class="d-flex">
                           <a href="cambio?ticket_id=${id}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"><i class="far fa-eye"></i></a>
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

