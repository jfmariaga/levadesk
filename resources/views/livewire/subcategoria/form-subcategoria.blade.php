<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                @if ($subCategoria_old)
                    <i class="fas fa-edit"></i> Editar Subcategoría
                @else
                    <i class="fas fa-plus"></i> Nueva Subcategoría
                @endif
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetear">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form wire:submit.prevent="submit">
                <div class="form-group">
                    <label for="nombre">Nombre <b style="color: red">*</b></label>
                    <input type="text" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                        wire:model="nombre">
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="categoria">¿A qué tipo de categoría pertenece? <b style="color: red">*</b> </label>
                    <div wire:ignore>
                        <select class="select2" id="categoria" >
                            <option value="">Seleccionar...</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }} - {{$categoria->solicitud->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('categoria_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <label for="codigo">Nomenclatura <b style="color: red">*</b></label>
                        <input type="text" id="codigo" class="form-control @error('codigo') is-invalid @enderror"
                            wire:model="codigo">
                        @error('codigo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-6">
                        @if ($subCategoria_old)
                            <label for="estado">Estado <b style="color: red">*</b></label>
                            <select id="estado" class="form-control @error('estado') is-invalid @enderror "
                                wire:model="estado">
                                <option value="0">Activo</option>
                                <option value="1">Inactivo</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" wire:model="descripcion"></textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <input type="reset" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" wire:click="resetear"
                value="Cancelar">
            <input type="submit" class="btn btn-outline-info btn-sm" value="Guardar" wire:click="submit">
        </div>
    </div>

    @push('js')
        <script>
            Livewire.on('ok_subcategoria', i => {
                $('#form_subcategoria').modal('hide');
                toastRight('success', 'Subcategoría agregada con éxito!');
            })

            Livewire.on('update_subcategoria_ok', i => {
                $('#form_subcategoria').modal('hide');
                toastRight('success', 'Subcategoría editada con éxito!');
            })

            Livewire.on('selectCategoria', (id = null) => {
                if (id) {
                    $('#categoria').val(id).select2().trigger('change');
                } else {
                    $('#categoria').val('').select2().trigger('change');
                }
            })

            document.addEventListener('livewire:load', function() {
                $('.select2').select2();
                $('#categoria').on('change', function() {
                    @this.set('categoria_id', this.value)
                })
            });
        </script>
    @endpush
</div>
