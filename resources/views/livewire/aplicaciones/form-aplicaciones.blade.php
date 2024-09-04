<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                @if ($aplicacion_old)
                    <i class="fas fa-edit"></i> Editar aplicacón
                @else
                    <i class="fas fa-plus"></i> Nueva aplicacón
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
                    <label for="sociedad">Sociedad <b style="color: red">*</b> </label>
                    <div wire:ignore>
                        <select class="select2" id="sociedad">
                            <option value="">Seleccionar...</option>
                            @foreach ($sociedades as $sociedad)
                                <option value="{{ $sociedad->id }}">{{ $sociedad->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('sociedad')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <label for="responsable">responsable <b style="color: red">*</b> </label>
                        <div wire:ignore>
                            <select class="select2" id="responsable">
                                <option value="">Seleccionar...</option>
                                @foreach ($responsables as $responsable)
                                    <option value="{{ $responsable->id }}">{{ $responsable->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('responsable')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-6">
                        @if ($aplicacion_old)
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
            Livewire.on('ok_aplicacion', i => {
                $('#form_aplicacion').modal('hide');
                toastRight('success', 'Aplicación agregada con éxito!');
            })

            Livewire.on('update_aplicacion_ok', i => {
                $('#form_aplicacion').modal('hide');
                toastRight('success', 'Aplicación editada con éxito!');
            })

            Livewire.on('selectSociedad', (id = null) => {
                if (id) {
                    $('#sociedad').val(id).select2().trigger('change');
                } else {
                    $('#sociedad').val('').select2().trigger('change');
                }
            })

            Livewire.on('selectResponsable', (id = null) => {
                if (id) {
                    $('#responsable').val(id).select2().trigger('change');
                } else {
                    $('#reponsable').val('').select2().trigger('change');
                }
            })

            document.addEventListener('livewire:load', function() {
                $('.select2').select2();
                $('#sociedad').on('change', function() {
                    @this.set('sociedad', this.value)
                })

                $('#responsable').on('change', function() {
                    @this.set('responsable', this.value)
                })
            });
        </script>
    @endpush
</div>

