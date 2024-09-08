<div>
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-control-sm {
            font-size: 0.875rem;
            padding: 0.5rem;
        }

        .card {
            border-radius: 10px;
        }

        .card-header {
            font-weight: bold;
        }

        .btn {
            border-radius: 25px;
        }

        .list-unstyled {
            margin-top: 10px;
        }
    </style>

    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar: Información del usuario -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <img src="{{ Auth::user()->adminlte_image() }}" class="rounded-circle mb-3 profile-img" alt="Foto de perfil">
                        <h4 class="text-primary">{{ Auth::user()->name }}</h4>
                        <p class="text-muted">{{ Auth::user()->adminlte_desc() }}</p>
                        <p class="mb-2"><strong>Sociedad:</strong> {{ $sociedad->nombre ?? 'No asignada' }}</p>
                        <p class="mb-0"><strong>Grupos de Atención:</strong></p>
                        <ul class="list-unstyled">
                            @foreach ($grupos as $grupo)
                                <li>{{ $grupo->nombre }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main section: Actualizar perfil y Cambiar contraseña -->
            <div class="col-md-8">
                <div class="row">
                    <!-- Actualizar Perfil -->
                    <div class="col-md-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Actualizar Perfil</h5>
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent="updateProfile" enctype="multipart/form-data">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Nombre Completo</label>
                                        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">
                                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" wire:model="email" class="form-control form-control-sm" id="email">
                                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="profile_photo" class="form-label">Foto de Perfil</label>
                                        <div class="custom-file">
                                            <input type="file" wire:model="profile_photo" class="custom-file-input" id="profile_photo">
                                            <label class="custom-file-label" for="profile_photo">Seleccionar archivo</label>
                                            @error('profile_photo') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-info w-100"><i class="fas fa-save me-1"></i> Actualizar Perfil</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Cambiar Contraseña -->
                    <div class="col-md-5">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Cambiar Contraseña</h5>
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent="updatePassword">
                                    <div class="form-group mb-3">
                                        <label for="current_password" class="form-label">Contraseña Actual</label>
                                        <input type="password" wire:model="current_password" class="form-control form-control-sm" id="current_password">
                                        @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">Nueva Contraseña</label>
                                        <input type="password" wire:model="password" class="form-control form-control-sm" id="password">
                                        @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                        <input type="password" wire:model="password_confirmation" class="form-control form-control-sm" id="password_confirmation">
                                        @error('password_confirmation') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-key me-1"></i> Actualizar Contraseña</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluyendo Toastr -->
    <script>
        window.addEventListener('toast', event => {
            toastr[event.detail.type](event.detail.message);
        });
    </script>
</div>
