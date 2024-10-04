<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                @if ($role_id)
                    <i class="fas fa-edit"></i> Editar Rol
                @else
                    <i class="fas fa-plus"></i> Nuevo Rol
                @endif
            </h5>
            <button type="button" class="close" data-dismiss="modal" wire:click="resetForm">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <form wire:submit.prevent="submit">
                <div class="form-group">
                    <label for="name">Nombre del Rol <b style="color: red">*</b></label>
                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                        wire:model="name">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="permissions">Permisos</label>
                    <div class="checkbox">
                        @foreach ($allPermissions as $permission)
                            <label>
                                <!-- Usar nombre en lugar de ID -->
                                <input type="checkbox" value="{{ $permission->name }}" wire:model="selectedPermissions">
                                {{ $permission->name }}
                            </label><br>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="reset" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"
                        wire:click="resetForm" value="Cancelar">
                    <input type="submit" class="btn btn-outline-info btn-sm" value="Guardar">
                </div>
            </form>
        </div>
    </div>
    @push('js')
        <script>
            Livewire.on('roleUpdated', i => {
                $('#form_roles').modal('hide');
                toastRight('success', 'Rol editado con éxito!');
            })
            Livewire.on('roleOk', i => {
                $('#form_roles').modal('hide');
                toastRight('success', 'Rol agregado con éxito!');
            })
        </script>
    @endpush
</div>
