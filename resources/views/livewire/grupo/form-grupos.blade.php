<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                @if ($grupo_old)
                    <i class="fas fa-edit"></i> Editar Grupo
                @else
                    <i class="fas fa-plus"></i> Nuevo Grupo
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
                <div class="form-group">
                    <label for="usuarios">Usuarios <b style="color: red">*</b></label>
                    <div class="select2_100" wire:ignore>
                        <select multiple id="usuarios" class="form-control select2">
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('selectedUsuarios')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <span wire:loading wire:target="guardar">
                <button type="button" class="btn grey btn-outline-secondary" disabled>
                    <i class="la la-refresh spinner"> </i>
                </button>
            </span>
            <span wire:loading.remove wire:target="guardar">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal"
                    wire:click="resetear">Cancelar</button>
                <button type="button" class="btn btn-outline-primary" wire:click="submit">Guardar</button>
            </span>
        </div>
    </div>
    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                $('.select2').select2();

                $('#usuarios').on('change', function() {
                    @this.set('selectedUsuarios', $(this).val());
                });

                Livewire.on('selectUsuarios', (usuarios) => {
                    $('#usuarios').val(usuarios).trigger('change');
                });

                Livewire.on('ok_grupo', () => {
                    $('#form_grupos').modal('hide');
                    toastRight('success', 'Grupo agregado con éxito!');
                });

                Livewire.on('update_grupo_ok', () => {
                    $('#form_grupos').modal('hide');
                    toastRight('success', 'Grupo editado con éxito!');
                });

                Livewire.on('resetSelect2', () => {
                    $('#usuarios').val(null).trigger('change');
                });
            });
        </script>
    @endpush

</div>
