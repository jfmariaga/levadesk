<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                @if ($ans_old)
                    <i class="fas fa-edit"></i> Editar ANS
                @else
                    <i class="fas fa-plus"></i> Nuevo ANS
                @endif
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetear">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form wire:submit.prevent="submit">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nivel">Nivel de Criticidad <b style="color: red">*</b></label>
                        <input type="text" id="nivel" class="form-control @error('nivel') is-invalid @enderror" wire:model="nivel">
                        @error('nivel')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="solicitud">Tipo de Solicitud <b style="color: red">*</b> </label>
                        <div wire:ignore>
                            <select class="form-control select2" id="solicitud">
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
                </div>
                <div class="form-group">
                    <label for="h_atencion">Horario de Atención <b style="color: red">*</b></label>
                    <input type="text" id="h_atencion" class="form-control @error('h_atencion') is-invalid @enderror" wire:model="h_atencion">
                    @error('h_atencion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="t_asignacion">Tiempo de Asignación (en minutos) <b style="color: red">*</b></label>
                        <input type="number" id="t_asignacion" class="form-control @error('t_asignacion') is-invalid @enderror" wire:model="t_asignacion">
                        @error('t_asignacion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="t_resolucion">Tiempo de Resolución (en horas) <b style="color: red">*</b></label>
                        <input type="number" id="t_resolucion" class="form-control @error('t_resolucion') is-invalid @enderror" wire:model="t_resolucion">
                        @error('t_resolucion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="t_aceptacion">Tiempo de Aceptación (en horas) <b style="color: red">*</b></label>
                        <input type="number" id="t_aceptacion" class="form-control @error('t_aceptacion') is-invalid @enderror" wire:model="t_aceptacion">
                        @error('t_aceptacion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <input type="reset" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" wire:click="resetear" value="Cancelar">
            <input type="submit" class="btn btn-outline-info btn-sm" value="Guardar" wire:click="submit">
        </div>
    </div>

    @push('js')
        <script>
            Livewire.on('ok_ans', i => {
                $('#form_ans').modal('hide');
                toastRight('success', 'ANS agregado con éxito!');
            })

            Livewire.on('update_ans_ok', i => {
                $('#form_ans').modal('hide');
                toastRight('success', 'ANS editado con éxito!');
            })

            Livewire.on('error_ans', message => {
                toastRight('warning', message);
            });

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
