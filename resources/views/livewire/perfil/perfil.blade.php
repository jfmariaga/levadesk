<div>
    <style>
        /* Estilos generales */
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
            /* Color para la parte superior */
            height: 10px;
        }

        .user-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: black;
            /* Nombre del usuario en negro */
        }

        .text-muted {
            color: #6c757d;
        }

        /* Botones minimalistas con buen contraste */
        .tab-button {
            background-color: #77adb6;
            /* Color de fondo personalizado */
            border: 1px solid #77adb6;
            border-radius: 25px;
            color: white;
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .tab-button.active {
            background-color: #138496;
            /* Color más oscuro para el estado activo */
            color: white;
            border-color: #138496;
        }

        .tab-button:hover {
            background-color: #138496;
            /* Cambiar el color en hover */
            color: white;
        }

        .tab-button:focus {
            outline: none;
            box-shadow: none;
        }

        /* Loader */
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

        /* Centrar el contenido */
        .form-group {
            width: 100%;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        /* Estilos para el switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #17a2b8;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar: Información del usuario -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header-top"></div> <!-- Parte superior con color -->
                    <div class="card-body text-center">
                        <img src="{{ $profile_photo_preview ?? Auth::user()->adminlte_image() }}"
                            class="rounded-circle mb-3 profile-img" alt="Foto de perfil">
                        <h4 class="user-name">{{ Auth::user()->name }}</h4> <!-- Nombre en negro -->
                        <p class="text-muted">{{ Auth::user()->adminlte_desc() }}</p>
                        <p class="mb-2"><strong>Sociedad:</strong> {{ $sociedad->nombre ?? 'No asignada' }}</p>
                        <p class="mb-0"><strong>Grupos de Atención:</strong></p>
                        <ul class="list-unstyled">
                            @foreach ($grupos as $grupo)
                                <li>{{ $grupo->nombre }}</li>
                            @endforeach
                        </ul>
                        @if (!Auth::user()->hasRole('Usuario'))
                            <div class="form-group d-flex flex-column align-items-center">
                                <div class="d-flex align-items-center mb-3">
                                    <label class="mb-0">¿En vacaciones?</label>
                                    <label class="switch ml-2">
                                        <input type="checkbox" wire:model="en_vacaciones">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <!-- Selector de Agente -->
                                <div class="form-group mb-3">
                                    <label for="agente">Asignar tickets a:</label>
                                    <select wire:model="nuevoAsignadoId" id="agente"
                                        class="form-control form-control-sm">
                                        <option value="">Seleccionar automáticamente</option>
                                        @foreach ($agentes as $agente)
                                            <option value="{{ $agente->id }}">{{ $agente->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button class="btn btn-outline-info btn-sm" wire:click="marcarVacaciones">Confirmar
                                    Vacaciones</button>
                            </div>
                        @endif


                    </div>
                </div>
            </div>

            <!-- Main section: Pestañas para "Actualizar perfil" y "Cambiar contraseña" -->
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
                </div>

                <!-- Sección de perfil -->
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

                                    <!-- Mostrar loader mientras se carga la imagen -->
                                    <div wire:loading wire:target="profile_photo" class="loader"></div>

                                    <!-- Mostrar vista previa de la imagen cargada -->
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

                <!-- Sección de contraseña -->
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
        </script>
    @endpush

</div>
