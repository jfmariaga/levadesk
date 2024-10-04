<div>
    @if (Auth::user()->id === $usuarioId)
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h4 class="modal-title">
                    <i class="fas fa-ticket-alt"></i>
                    @if ($ticket_old)
                        Editar Ticket
                    @else
                        Nuevo Ticket
                    @endif
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" wire:click="resetForm">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="submit">
                    <!-- Sociedad y Tipo de Solicitud -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="sociedad_id"><i class="fas fa-building"></i> Sociedad <b
                                    class="text-danger">*</b></label>
                            <select id="sociedad_id" class="form-control" wire:model="sociedad_id">
                                <option value="">Seleccionar...</option>
                                @foreach ($sociedades as $sociedad)
                                    <option value="{{ $sociedad->id }}">{{ $sociedad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('sociedad_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tipo_solicitud_id"><i class="fas fa-list"></i> Tipo de Solicitud <b
                                    class="text-danger">*</b></label>
                            <select id="tipo_solicitud_id" class="form-control" wire:model="tipo_solicitud_id">
                                <option value="">Seleccionar...</option>
                                @foreach ($tipos_solicitud as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('tipo_solicitud_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Categoría y Subcategoría -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="categoria_id"><i class="fas fa-tags"></i> Categoría <b
                                    class="text-danger">*</b></label>
                            <select id="categoria_id" class="form-control" wire:model="categoria_id">
                                <option value="">Seleccionar...</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="subcategoria_id"><i class="fas fa-tag"></i> Subcategoría <b
                                    class="text-danger">*</b></label>
                            <select id="subcategoria_id" class="form-control" wire:model="subcategoria_id">
                                <option value="">Seleccionar...</option>
                                @foreach ($subcategorias as $subcategoria)
                                    <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre }}</option>
                                @endforeach
                            </select>
                            @error('subcategoria_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Mostrar campos de excepción si es una excepción -->
                        @if ($esExcepcion)
                            <div class="form-group col-md-6">
                                <label for="usuario_sap"><i class="fas fa-user"></i> Usuario<b
                                        class="text-danger">*</b></label>
                                <input type="text" id="usuario_sap" class="form-control" wire:model="usuario_sap"
                                    placeholder="Usuario de la excepción">
                                @error('usuario_sap')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="modulo"><i class="fas fa-cubes"></i> Módulo <b
                                        class="text-danger">*</b></label>
                                <input type="text" id="modulo" class="form-control" wire:model="modulo"
                                    placeholder="Ingresa el módulo">
                                @error('modulo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="fecha_inicio"><i class="fas fa-calendar-alt"></i> Fecha de Inicio <b
                                        class="text-danger">*</b></label>
                                <input type="date" id="fecha_inicio" class="form-control" wire:model="fecha_inicio">
                                @error('fecha_inicio')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="fecha_fin"><i class="fas fa-calendar-alt"></i> Fecha de Fin <b
                                        class="text-danger">*</b></label>
                                <input type="date" id="fecha_fin" class="form-control" wire:model="fecha_fin">
                                @error('fecha_fin')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>



                    <!-- Aplicación (condicional) y Urgencia -->
                    @if (!empty($aplicaciones))
                        <!-- Cambia esta condición según tu lógica -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="aplicacion_id"><i class="fas fa-laptop-code"></i> Aplicación <b
                                        class="text-danger">*</b></label>
                                <select id="aplicacion_id" class="form-control" wire:model="aplicacion_id">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($aplicaciones as $aplicacion)
                                        <option value="{{ $aplicacion->id }}">{{ $aplicacion->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('aplicacion_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="urgencia"><i class="fas fa-exclamation-triangle"></i> Urgencia <b
                                        class="text-danger">*</b></label>
                                <select id="urgencia" class="form-control" wire:model="urgencia">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($urgencias as $u)
                                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('urgencia')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="urgencia"><i class="fas fa-exclamation-triangle"></i> Urgencia <b
                                        class="text-danger">*</b></label>
                                <select id="urgencia" class="form-control" wire:model="urgencia">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($urgencias as $u)
                                        <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('urgencia')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Título -->
                    <div class="form-group">
                        <label for="titulo"><i class="fas fa-heading"></i> Título <b
                                class="text-danger">*</b></label>
                        <input type="text" id="titulo" class="form-control" wire:model="titulo"
                            placeholder="Ingresa el título del ticket">
                        @error('titulo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="descripcion"><i class="fas fa-align-left"></i> Descripción <b
                                class="text-danger">*</b></label>
                        <textarea id="descripcion" class="form-control" wire:model="descripcion" placeholder="Describe el ticket"></textarea>
                        @error('descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Adjuntos -->
                    <div class="form-group">
                        <label><i class="fas fa-paperclip"></i> Adjuntos (jpg, png, pdf, doc, docx, xlsx, xls, msg)</label>
                        <x-adminlte-input-file id="{{ $identificar }}" multiple wire:model="archivos"
                            name="ifPholder" igroup-size="sm" placeholder="Seleccionar un archivo...">
                            <x-slot name="prependSlot">
                                <div class="input-group-text bg-lightblue">
                                    <i class="fas fa-upload"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-file>

                        <!-- Vista previa de archivos -->
                        <div class="row mt-3">
                            <div class="col-12">
                                @if ($archivos)
                                    <div class="mt-2 d-flex justify-content-center">
                                        @foreach ($archivos as $archivo)
                                            <div class="text-center mx-2">
                                                @if (in_array($archivo->extension(), ['jpg', 'png']))
                                                    <div class="d-flex justify-content-center">
                                                        <img src="{{ $archivo->temporaryUrl() }}" alt=""
                                                            class="img-fluid" style="max-width: 100px;">
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-center">
                                                        <img src="{{ $this->getIcon($archivo->extension()) }}"
                                                            alt="" class="img-fluid"
                                                            style="max-width: 100px;">
                                                    </div>
                                                @endif
                                                <span>{{ $archivo->getClientOriginalName() }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @error('archivos')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="col-12 d-flex justify-content-center">
                            <div wire:loading wire:target="archivos" class="spinner-border text-primary"
                                role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"
                            wire:click="resetForm">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-outline-info btn-sm" wire:click="submit">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Estilos adicionales -->
    <style>
        .modal-header {
            background-color: #17a2b8;
            color: white;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            font-size: 14px;
        }

        .modal-footer .btn {
            min-width: 100px;
        }

        .btn-sm i {
            margin-right: 5px;
        }
    </style>
    <script>
        document.addEventListener('livewire:load', function() {

            bsCustomFileInput.init();
            $('.select2').select2();

            $('#grupo_id').on('change', function() {
                @this.set('grupo_id', $(this).val());
            });

            $('#sociedad_id').on('change', function() {
                @this.set('sociedad_id', $(this).val());
            });

            $('#tipo_solicitud_id').on('change', function() {
                @this.set('tipo_solicitud_id', $(this).val());
            });

            $('#categoria_id').on('change', function() {
                @this.set('categoria_id', $(this).val());
            });

            $('#subcategoria_id').on('change', function() {
                @this.set('subcategoria_id', $(this).val());
            });

            $('#estado_id').on('change', function() {
                @this.set('estado_id', $(this).val());
            });

            Livewire.on('selectUsuarios', (usuarios) => {
                $('#usuarios').val(usuarios).trigger('change');
            });

            Livewire.on('selectGrupos', (grupos) => {
                $('#grupos').val(grupos).trigger('change');
            });

            Livewire.on('selectEstados', (estados) => {
                $('#estados').val(estados).trigger('change');
            });

            Livewire.on('resetSelect2', () => {
                $('#usuarios').val(null).trigger('change');
                $('#grupos').val(null).trigger('change');
                $('#estados').val(null).trigger('change');
            });

            Livewire.on('ok_ticket', () => {
                $('#form_ticket').modal('hide');
                toastRight('success', 'Ticket agregado con éxito!');
            });

            Livewire.on('update_ticket_ok', () => {
                $('#form_ticket').modal('hide');
                toastRight('success', 'Ticket editado con éxito!');
            });

            $('#form_ticket').on('hidden.bs.modal', function() {
                @this.resetForm();
            });

            Livewire.on('showToast', (data) => {
                toastRight(data.type, data.message);
            });


        });
    </script>
</div>
