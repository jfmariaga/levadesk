<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                @if ($categoria_old)
                    <i class="fas fa-edit"></i> Editar categoria
                @else
                    <i class="fas fa-plus"></i> Nueva categoria
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
                    <label for="solicitud">Tipo de Solicitud <b style="color: red">*</b> </label>
                    <div wire:ignore>
                        <select class="select2" id="solicitud">
                            <option value="">Seleccionar...</option>
                            @foreach ($solicitudes as $solicitud)
                                <option value="{{ $solicitud->id }}">{{ $solicitud->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('solicitud')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <label for="nomenclatura">Nomenclatura <b style="color: red">*</b></label>
                        <input type="text" id="codigo" class="form-control @error('codigo') is-invalid @enderror"
                            wire:model="codigo">
                        @error('codigo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-6">
                        @if ($categoria_old)
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
            Livewire.on('ok_categoria', i => {
                $('#form_categoria').modal('hide');
                toastRight('success', 'Categoria agregada con éxito!');
            })

            Livewire.on('update_categoria_ok', i => {
                $('#form_categoria').modal('hide');
                toastRight('success', 'Categoria editada con éxito!');
            })
            
            Livewire.on('selectSolicitud', (id = null) => {
                if (id) {
                    $('#solicitud').val(id).select2().trigger('change');
                } else {
                    $('#solicitud').val('').select2().trigger('change');
                }
            })

            document.addEventListener('livewire:load', function() {
                $('.select2').select2();
                $('#solicitud').on('change', function() {
                    @this.set('solicitud', this.value)
                })
            });
        </script>
    @endpush
</div>
