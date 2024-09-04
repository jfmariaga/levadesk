<div>
    @if (Auth::user()->id === $usuarioId)
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    @if ($ticket_old)
                        Editar Ticket
                    @else
                        Nuevo Ticket
                    @endif
                </h4>
                <button type="button" class="close" data-dismiss="modal" wire:click="resetForm">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="submit">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="sociedad_id">Sociedad <b style="color: red">*</b></label>
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
                            <label for="tipo_solicitud_id">Tipo de Solicitud <b style="color: red">*</b></label>
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
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="categoria_id">Categoría <b style="color: red">*</b></label>
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
                            <label for="subcategoria_id">Subcategoría <b style="color: red">*</b></label>
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
                        <div class="form-group col-md-6">
                            @if (!empty($aplicaciones))
                                <label for="aplicacion_id">Aplicación <b style="color: red">*</b></label>
                                <select id="aplicacion_id" class="form-control" wire:model="aplicacion_id">
                                    <option value="">Seleccionar...</option>
                                    @foreach ($aplicaciones as $aplicacion)
                                        <option value="{{ $aplicacion->id }}">{{ $aplicacion->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('aplicacion_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="urgencia"> Urgencia <b style="color: red">*</b></label>
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
                        <div class="form-group col-md-6">
                            <label for="titulo">Título <b style="color: red">*</b></label>
                            <input type="text" id="titulo" class="form-control" wire:model="titulo">
                            @error('titulo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" class="form-control" wire:model="descripcion"></textarea>
                        @error('descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Adjuntos (jpg,png,pdf,doc,docx,xlsx,xls)</label>
                        <x-adminlte-input-file id="{{ $identificar }}" multiple wire:model="archivos" name="ifPholder"
                            igroup-size="sm" placeholder="Seleccionar un archivo...">
                            <x-slot name="prependSlot">
                                <div class="input-group-text bg-lightblue">
                                    <i class="fas fa-upload"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-file>
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
                                                            alt="" class="img-fluid" style="max-width: 100px;">
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
                            <div wire:loading wire:target="archivos" class="" role="alert">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="text-center"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-center">
                            <div wire:loading wire:target="submit" class="" role="alert">
                                <div class="spinner-grow text-info" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="archivos, submit"
                            wire:click="resetForm">Cancelar</button>
                        <button type="submit" class="btn btn-outline-info btn-sm" wire:click="submit"
                            wire:loading.attr="disabled" wire:target="archivos, submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
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


        });
    </script>
</div>
