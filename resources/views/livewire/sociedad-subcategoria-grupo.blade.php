<div>
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border-radius: 5px;
            height: 45px;
            font-size: 14px;
        }

        .btn-lg {
            padding: 10px 20px;
            font-size: 16px;
        }

        .text-right {
            margin-top: 20px;
        }

        .card-header {
            border-bottom: none;
            font-weight: bold;
        }
    </style>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                @if ($edit_mode)
                    Editar Relación
                @else
                    Agregar Relación
                @endif
            </h4>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="agregarRelacion">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="sociedad_id">Sociedad</label>
                        <select wire:model="sociedad_id" id="sociedad_id" class="form-control">
                            <option value="">Selecciona una sociedad</option>
                            @foreach ($sociedades as $sociedad)
                                <option value="{{ $sociedad->id }}">{{ $sociedad->nombre }}</option>
                            @endforeach
                        </select>
                        @error('sociedad_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="categoria_id">Categoría</label>
                        <select wire:model="categoria_id" id="categoria_id" class="form-control">
                            <option value="">Selecciona una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="subcategoria_id">Subcategoría</label>
                        <select wire:model="subcategoria_id" id="subcategoria_id" class="form-control">
                            <option value="">Selecciona una subcategoría</option>
                            @foreach ($subcategorias as $subcategoria)
                                <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre }}</option>
                            @endforeach
                        </select>
                        @error('subcategoria_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="grupo_id">Grupo</label>
                        <select wire:model="grupo_id" id="grupo_id" class="form-control">
                            <option value="">Selecciona un grupo</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('grupo_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-outline-info btn-lg">
                        <i class="fas fa-save"></i>
                        @if ($edit_mode)
                            Guardar Cambios
                        @else
                            Agregar Relación
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <div wire:ignore class="card-body card-dashboard">
        <table class="table table-striped tabla_relaciones d-none" style="width:100%;">
            <thead>
                <tr>
                    <th>Sociedad</th>
                    <th>Categoría</th>
                    <th>Subcategoría</th>
                    <th>Grupo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="content_tabla_relaciones">
            </tbody>
        </table>
    </div>

    <div class="loading_p">
        <i class="la la-spinner spinner" style="font-size:30px;"></i>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                @this.cargarRelaciones();

                Livewire.on('showToast', (data) => {
                    toastRight(data.type, data.message);
                });
            });

            Livewire.on('cargarRelacionesTabla', data => {
                cargarTablaRelaciones(data);
            });

            function cargarTablaRelaciones(data) {
                if ($.fn.DataTable.isDataTable('.tabla_relaciones')) {
                    $('.tabla_relaciones').DataTable().destroy();
                }
                $('.tabla_relaciones').addClass('d-none');
                $('.loading_p').removeClass('d-none');
                $('#content_tabla_relaciones').html('');
                llenarTablaRelaciones(data).then(() => {
                    $('.tabla_relaciones').DataTable({
                        language: {
                            "emptyTable": "No hay información",
                        },
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                autoFilter: true,
                                title: 'Relaciones',
                                exportOptions: { columns: [0, 1, 2, 3] },
                            },
                            {
                                extend: 'pdfHtml5',
                                autoFilter: true,
                                title: 'Relaciones',
                                exportOptions: { columns: [0, 1, 2, 3] },
                            }
                        ]
                    });
                    $('.tabla_relaciones').removeClass('d-none');
                    $('.loading_p').addClass('d-none');
                });
            }

            function llenarTablaRelaciones(data) {
                data = JSON.parse(data);
                return new Promise((resolve) => {
                    let body = $('#content_tabla_relaciones');
                    data.forEach((element) => {
                        const { id, sociedad, categoria, subcategoria, grupo } = element;

                        body.append(`
                        <tr id="tr_${id}">
                            <td>${sociedad}</td>
                            <td>${categoria}</td>
                            <td>${subcategoria}</td>
                            <td>${grupo}</td>
                            <td>
                                <div class="d-flex">
                                    <button onclick="editarRelacion(${id})" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                    <button onclick="eliminarRelacion(${id})" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Eliminar">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>`);
                    });
                    resolve(body);
                });
            }

            function eliminarRelacion(id) {
                alertClickCallback('Eliminar', 'Eliminar relación', 'warning', 'Confirmar', 'Cancelar', async () => {
                    const res = await @this.eliminarRelacion(id);
                    toastRight('error', 'Se eliminó la relación!');
                    $(`#tr_${id}`).remove();
                });
            }

            function editarRelacion(id) {
                Livewire.emit('editRelacion', id);
            }
        </script>
    @endpush
</div>
