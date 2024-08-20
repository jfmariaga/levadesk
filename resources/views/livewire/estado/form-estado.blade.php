<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                @if ($estado_old)
                    <i class="fas fa-edit"></i> Editar Estado
                @else
                    <i class="fas fa-plus"></i> Nuevo Estado
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
            Livewire.on('ok_estado', () => {
                $('#form_estado').modal('hide');
                toastRight('success', 'Estado agregado con éxito!');
            });

            Livewire.on('update_estado_ok', () => {
                $('#form_estado').modal('hide');
                toastRight('success', 'Estado editado con éxito!');
            });
        </script>
    @endpush

</div>
