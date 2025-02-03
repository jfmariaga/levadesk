<div class="card shadow-sm border-0">
    @if (auth()->user()->id == 23 || auth()->user()->hasAnyRole('Admin'))
        <div class="card-body">
            <div class="row g-3 align-items-center mb-4">
                <!-- Campo de búsqueda -->
                <div class="col-lg-4">
                    <input type="text" wire:model="search" class="form-control form-control-lg"
                        placeholder="Buscar tickets...">
                </div>

                <!-- Filtro de fecha inicial -->
                <div class="col-lg-3">
                    <input type="date" wire:model="startDate" class="form-control form-control-lg"
                        placeholder="Fecha inicial">
                </div>

                <!-- Filtro de fecha final -->
                <div class="col-lg-3">
                    <input type="date" wire:model="endDate" class="form-control form-control-lg"
                        placeholder="Fecha final">
                </div>
            </div>

            @if ($tickets->count())
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Fecha</th>
                                <th>Código</th>
                                <th>Título</th>
                                <th>Prioridad</th>
                                <th>Sociedad</th>
                                <th>Tipo de Solicitud</th>
                                <th>Categoría</th>
                                <th>Subcategoría</th>
                                <th>Usuario</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr class="text-center">
                                    <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $ticket->nomenclatura }}</td>
                                    <td>{{ $ticket->titulo }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $ticket->prioridad == 'CRITICA' ? 'danger' : ($ticket->prioridad == 'ALTA' ? 'warning' : 'success') }}">
                                            {{ $ticket->prioridad }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->sociedad->nombre }}</td>
                                    <td>{{ $ticket->tipoSolicitud->nombre }}</td>
                                    <td>{{ $ticket->categoria->nombre }}</td>
                                    <td>{{ $ticket->subcategoria->nombre }}</td>
                                    <td>{{ $ticket->usuario->name }}</td>
                                    <td>{{ $ticket->estado->nombre }}</td>
                                    <td>
                                        <a target="_blank" href="gestionar?ticket_id={{ $ticket->id }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="far fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="alert alert-warning text-center">
                    No se encontraron tickets en el rango seleccionado.
                </div>
            @endif
        </div>
    @else
        <h2 class="text-center"><span >No tienes permisos para este buscador</span></h2>
    @endif
</div>
