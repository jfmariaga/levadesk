<div>
    <style>
        /* === Tus estilos originales === */
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header-top {
            background-color: #17a2b8;
            height: 10px;
        }

        .user-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: black;
        }

        .text-muted {
            color: #6c757d;
        }

        .tab-button {
            background-color: #77adb6;
            border: 1px solid #77adb6;
            border-radius: 25px;
            color: white;
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            margin: 0 5px;
        }

        .tab-button.active {
            background-color: #138496;
            color: white;
            border-color: #138496;
        }

        .tab-button:hover {
            background-color: #138496;
            color: white;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin-top: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .form-group {
            width: 100%;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .table-sm th,
        .table-sm td {
            font-size: 0.9rem;
            vertical-align: middle;
        }
    </style>

    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header-top"></div>
                    <div class="card-body text-center">
                        <img src="{{ $profile_photo_preview ?? Auth::user()->adminlte_image() }}"
                            class="rounded-circle mb-3 profile-img" alt="Foto de perfil">
                        <h4 class="user-name">{{ Auth::user()->name }}</h4>
                        <p class="text-muted">{{ Auth::user()->adminlte_desc() }}</p>
                        <p class="mb-2"><strong>Sociedad:</strong> {{ $sociedad->nombre ?? 'No asignada' }}</p>
                        <p class="mb-2"><strong>Área:</strong> {{ Auth::user()->area ?? 'Sin definir' }}</p>
                        @if (Auth::user()->en_vacaciones)
                            <div class="text-center mt-3">
                                <button wire:click="volverDelTrabajo" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-briefcase me-1"></i> Regresar al trabajo
                                </button>
                            </div>
                        @endif

                        {{-- <p class="mb-0"><strong>Grupos de Atención:</strong></p>
                        <ul class="list-unstyled">
                            @foreach ($grupos as $grupo)
                                <li>{{ $grupo->nombre }}</li>
                            @endforeach
                        </ul> --}}

                        {{-- @if (!Auth::user()->hasRole('Usuario') && !Auth::user()->hasRole('Aprobador'))
                            <div class="form-group d-flex flex-column align-items-center">
                                @if (Auth::user()->en_vacaciones)
                                    <p><strong>Agente BK:</strong>
                                        {{ Auth::user()->backups->last()->name ?? 'No asignado' }}</p>
                                    <button class="btn btn-outline-info btn-sm" wire:click="volverDelTrabajo">
                                        Regresar al trabajo
                                    </button>
                                @else
                                    <div class="form-group mb-3">
                                        <label for="agente">Si te vas a ausentar, por favor elige un agente como tu BK:</label>
                                        <select wire:model="nuevoAsignadoId" id="agente"
                                            class="form-control form-control-sm">
                                            <option value="">Seleccionar automáticamente</option>
                                            @foreach ($agentes as $agente)
                                                <option value="{{ $agente->id }}">{{ $agente->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="btn btn-outline-info btn-sm" wire:click="marcarVacaciones">
                                        Confirmar
                                    </button>
                                @endif
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>

            <!-- Secciones -->
            <div class="col-md-8">
                <div class="d-flex justify-content-center mb-4">
                    <button class="tab-button @if ($activeSection === 'profile') active @endif"
                        wire:click="setActiveSection('profile')">
                        Actualizar Perfil
                    </button>
                    <button class="tab-button @if ($activeSection === 'password') active @endif"
                        wire:click="setActiveSection('password')">
                        Cambiar Contraseña
                    </button>
                    @if (!Auth::user()->hasRole('Usuario') && !Auth::user()->hasRole('Aprobador'))
                        <button class="tab-button @if ($activeSection === 'vacaciones') active @endif"
                            wire:click="setActiveSection('vacaciones')">
                            Vacaciones / BK
                        </button>
                    @endif
                </div>

                <!-- PERFIL -->
                @if ($activeSection === 'profile')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Actualizar Perfil</h5>
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="updateProfile" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Nombre Completo</label>
                                    <input type="text" wire:model.defer="name" class="form-control form-control-sm"
                                        id="name">
                                    @error('name')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" wire:model.defer="email" class="form-control form-control-sm"
                                        id="email">
                                    @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div wire:ignore class="form-group mb-3">
                                    <label for="email" class="form-label">Área</label>
                                    <select id="area" class="select2">
                                        <option value="">Seleccionar...</option>
                                        <option value="Administración Planta">Administración Planta</option>
                                        <option value="Administrativa y Financiera">Administrativa y Financiera</option>
                                        <option value="Tecnología">Tecnología</option>
                                        <option value="Producción">Producción</option>
                                        <option value="Comercial">Comercial</option>
                                        <option value="Logística">Logística</option>
                                        <option value="Mantenimiento">Mantenimiento</option>
                                    </select>
                                    @error('area')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="profile_photo" class="form-label">Foto de Perfil</label>
                                    <div class="custom-file">
                                        <input type="file" wire:model="profile_photo" class="custom-file-input"
                                            id="profile_photo">
                                        <label class="custom-file-label" for="profile_photo">Seleccionar archivo</label>
                                        @error('profile_photo')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div wire:loading wire:target="profile_photo" class="loader"></div>
                                    @if ($profile_photo)
                                        <div class="mt-2">
                                            <img src="{{ $profile_photo->temporaryUrl() }}" class="profile-img">
                                        </div>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-info w-100"><i class="fas fa-save me-1"></i>
                                    Actualizar Perfil</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- CONTRASEÑA -->
                @if ($activeSection === 'password')
                    <div class="card shadow-sm">
                        <div
                            class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Cambiar Contraseña</h5>
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="updatePassword">
                                <div class="form-group mb-3">
                                    <label for="current_password" class="form-label">Contraseña Actual</label>
                                    <input type="password" wire:model.defer="current_password"
                                        class="form-control form-control-sm" id="current_password">
                                    @error('current_password')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" wire:model.defer="password"
                                        class="form-control form-control-sm" id="password">
                                    @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Nueva
                                        Contraseña</label>
                                    <input type="password" wire:model.defer="password_confirmation"
                                        class="form-control form-control-sm" id="password_confirmation">
                                    @error('password_confirmation')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-secondary w-100"><i
                                        class="fas fa-key me-1"></i>
                                    Actualizar Contraseña</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- VACACIONES / BK -->
                {{-- @if ($activeSection === 'vacaciones')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fas fa-plane me-2"></i> Configurar Backups</h5>
                        </div>
                        <div class="card-body">
                            <p>Selecciona un agente backup por flujo o aplicación para cubrir tus tickets durante
                                vacaciones.</p>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Nombre</th>
                                            <th>Grupo</th>
                                            <th>Agente Backup</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($flujos as $flujo)
                                            <tr>
                                                <td><span class="badge bg-secondary">Flujo</span></td>
                                                <td>{{ $flujo->subcategoria->nombre ?? 'Sin nombre' }}</td>
                                                <td>{{ $flujo->grupo->nombre ?? 'Sin grupo' }}</td>
                                                <td>
                                                    <select wire:model="backupAsignaciones.flujo:{{ $flujo->id }}"
                                                        class="form-select form-select-sm">
                                                        <option value="">-- Seleccionar --</option>
                                                        @foreach ($agentes as $agente)
                                                            <option value="{{ $agente->id }}">{{ $agente->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No hay flujos
                                                    asignados.</td>
                                            </tr>
                                        @endforelse

                                        @foreach ($apps as $app)
                                            <tr>
                                                <td><span class="badge bg-success">Aplicación</span></td>
                                                <td>{{ $app->nombre }}</td>
                                                <td>{{ $app->grupo->nombre ?? 'Sin grupo' }}</td>
                                                <td>
                                                    <select wire:model="backupAsignaciones.app:{{ $app->id }}"
                                                        class="form-select form-select-sm">
                                                        <option value="">-- Seleccionar --</option>
                                                        @foreach ($agentes as $agente)
                                                            <option value="{{ $agente->id }}">{{ $agente->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button wire:click="guardarBackups" class="btn btn-success btn-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Backups
                                </button>

                                @php
                                    // Cantidad total de flujos y aplicaciones asignadas al agente
                                    $totalItems = count($flujos ?? []) + count($apps ?? []);

                                    // Cuántos selects tienen valor asignado
                                    $asignados = collect($backupAsignaciones ?? [])
                                        ->filter(fn($v) => !empty($v))
                                        ->count();

                                    // Si faltan asignaciones, desactivar botón
                                    $faltan = $totalItems > $asignados;
                                @endphp

                                <button wire:click="marcarVacaciones" class="btn btn-outline-info btn-sm"
                                    @if ($faltan) disabled @endif>
                                    <i class="fas fa-plane me-1"></i> Marcar Vacaciones
                                </button>
                            </div>

                            @if ($faltan)
                                <div class="alert alert-warning mt-2 py-2 text-center">
                                    ⚠️ Debes asignar un backup a todos los flujos y aplicaciones antes de continuar.
                                </div>
                            @endif

                        </div>
                    </div>
                @endif --}}

                <!-- VACACIONES / BK -->
                @if ($activeSection === 'vacaciones')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fas fa-plane me-2"></i> Configurar Backups</h5>
                        </div>
                        <div class="card-body">
                            <p>
                                Selecciona primero un <strong>agente backup global</strong> y luego verifica si deseas
                                mantenerlo
                                para todos los flujos y aplicaciones. Puedes cambiar algunos manualmente antes de
                                guardar.
                            </p>

                            <div class="mb-3">
                                <label for="backupGlobal" class="form-label">Agente Backup Global:</label>
                                <div class="input-group">
                                    <select wire:model="nuevoAsignadoId" id="backupGlobal"
                                        class="form-select form-select-sm">
                                        <option value="">-- Seleccionar agente --</option>
                                        @foreach ($agentes as $agente)
                                            <option value="{{ $agente->id }}">{{ $agente->name }}</option>
                                        @endforeach
                                    </select>
                                    <button wire:click="asignarBackupGlobal" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-sync-alt me-1"></i> Aplicar Global
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Nombre</th>
                                            <th>Grupo</th>
                                            <th>Agente Backup</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($flujos as $flujo)
                                            <tr>
                                                <td><span class="badge bg-secondary">Flujo</span></td>
                                                <td>{{ $flujo->subcategoria->nombre ?? 'Sin nombre' }}</td>
                                                <td>{{ $flujo->grupo->nombre ?? 'Sin grupo' }}</td>
                                                <td>
                                                    <select wire:model="backupAsignaciones.flujo:{{ $flujo->id }}"
                                                        class="form-select form-select-sm">
                                                        <option value="">-- Seleccionar --</option>
                                                        @foreach ($agentes as $agente)
                                                            <option value="{{ $agente->id }}">{{ $agente->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No hay flujos
                                                    asignados.</td>
                                            </tr>
                                        @endforelse

                                        @foreach ($apps as $app)
                                            <tr>
                                                <td><span class="badge bg-success">Aplicación</span></td>
                                                <td>{{ $app->nombre }}</td>
                                                <td>{{ $app->grupo->nombre ?? 'Sin grupo' }}</td>
                                                <td>
                                                    <select wire:model="backupAsignaciones.app:{{ $app->id }}"
                                                        class="form-select form-select-sm">
                                                        <option value="">-- Seleccionar --</option>
                                                        @foreach ($agentes as $agente)
                                                            <option value="{{ $agente->id }}">{{ $agente->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @php
                                $totalItems = count($flujos ?? []) + count($apps ?? []);
                                $asignados = collect($backupAsignaciones ?? [])
                                    ->filter(fn($v) => !empty($v))
                                    ->count();
                                $faltan = $totalItems > $asignados;
                            @endphp

                            <div class="d-flex justify-content-between mt-3">
                                <button wire:click="guardarBackups" class="btn btn-success btn-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Backups
                                </button>

                                <button wire:click="marcarVacaciones" class="btn btn-outline-info btn-sm"
                                    @if ($faltan || !$backupsGuardados) disabled @endif>
                                    <i class="fas fa-plane me-1"></i> Marcar Vacaciones
                                </button>
                            </div>

                            @if ($faltan)
                                <div class="alert alert-warning mt-2 py-2 text-center">
                                    ⚠️ Debes asignar un backup a todos los flujos y aplicaciones antes de continuar.
                                </div>
                            @elseif(!$backupsGuardados)
                                <div class="alert alert-warning mt-2 py-2 text-center">
                                    ⚠️ Guarda tus backups antes de marcar vacaciones.
                                </div>
                            @endif

                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                Livewire.on('showToast', (data) => {
                    toastRight(data.type, data.message);
                });
            });

            $('.select2').select2();
            $('#area').on('change', function() {
                @this.set('area', $(this).val());
            });
        </script>
    @endpush
</div>
