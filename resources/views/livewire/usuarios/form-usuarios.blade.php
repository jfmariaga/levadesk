<div>
    <div class="modal-content">
        <div class="modal-header bg-info text-white">
            <h4 class="modal-title">
                <i class="fas fa-user-edit"></i> Editar Usuario
            </h4>
            <button type="button" class="close text-white" data-dismiss="modal" wire:click="resetear">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form wire:submit.prevent="actualizar">
                <!-- Nombre y Correo -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name"><i class="fas fa-user"></i> Nombre <b class="text-danger">*</b></label>
                        <input type="text" id="name" class="form-control" wire:model="name"
                            placeholder="Ingresa el nombre">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email"><i class="fas fa-envelope"></i> Correo <b
                                class="text-danger">*</b></label>
                        <input type="email" id="email" class="form-control" wire:model="email"
                            placeholder="Ingresa el correo">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Sociedad y Rol -->
                <div class="form-row">
                    <div wire:ignore class="form-group col-md-6">
                        <label for="sociedad_id"><i class="fas fa-building"></i> Sociedad <b
                                class="text-danger">*</b></label>
                        <select id="sociedad_id" class="form-control select2" wire:model="sociedad_id">
                            <option value="">Seleccionar...</option>
                            @foreach ($sociedades as $sociedad)
                                <option value="{{ $sociedad->id }}">{{ $sociedad->nombre }}</option>
                            @endforeach
                        </select>
                        @error('sociedad')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div wire:ignore class="form-group col-md-6">
                        <label for="rol"><i class="fas fa-user-tag"></i> Rol <b class="text-danger">*</b></label>
                        <select id="rol" class="form-control select2" wire:model="rol">
                            <option value="">Seleccionar...</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                            @endforeach
                        </select>
                        @error('rol')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Estado -->
                <div class="form-group col-md-6">
                    <label for="estado"><i class="fas fa-toggle-on"></i> Estado <b class="text-danger">*</b></label>
                    <select id="estado" class="form-control" wire:model="estado">
                        <option value="">Seleccionar...</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                    @error('estado')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botones de acción -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"
                        wire:click="resetear">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-info btn-sm" wire:click="actualizar">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Estilos adicionales -->
    <style>
        .modal-header {
            background-color: #17a2b8;
            color: white;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
            padding-top: 5px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            font-size: 14px;
        }

        .form-control {
            border-radius: 0.25rem;
        }

        .modal-footer .btn {
            min-width: 100px;
        }

        .btn-sm i {
            margin-right: 5px;
        }
    </style>

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                // Inicializar Select2
                $('#sociedad_id, #rol').select2();

                // Sincronizar los valores con Livewire cuando cambien
                $('#sociedad_id').on('change', function() {
                    @this.set('sociedad_id', $(this).val());
                });

                $('#rol').on('change', function() {
                    @this.set('rol', $(this).val());
                });

                // Manejar eventos emitidos desde Livewire para seleccionar opciones en Select2
                Livewire.on('selectSociedad', (id = null) => {
                    if (id) {
                        $('#sociedad_id').val(id).trigger('change');
                    } else {
                        $('#sociedad_id').val('').trigger('change');
                    }
                });

                Livewire.on('selectRol', (id = null) => {
                    if (id) {
                        $('#rol').val(id).trigger('change');
                    } else {
                        $('#rol').val('').trigger('change');
                    }
                });

                Livewire.on('usuario_actualizado', () => {
                    $('#form_usuarios').modal('hide');
                    toastRight('success', 'Usuario editado con éxito!');
                    @this.resetear()
                });
            });
        </script>
    @endpush
</div>
