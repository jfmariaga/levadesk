<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Información del Usuario</h3>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="updateProfile">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" wire:model="name" class="form-control" id="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" wire:model="email" class="form-control" id="email">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h3 class="card-title">Cambiar Contraseña</h3>
        </div>
        <div class="card-body">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="updatePassword">
                <div class="form-group">
                    <label for="current_password">Contraseña Actual</label>
                    <input type="password" wire:model="current_password" class="form-control" id="current_password">
                    @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <input type="password" wire:model="password" class="form-control" id="password">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                    <input type="password" wire:model="password_confirmation" class="form-control" id="password_confirmation">
                    @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-info">Actualizar Contraseña</button>
            </form>
        </div>
    </div>
</div>
