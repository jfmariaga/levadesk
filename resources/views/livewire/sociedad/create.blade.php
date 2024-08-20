<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                @if ($sociedad_old)
                    <i class="fas fa-edit"></i> Editar Sociedad
                @else
                    <i class="fas fa-plus"></i> Nueva Sociedad
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
                    <label for="nomenclatura">Nomenclatura <b style="color: red">*</b></label>
                    <input type="text" id="codigo" class="form-control @error('codigo') is-invalid @enderror"
                        wire:model="codigo">
                    @error('codigo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" wire:model="descripcion"></textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                @if ($sociedad_old)
                    <div class="form-group">
                        <label for="estado">Estado <b style="color: red">*</b></label>
                        <select id="estado" class="form-control @error('estado') is-invalid @enderror "
                            wire:model="estado">
                            <option value="0">Activo</option>
                            <option value="1">Inactivo</option>
                        </select>
                        @error('estado')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
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
            Livewire.on('ok_sociedad', i => {
                $('#form_sociedad').modal('hide');
                toastRight('success', 'Sociedad agregada con éxito!');
            })

            Livewire.on('update_sociedad_ok', i => {
                $('#form_sociedad').modal('hide');
                toastRight('success', 'Sociedad editada con éxito!');
            })
        </script>
    @endpush
</div>
