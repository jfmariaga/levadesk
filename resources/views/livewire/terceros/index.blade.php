<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Gestión de Terceros</h3>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" wire:model="nombre" placeholder="Nombre del tercero">
                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea class="form-control" wire:model="descripcion" placeholder="Descripción (opcional)"></textarea>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" wire:model="activo" id="activo">
                    <label class="form-check-label" for="activo">Activo</label>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Listado de Terceros</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($terceros as $t)
                        <tr>
                            <td>{{ $t->nombre }}</td>
                            <td>{{ $t->descripcion ?? '-' }}</td>
                            <td>{{ $t->activo ? 'Sí' : 'No' }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" wire:click="edit({{ $t->id }})">Editar</button>
                                <button class="btn btn-sm btn-danger" wire:click="delete({{ $t->id }})">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
