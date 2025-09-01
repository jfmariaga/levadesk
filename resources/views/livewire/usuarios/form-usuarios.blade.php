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
                        <select id="rol" class="form-control select2" wire:model="rol" multiple>
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

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="estado"><i class="fas fa-toggle-on"></i> Estado <b
                                class="text-danger">*</b></label>
                        <select id="estado" class="form-control" wire:model="estado">
                            <option value="">Seleccionar...</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        @error('estado')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="estado"><i class="fas fa-toggle-on"></i> Aprobador TI <b
                                class="text-danger">*</b></label>
                        <select id="estado" class="form-control" wire:model="aprobador_ti">
                            <option value="">Seleccionar...</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                        @error('estado')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
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

    <!-- Modal Tickets Pendientes -->
    <div class="modal fade" id="modalTicketsPendientes" tabindex="-1" role="dialog" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Reasignar Tickets Pendientes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Este usuario tiene tickets pendientes. Debes reasignarlos antes de inactivarlo.</p>

                    @if (count($ticketsComoUsuario) > 0)
                        <h6>Tickets donde es Usuario</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nomenclatura</th>
                                    <th>Título</th>
                                    <th>Estado</th>
                                    <th>Agente actual</th>
                                    <th>Reasignar Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ticketsComoUsuario as $ticket)
                                    <tr>
                                        <td>{{ $ticket['nomenclatura'] }}</td>
                                        <td>{{ $ticket['titulo'] ?? '-' }}</td>
                                        <td>{{ $ticket['estado']['nombre'] ?? '-' }}</td>
                                        <td>{{ $ticket['asignado']['name'] ?? '-' }}</td>
                                        <td>
                                            <select wire:model="reasignacionesUsuario.{{ $ticket['id'] }}"
                                                class="form-control">
                                                <option value="">-- Seleccionar --</option>
                                                @foreach ($usuariosActivos as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if (count($ticketsComoAgente) > 0)
                        <h6>Tickets donde es Agente</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nomenclatura</th>
                                    <th>Título</th>
                                    <th>Estado</th>
                                    <th>Agente actual</th>
                                    <th>Reasignar Agente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ticketsComoAgente as $ticket)
                                    <tr>
                                        <td>{{ $ticket['nomenclatura'] }}</td>
                                        <td>{{ $ticket['titulo'] ?? '-' }}</td>
                                        <td>{{ $ticket['estado']['nombre'] ?? '-' }}</td>
                                        <td>{{ $ticket['asignado']['name'] ?? '-' }}</td>
                                        <td>
                                            <select wire:model="reasignacionesAgente.{{ $ticket['id'] }}"
                                                class="form-control">
                                                <option value="">-- Seleccionar --</option>
                                                @foreach ($usuariosActivos as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="confirmarReasignacion"
                        @if (count($reasignacionesUsuario) < count($ticketsComoUsuario) ||
                                count($reasignacionesAgente) < count($ticketsComoAgente)) disabled @endif>
                        Reasignar e Inactivar
                    </button>
                </div>

            </div>
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
                    let valores = $(this).val() || []; // Asegurar un array vacío si no hay selección
                    @this.set('rol', valores);
                });


                // Manejar eventos emitidos desde Livewire para seleccionar opciones en Select2
                Livewire.on('selectSociedad', (id = null) => {
                    if (id) {
                        $('#sociedad_id').val(id).trigger('change');
                    } else {
                        $('#sociedad_id').val('').trigger('change');
                    }
                });

                Livewire.on('selectRol', (ids = []) => {
                    $('#rol').val(ids).trigger('change');
                });


                Livewire.on('usuario_actualizado', () => {
                    $('#form_usuarios').modal('hide');
                    toastRight('success', 'Usuario editado con éxito!');
                    @this.resetear()
                });

                Livewire.on('showTicketsPendientes', () => {
                    $('#modalTicketsPendientes').modal('show');
                });

                Livewire.on('closeTicketsPendientes', () => {
                    $('#modalTicketsPendientes').modal('hide');
                });
            });
        </script>
    @endpush
</div>
