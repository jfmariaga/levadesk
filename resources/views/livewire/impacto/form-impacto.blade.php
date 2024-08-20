<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                @if ($impacto_old)
                    <i class="fas fa-edit"></i> Editar Impacto
                @else
                    <i class="fas fa-plus"></i> Nuevo Impacto
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
                    <label for="puntuacion">Puntuación</label>
                    <input type="number" id="puntuacion" class="form-control @error('puntuacion') is-invalid @enderror"
                        wire:model="puntuacion">
                    @error('puntuacion')
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
            Livewire.on('ok_impacto', () => {
                $('#form_impacto').modal('hide');
                toastRight('success', 'Impacto agregado con éxito!');
            });

            Livewire.on('update_impacto_ok', () => {
                $('#form_impacto').modal('hide');
                toastRight('success', 'Impacto editado con éxito!');
            });
        </script>
    @endpush
</div>


