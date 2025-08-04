<div>
    <style>
        .custom-ticket-card {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .custom-ticket-card h5 {
            font-size: 16px;
            font-weight: 600;
            color: #0E69B2;
            /* color corporativo */
            margin-bottom: 6px;
            border-bottom: 1px solid #e1e1e1;
            padding-bottom: 4px;
        }

        .section {
            margin-bottom: 15px;
        }

        .info-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .info-list li {
            padding: 4px 0;
            border-bottom: 1px dotted #e0e0e0;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .required {
            color: red;
            font-weight: bold;
        }

        .tipo-cambio {
            font-size: 12px;
            margin-top: 5px;
            display: inline-block;
        }

        .btn-outline-primary {
            border-color: #0E69B2;
            color: #0E69B2;
        }

        .btn-outline-primary:hover {
            background-color: #0E69B2;
            color: #fff;
        }

        .card {
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-bottom: 1px solid #eee;
            background-color: #f8f9fa;
        }

        .card-body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        h5 {
            font-size: 1.25rem;
            color: #333;
        }

        p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .solicitud-badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
            background-color: #6e6997;
            color: #ffffff;
        }

        .text-right {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .text-right .solicitud-badge {
            margin-left: 8px;
        }

        .timeline-horizontal {
            display: flex;
            overflow-x: auto;
            padding: 10px 20px;
            /* Reducir el padding para menos espacio */
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .timeline-item {
            display: inline-block;
            background: #f4f4f4;
            border-radius: 8px;
            /* Esquinas menos redondeadas */
            margin: 5px 10px;
            /* Menor separación entre items */
            padding: 8px 16px;
            /* Padding interno más pequeño */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            font-size: 0.8rem;
            /* Texto más pequeño */
        }

        .timeline-icon {
            width: 20px;
            /* Icono más pequeño */
            height: 20px;
            background-color: #777;
            border-radius: 50%;
            position: absolute;
            left: -10px;
            /* Ajustar posición a la izquierda */
            top: 10px;
            /* Centrar respecto al contenido */
        }

        .timeline-content {
            padding-left: 35px;
            /* Ajustar para el icono más pequeño */
        }

        .timeline-content h2 {
            font-size: 0.85rem;
            /* Tamaño de texto reducido para fecha/hora */
            color: #555;
        }

        .timeline-content p {
            font-size: 0.75rem;
            /* Tamaño de texto reducido para detalles */
            color: #666;
        }

        .hidden {
            display: none;
        }

        .btn-flecha {
            background: none;
            border: none;
            cursor: pointer;
        }

        .btn-flecha-gestion {
            background: none;
            border: none;
            cursor: pointer;
        }

        .list-group-item {
            display: flex;
            align-items: center;
            /* Alinea verticalmente los elementos */
            justify-content: start;
            /* Alinea horizontalmente al inicio */
            background-color: transparent;
            border: none;
            padding: 5px 10px;
        }

        .comentario {
            border-color: #333;
        }

        .btn-color-azul {
            background: #6790e7;
            color: #eee
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .card-body {
            background-color: #fff;
        }

        textarea {
            resize: none;
            padding: 10px;
            border-radius: 0;
            font-size: 14px;
        }

        .input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .input-group-prepend {
            margin-right: 10px;
        }

        .input-group-prepend i {
            font-size: 1.5rem;
            /* Tamaño del ícono */
            color: gray;
            cursor: pointer;
        }

        .icono-notificacion {
            font-size: 1.2rem;
            /* Tamaño del ícono */
            color: rgb(235, 105, 105);
            cursor: pointer;
            background: none;
            border: none;
        }

        .icono-colaborador {
            font-size: 1.2rem;
            /* Tamaño del ícono */
            color: rgb(107, 179, 137);
            cursor: pointer;
            background: none;
            border: none;
        }

        .icono-todo {
            font-size: 1.2rem;
            /* Tamaño del ícono */
            color: rgb(86, 200, 253);
            cursor: pointer;
            background: none;
            border: none;
        }

        .btn {
            margin-left: auto;
        }

        .border-file {
            border: 1px solid #ddd;
        }

        .p-2 {
            padding: 0.5rem;
        }

        .rounded-file {
            border-radius: 4px;
        }

        .text-success-file {
            color: #28a745;
        }

        .text-muted-file {
            color: #6c757d;
        }

        .btn-link-file {
            background: none;
            border: none;
            cursor: pointer;
        }

        .btn-link-file:hover {
            text-decoration: none;
        }

        .text-danger-file {
            color: #dc3545;
        }

        .flex-grow-1-file {
            flex-grow: 1;
        }

        .contar-recordatorios {
            color: black;
            border-top-left-radius: 3px;
            border: #333;
            margin-left: -7px;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .contar-tareas {
            color: black;
            border-top-left-radius: 3px;
            border: #333;
            margin-left: -5px;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .ans-badge {
            font-size: 0.9rem;
        }

        /* Ajustar tamaño para dispositivos móviles */
        @media (max-width: 768px) {
            .ans-badge {
                font-size: 0.7rem;
                /* Reducir el tamaño en pantallas pequeñas */
                padding: 5px;
            }
        }
    </style>
    @if ($ticket)
        <div class="container-fluid">
            <div class="row">
                <a href="{{ route('gestion') }}" class="btn btn-sm btn-outline-secondary ml-3 mb-2 float-right"><i
                        class="fas fa-angle-double-left"></i> Volver</a>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <div class="col-12 mt-1 mr-2">
                            @if ($ticket->solucion() && $ticket->estado_id != 4)
                                <span wire:ignore class="alert alert-success color-verde-claro float-right">
                                    Solución indicada en el comentario
                                    #{{ $ticket->comentarios->search($ticket->solucion()) + 1 }}.
                                </span>
                            @endif
                            <div class="col-12 mt-1 mr-2">
                                @php
                                    $comentarioCalificado = $ticket
                                        ->comentarios()
                                        ->whereNotNull('calificacion')
                                        ->first();
                                @endphp

                                @if ($ticket->estado_id == 4)
                                    @if ($comentarioCalificado)
                                        <div
                                            class="alert alert-light d-flex justify-content-between align-items-center shadow-sm p-3 mb-3 rounded">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success mr-2"
                                                    style="font-size: 24px;"></i>
                                                <div>
                                                    <strong>El usuario aceptó la solución.</strong>
                                                    <p class="mb-0 text-muted">
                                                        {{ $comentarioCalificado->comentario_calificacion ? $comentarioCalificado->comentario_calificacion : 'Sin comentarios.' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $comentarioCalificado->calificacion ? 'text-warning' : 'text-muted' }}"
                                                        style="font-size: 24px;"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="alert alert-light d-flex justify-content-between align-items-center shadow-sm p-3 mb-3 rounded">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success mr-2"
                                                    style="font-size: 24px;"></i>
                                                <div>
                                                    <strong>Sistema aceptó la solución por expiración.</strong>
                                                    <p class="mb-0 text-muted">Sin comentarios.</p>
                                                </div>
                                            </div>
                                            <div class="rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star text-warning" style="font-size: 24px;"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    @endif
                                @elseif ($ticket->estado_id === 5)
                                    <div
                                        class="alert alert-light d-flex justify-content-between align-items-center shadow-sm p-3 mb-3 rounded">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success mr-2"
                                                style="font-size: 24px;"></i>
                                            <div>
                                                <strong>Ticket calificado por el sistema.</strong>
                                                <p class="mb-0 text-muted">Sin comentarios.</p>
                                            </div>
                                        </div>
                                        <div class="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $comentarioCalificado->calificacion ? 'text-warning' : 'text-muted' }}"
                                                    style="font-size: 24px;"></i>
                                            @endfor
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-header col-md-12">
                            <div class="mb-1 d-flex flex-column flex-md-row align-items-center p-2"
                                style="background-color: #eeeeee">
                                <div class="col-12 col-md-4 mt-2 text-center text-md-left">
                                    @if ($ticket->cambio && $ticket->cambio->estado == 'aprobado' && $ticket->estado_id == 3)
                                        <p><strong>CAMBIO EN ATENCIÓN</strong></p>
                                    @else
                                        <p><strong>{{ $ticket->estado->nombre }}</strong></p>
                                    @endif
                                </div>
                                <div class="col-12 col-md-8">
                                    <p class="text-center text-md-right mt-2">
                                        @if ($ticket->estado_id != 4 && $ticket->estado_id != 5)
                                            @php
                                                $tiempoRestante = $ticket->tiempo_restante;
                                            @endphp

                                            @if ($tiempoRestante > 900)
                                                <span class="badge badge-success p-2 ans-badge">
                                                    <i class="fas fa-check-circle"></i> Tiempo restante ANS
                                                    {{ $tipoANS }}:
                                                    {{ $this->formatTiempoRestante($tiempoRestante) }}
                                                </span>
                                            @elseif ($tiempoRestante <= 900 && $tiempoRestante > 300)
                                                <span class="badge badge-warning p-2 ans-badge">
                                                    <i class="fas fa-exclamation-circle"></i> Tiempo restante ANS
                                                    {{ $tipoANS }}:
                                                    {{ $this->formatTiempoRestante($tiempoRestante) }}
                                                </span>
                                            @elseif ($tiempoRestante > 0)
                                                <span class="badge badge-danger p-2 ans-badge">
                                                    <i class="fas fa-times-circle"></i> Tiempo restante ANS
                                                    {{ $tipoANS }}:
                                                    {{ $this->formatTiempoRestante($tiempoRestante) }}
                                                </span>
                                            @else
                                                <span class="badge badge-danger p-2 ans-badge">
                                                    <i class="fas fa-times-circle"></i> No cumpliste con el ANS
                                                    {{ $tipoANS }}
                                                </span>
                                            @endif
                                        @endif

                                        <span
                                            class="solicitud-badge font-weight-bold">{{ $ticket->nomenclatura }}</span>

                                        <button wire:click="recordatorios" class="ml-1 icono-notificacion">
                                            <i class="fas fa-bell"></i>
                                        </button>
                                        @if ($ticket->recordatorios)
                                            <span
                                                class="contar-recordatorios">{{ count($ticket->recordatorios) }}</span>
                                        @endif

                                        <button wire:click="tareas" class="icono-todo">
                                            <i class="fas fa-tasks"></i>
                                        </button>
                                        @if ($ticket->tareas)
                                            <span class="contar-tareas">{{ count($ticket->tareas) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="form-row align-items-start mt-2 mb-1">
                                @if ($recordatorio)
                                    <!-- Tarjeta de creación de nuevo recordatorio -->
                                    @if ($ticket->estado_id != 4 && $ticket->estado_id != 5)
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p><strong>Nuevo recordatorio</strong></p>
                                                    <input type="datetime-local" id="reminder_at" name="reminder_at"
                                                        class="form-control" value="" wire:model="reminder_at">
                                                    @error('reminder_at')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <textarea name="detalle" class="form-control mt-1" cols="30" rows="3" wire:model="desDetalle"
                                                        placeholder="Descripción del recordatorio (opcional)"></textarea>
                                                    <button wire:click="guardarRecordatorio"
                                                        class="btn btn-outline-info btn-sm float-right mt-1">
                                                        Guardar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Tarjeta de visualización de recordatorios -->
                                    <div class="col-md-8">
                                        <div class="card" style="height: 300px; overflow-y: auto;">
                                            <div class="card-body p-2">
                                                @if ($ticket->recordatorios)
                                                    <ul class="list-group">
                                                        @foreach ($ticket->recordatorios as $recordatorio)
                                                            <?php
                                                            $hora = date('d-m-Y H:i:s');
                                                            $horaRecordatorio = \Carbon\Carbon::parse($recordatorio->reminder_at)->format('d-m-Y H:i:s');
                                                            ?>
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    @if ($hora >= $horaRecordatorio)
                                                                        <del>
                                                                            Recordatorio para el
                                                                            {{ $horaRecordatorio }}:
                                                                            {{ $recordatorio->detalle }}
                                                                        </del>
                                                                    @else
                                                                        Recordatorio para el {{ $horaRecordatorio }}:
                                                                        <i>{{ $recordatorio->detalle }}</i>
                                                                    @endif
                                                                </div>
                                                                <button
                                                                    wire:click="eliminarRecordatorio({{ $recordatorio->id }})"
                                                                    class="btn btn-link text-danger p-0 ml-2">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-row align-items-start mt-2 mb-1">
                                {{-- @if ($tarea)
                                    <!-- Tarjeta de creación de nueva tarea -->
                                    @if ($ticket->estado_id != 4 && $ticket->estado_id != 5 && Auth::id() == $ticket->asignado_a)
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p><strong>Nueva tarea</strong></p>
                                                    <input type="text" class="form-control" wire:model="titulo"
                                                        placeholder="Título de la tarea" required>
                                                    @error('titulo')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <br>
                                                    <textarea wire:model="descripcion" class="form-control" placeholder="Descripción"></textarea>
                                                    <br>
                                                    <p><strong>Fecha límite para esta tarea:</strong></p>
                                                    <input type="datetime-local" wire:model="fecha_cumplimiento"
                                                        class="form-control">
                                                    @error('fecha_cumplimiento')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <br>
                                                    <select wire:model="asignado_a" class="form-control">
                                                        <option value="">No asignado</option>
                                                        @foreach ($ticket->colaboradores as $colaborador)
                                                            <option value="{{ $colaborador->id }}">
                                                                {{ $colaborador->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button wire:click="crearTarea"
                                                        class="btn btn-outline-info btn-sm float-right mt-1">
                                                        Guardar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Tarjeta de visualización de tareas -->
                                    <div class="col-md-8">
                                        <div class="card" style="height: 300px; overflow-y: auto;">
                                            <div class="card-body p-2">
                                                @if ($ticket->tareas)
                                                    <ul class="list-group">
                                                        @foreach ($ticket->tareas as $tarea)
                                                            <li class="list-group-item">
                                                                <div>
                                                                    <strong>{{ $tarea->titulo }}</strong> -
                                                                    {{ $tarea->descripcion }}<br>
                                                                    <span>{{ \Carbon\Carbon::parse($tarea->fecha_cumplimiento)->format('d-m-Y H:i:s') }}</span>
                                                                    <span class="text-muted">-
                                                                        {{ $tarea->estado }}</span><br>
                                                                    @if ($tarea->user_id)
                                                                        <span class="text-muted">Asignado a:
                                                                            {{ $tarea->user->name }}</span>
                                                                    @endif
                                                                </div>
                                                                <!-- Botones de interacción con los estados -->
                                                                @if ($tarea->estado == 'pendiente' && $tarea->user_id == auth()->id())
                                                                    <button
                                                                        wire:click="marcarEnProgreso({{ $tarea->id }})"
                                                                        class="btn btn-outline-warning btn-sm">Marcar
                                                                        como en progreso</button>
                                                                @elseif ($tarea->estado == 'en_progreso' && $tarea->user_id == auth()->id())
                                                                    <button
                                                                        wire:click="marcarCompletada({{ $tarea->id }})"
                                                                        class="btn btn-outline-success btn-sm">Marcar
                                                                        como completada</button>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif --}}
                                @if ($tarea)
                                    @if ($ticket->estado_id != 4 && $ticket->estado_id != 5 && Auth::id() == $ticket->asignado_a)
                                        <div class="col-md-12">
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <p><strong>Nueva tarea</strong></p>
                                                    @if ($ticket->cambio->tipo_cambio == 1)
                                                        <div class="mb-2"
                                                            style="display: flex; align-items: center; gap: 10px;">
                                                            <p class="mb-0">¿Incluye transporte?</p>
                                                            <label class="switch mb-0">
                                                                <input type="checkbox" wire:model="transporte">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    <!-- Título -->
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" wire:model="titulo"
                                                            placeholder="Título de la tarea" required>
                                                        @error('titulo')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <!-- Descripción -->
                                                    <div class="form-group">
                                                        <textarea wire:model="descripcion" class="form-control"
                                                            placeholder="Si la tarea es un transporte, por favor relaciona todos los transportes posibles para este ticket, ya que si asignas una nueva tarea que implique un transporte este sera tratado como un nuevo flujo de aprobación "></textarea>
                                                    </div>

                                                    <!-- Fecha límite y Responsable -->
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <p><strong>Fecha límite para esta tarea:</strong></p>
                                                            <input type="datetime-local"
                                                                wire:model="fecha_cumplimiento" class="form-control">
                                                            @error('fecha_cumplimiento')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Responsable:</strong></p>
                                                            <select wire:model="asignado_a" class="form-control">
                                                                <option value="">No asignado</option>
                                                                @foreach ($ticket->colaboradores as $colaborador)
                                                                    <option value="{{ $colaborador->id }}">
                                                                        {{ $colaborador->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($tarea_id)
                                                        <button wire:click="actualizarTarea"
                                                            class="btn btn-outline-info btn-sm float-right mt-3">
                                                            Actulizar
                                                        </button>
                                                        <button wire:click="resetFormularioTarea"
                                                            class="btn btn-outline-secondary btn-sm float-right mt-3 mr-2">
                                                            Cancelar
                                                        </button>
                                                    @else
                                                        <button wire:click="crearTarea"
                                                            class="btn btn-outline-info btn-sm float-right mt-3">
                                                            Guardar
                                                        </button>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Tarjeta de visualización de tareas -->
                                    <div class="col-md-12">
                                        <div class="card" style="height: 300px; overflow-y: auto;">
                                            <div class="card-body p-2">
                                                @if ($ticket->tareas)
                                                    <ul class="list-group">
                                                        @foreach ($ticket->tareas as $index => $tarea)
                                                            <li class="list-group-item">
                                                                <div>
                                                                    <strong>
                                                                        <h4>{{ $tarea->transporte ? 'Esta tarea incluye transporte' : '' }}
                                                                        </h4>
                                                                    </strong>
                                                                    @if ($tarea->editar && Auth::id() == $ticket->asignado_a)
                                                                        <button
                                                                            wire:click="editarTarea({{ $tarea->id }})"
                                                                            class="btn btn-outline-warning btn-sm">
                                                                            Editar
                                                                        </button>
                                                                    @endif
                                                                    <hr>
                                                                    <strong>{{ $tarea->titulo }}</strong>
                                                                    <p><i>{{ $tarea->descripcion }}</i></p>
                                                                    <span>Fecha de
                                                                        Creación:{{ \Carbon\Carbon::parse($tarea->created_at)->format('d-m-Y H:i:s') }}</span><br>
                                                                    <span>Última
                                                                        Modificación:{{ \Carbon\Carbon::parse($tarea->updated_at)->format('d-m-Y H:i:s') }}</span><br>
                                                                    <span>Fecha limite de
                                                                        ejecución:{{ \Carbon\Carbon::parse($tarea->fecha_cumplimiento)->format('d-m-Y H:i:s') }}</span>
                                                                    <br>
                                                                    <span class="text-muted">
                                                                        <strong>Estado:</strong>
                                                                        {{ $tarea->estado }}</span><br>

                                                                    @if ($tarea->user_id)
                                                                        <span class="text-muted">Responsable:
                                                                            {{ $tarea->user->name }}</span>
                                                                    @endif

                                                                    <div class="mt-3">
                                                                        {{-- Botón "Pedir Confirmación" solo si la tarea está pendiente --}}
                                                                        @if ($ticket->cambio && $loop->index > 0 && $tarea->estado == 'pendiente' && $tarea->user_id == auth()->id())
                                                                            @if ($tarea->autorizado == 0 && $tarea->solicitud_confirmacion == 1)
                                                                                <button
                                                                                    class="btn btn-outline-secondary btn-sm me-2"
                                                                                    disabled>
                                                                                    En espera de aprobación
                                                                                </button>
                                                                            @else
                                                                                <button
                                                                                    wire:click="pedirConfirmacion({{ $tarea->id }})"
                                                                                    class="btn btn-outline-info btn-sm me-2">
                                                                                    Pedir Confirmación
                                                                                </button>
                                                                            @endif
                                                                        @endif

                                                                        {{-- @if ($ticket->cambio && $tarea->estado == 'pendiente' && $tarea->aprobador_id == auth()->id())
                                                                            @if ($tarea->autorizado == 0 && $tarea->solicitud_confirmacion == 1)
                                                                                <button
                                                                                    wire:click="autorizarTarea({{ $tarea->id }})"
                                                                                    class="btn btn-outline-info btn-sm me-2">
                                                                                    Autorizar
                                                                                </button>
                                                                                <button
                                                                                    wire:click="rechazarTarea({{ $tarea->id }})"
                                                                                    class="btn btn-outline-warning btn-sm me-2">
                                                                                    Rechazar
                                                                                </button>
                                                                            @endif
                                                                        @endif --}}

                                                                        @if ($ticket->cambio && $tarea->estado == 'pendiente' && $tarea->aprobador_id == auth()->id())
                                                                            @if ($tarea->autorizado == 0 && $tarea->solicitud_confirmacion == 1)
                                                                                <div x-data="{ accion: '' }"
                                                                                    class="d-flex align-items-center gap-2">
                                                                                    <select x-model="accion"
                                                                                        class="form-select form-select-sm w-auto form-control">
                                                                                        <option value="">--
                                                                                            Seleccionar acción --
                                                                                        </option>
                                                                                        <option value="autorizar">
                                                                                            Autorizar</option>
                                                                                        <option value="rechazar">
                                                                                            Rechazar por falla técnica o
                                                                                            documentación</option>
                                                                                        <option value="editar">Editar
                                                                                            Tarea</option>
                                                                                    </select>
                                                                                    <br>
                                                                                    <button
                                                                                        class="btn btn-sm btn-primary ml-1"
                                                                                        @click="
                                                                                        if (accion === 'autorizar') {
                                                                                            $wire.autorizarTarea({{ $tarea->id }})
                                                                                        } else if (accion === 'rechazar') {
                                                                                            $wire.rechazarTarea({{ $tarea->id }})
                                                                                        } else if (accion === 'editar') {
                                                                                            $wire.modificarTarea({{ $tarea->id }})
                                                                                        }
                                                                                         "
                                                                                        :disabled="accion === ''">
                                                                                        Guardar
                                                                                    </button>
                                                                                </div>
                                                                            @endif
                                                                        @endif



                                                                        {{-- Mostrar mensaje si la tarea fue rechazada --}}
                                                                        @if ($tarea->estado == 'Rechazada')
                                                                            <p class="text-danger fw-bold">
                                                                                El aprobador TI rechazó la ejecución de
                                                                                esta tarea.
                                                                            </p>
                                                                        @endif

                                                                        {{-- Botones de cambio de estado (deshabilitados si hay solicitud pendiente) --}}
                                                                        @if (!($tarea->autorizado == 0 && $tarea->solicitud_confirmacion == 1))
                                                                            @if (($tarea->estado == 'pendiente' || $tarea->estado == 'Aprobada') && $tarea->user_id == auth()->id())
                                                                                <button
                                                                                    wire:click="marcarEnProgreso({{ $tarea->id }})"
                                                                                    class="btn btn-outline-warning btn-sm me-2">
                                                                                    Marcar como en progreso
                                                                                </button>
                                                                            @elseif ($tarea->estado == 'en_progreso' && $tarea->user_id == auth()->id())
                                                                                <button
                                                                                    wire:click="marcarCompletada({{ $tarea->id }})"
                                                                                    class="btn btn-outline-success btn-sm">
                                                                                    Marcar como completada
                                                                                </button>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <hr>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <h5>
                                {{ $ticket->titulo }}, {{ $ticket->descripcion }}
                            </h5>
                            <p><i class="text-muted">{{ $ticket->sociedad->nombre }}>>{{ $ticket->tipoSolicitud->nombre }}>>{{ $ticket->categoria->nombre }}>>
                                    {{ $ticket->subcategoria->nombre }}
                                    {{ $ticket->aplicacion ? '>>' . $ticket->aplicacion->nombre : '' }}</i></p>
                            @if ($ticket->excepcion)
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><strong>Usuario:</strong> {{ $ticket->excepcion->usuario_sap }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Modulo:</strong> {{ $ticket->excepcion->modulo }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Fecha inicio:</strong> {{ $ticket->excepcion->fecha_inicio }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p><strong>Fecha fin:</strong> {{ $ticket->excepcion->fecha_fin }}</p>
                                    </div>
                                </div>
                                <hr>
                            @endif
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Urgencia:</strong> {{ $ticket->urgencia->nombre }}</p>
                                </div>
                                <div class="col-md-3">
                                    @if ($ticket->impacto)
                                        <!-- Verifica si existe un impacto asignado -->
                                        <p><strong>Impacto:</strong> {{ $ticket->impacto->nombre }}</p>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    @if ($prioridad != 'NULL')
                                        <p><strong>Prioridad:</strong> {{ $ticket->prioridad }}</p>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    @foreach ($ticket->archivos as $archivo)
                                        @if ($archivo->comentario_id == null)
                                            <li>
                                                <a href="{{ Storage::url($archivo->ruta) }}" target="_blank">
                                                    {{ str_replace('-', ' ', basename($archivo->ruta, '.pdf')) }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row">
                                <h5>Timeline</h5>
                                <button wire:click="toggleTimeline" class="btn-flecha">
                                    <i class="fas {{ $showTimeline ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                                </button>
                                <h5 class="ml-2">Diagnóstico</h5>
                                <button wire:click="toggleGestion" class="btn-flecha-gestion">
                                    <i class="fas {{ $showGestion ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class=" col-md-12">
                                    @if ($showTimeline)
                                        <div class="timeline-horizontal">
                                            @foreach ($historial as $evento)
                                                <div class="timeline-item">
                                                    <div class="timeline-icon"></div>
                                                    <div class="timeline-content">
                                                        <h2>{{ $evento->created_at }}</h2>
                                                        <p>{{ $evento->detalle }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <hr>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    @if ($showGestion)
                                        <div class="selector-gestion">
                                            <li class="col-12 list-group-item">
                                                @if ($ticket->estado_id == 1 || auth()->user()->hasAnyRole('Admin'))
                                                    <p>¿Recategorizar?</p>
                                                    <div class="float-right">
                                                        <label class="switch"
                                                            style="margin-left:10px; margin-top: 5px">
                                                            <input type="checkbox" wire:model="recategorizar">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <p class="ml-2">¿Reasignar?</p>
                                                    <div class="float-right">
                                                        <label class="switch"
                                                            style="margin-left:10px; margin-top: 5px">
                                                            <input type="checkbox" wire:model="asignar">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if ($ticket->estado_id == 1)
                                                    <p class="ml-2">Asignar impacto</p>
                                                    <div class="float-right">
                                                        <label class="switch"
                                                            style="margin-left:10px; margin-top: 5px">
                                                            <input type="checkbox" wire:model="impacto">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if ($ticket->estado_id != 1)
                                                    @if ($ticket->tipoSolicitud->id == 4)
                                                        <p class="ml-2">¿Gestionar acceso?</p>
                                                        <div class="float-right">
                                                            <label class="switch"
                                                                style="margin-left:10px; margin-top: 5px">
                                                                <input type="checkbox" wire:model="acceso">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if ($ticket->estado_id != 4 && $ticket->estado_id != 5 && $ticket->estado_id != 6)
                                                        @if (
                                                            $ticket->estado_id != 8 &&
                                                                $ticket->estado_id != 11 &&
                                                                $ticket->estado_id != 14 &&
                                                                $ticket->estado_id != 12 &&
                                                                $ticket->estado_id != 17 &&
                                                                $ticket->estado_id != 10)
                                                            <p class="ml-2">¿Escalar a tercero?</p>
                                                            <div class="float-right">
                                                                <label class="switch"
                                                                    style="margin-left:10px; margin-top: 5px">
                                                                    <input type="checkbox" wire:model="escalar">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                        @endif
                                                        <p class="ml-2">¿Requiere cambio?</p>
                                                        <div class="float-right">
                                                            <label class="switch"
                                                                style="margin-left:10px; margin-top: 5px">
                                                                <input type="checkbox" wire:model="cambio">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                            </li>
                                            @if ($recategorizar)
                                                <div class="row select-categorias">
                                                    <div class="col-md-4">
                                                        <p><strong>Tipo de solicitud:</strong> <b
                                                                style="color: red">*</b>
                                                        </p>
                                                        <select id="solicitud_id" class="form-control"
                                                            wire:model="solicitud_id">
                                                            <option value="">Seleccionar...</option>
                                                            @foreach ($tipos_solicitud as $tipo)
                                                                <option value="{{ $tipo->id }}">
                                                                    {{ $tipo->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('solicitud_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-4">
                                                        <p><strong>Categoría:</strong> <b style="color: red">*</b></p>
                                                        <select class="form-control" wire:model="categoria_id">
                                                            @foreach ($categorias as $categoria)
                                                                <option value="{{ $categoria->id }}">
                                                                    {{ $categoria->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('categoria_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Subcategoría:</strong> <b style="color: red">*</b>
                                                        </p>
                                                        <select class="form-control" wire:model="subcategoria_id">
                                                            <option>Seleccionar</option>
                                                            @foreach ($subcategorias as $subcategoria)
                                                                <option value="{{ $subcategoria->id }}">
                                                                    {{ $subcategoria->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('subcategoria_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    @if (
                                                        $subcategoria &&
                                                            in_array($subcategoria->nombre, [
                                                                'SOPORTE DE APLICACIONES',
                                                                'DESARROLLO Y PERSONALIZACIONES',
                                                                'SOLICITUD DE CAPACITACION',
                                                                'INSTALACION Y ACTUALIZACION',
                                                            ]))
                                                        <div class="col-md-4">
                                                            <p><strong>Aplicación:</strong> <b style="color: red">*</b>
                                                            </p>
                                                            <select class="form-control" wire:model="aplicacion_id">
                                                                <option value="">Seleccionar aplicación...
                                                                </option>
                                                                @foreach ($aplicaciones as $aplicacion)
                                                                    <option value="{{ $aplicacion->id }}">
                                                                        {{ $aplicacion->nombre }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('aplicacion_id')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                    <div class="mt-2 col-12">
                                                        <button wire:click="actualizarCategoria"
                                                            class="btn btn-outline-info btn-sm float-right">Actualizar</button>
                                                    </div>
                                                </div>
                                                <hr>
                                            @endif
                                            @if ($asignar)
                                                <div class="row">
                                                    <div class="form-group col-5">
                                                        <p><strong>Reasignar el ticket a:</strong><b
                                                                style="color: red"> *</b></p>
                                                        <div wire:ignore>
                                                            <select class="select2" id="newAgente">
                                                                <option value="">Seleccionar...</option>
                                                                @foreach ($agentes as $agente)
                                                                    <option value="{{ $agente->id }}">
                                                                        {{ $agente->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('selectedNewAgente')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <input type="submit"
                                                            class="btn btn-outline-info btn-sm float-left"
                                                            value="Asignar" wire:click="AsignarNewAgente">
                                                    </div>
                                                    <hr>
                                                </div>
                                            @endif
                                            @if ($impacto)
                                                <div class="row select-categorias">
                                                    <div class="col-md-6">
                                                        <p><strong>Selecciona el impacto para este ticket:</strong> <b
                                                                style="color: red">*</b>
                                                        </p>
                                                        <select id="impacto_id" class="form-control"
                                                            wire:model="impacto_id">
                                                            <option value="">Seleccionar...</option>
                                                            @foreach ($impactos as $i)
                                                                <option value="{{ $i->id }}">
                                                                    {{ $i->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('impacto_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="mt-2 col-12">
                                                        <button wire:click="verificarImpacto"
                                                            class="btn btn-outline-info btn-sm float-right">Actualizar</button>
                                                    </div>
                                                </div>
                                                <hr>
                                            @endif
                                            @if ($acceso)
                                                @if ($ticket->estado_id == 4 && !$ticket->aprobacion)
                                                    <h5>No se ejecutó el flujo de Aprobación</h5>
                                                    <p>El ticket ha sido finalizado sin ejecutar un flujo de aprobación.
                                                    </p>
                                                    <hr>
                                                @elseif ($ticket->aprobacion)
                                                    @if ($ticket->aprobacion->estado === 'pendiente')
                                                        <h5>Flujo de Aprobación en Proceso</h5>
                                                        <p>El flujo de aprobación fue lanzado el
                                                            <strong>{{ $ticket->aprobacion->created_at->format('d/m/Y H:i') }}</strong>.
                                                        </p>
                                                        <p><strong>Líder funcional:</strong>
                                                            {{ $ticket->aprobacion->aprobadorFuncional->name }}</p>
                                                        <p><strong>Aprobador TI:</strong>
                                                            {{ $ticket->aprobacion->aprobadorTi->name }}</p>
                                                        <p>Para ver el estado del flujo, observa el timeline del ticket.
                                                        </p>
                                                        <hr>
                                                        <h5>Editar líderes del flujo</h5>
                                                        <div class="row">
                                                            <div class="form-group col-5">
                                                                <div wire:ignore>
                                                                    <select class="select2" id="aprobadorFuncional"
                                                                        wire:model="selectedFuncional">
                                                                        <option value="">Líder funcional</option>
                                                                        @foreach ($usuarios as $usuario)
                                                                            <option value="{{ $usuario->id }}">
                                                                                {{ $usuario->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-4">
                                                                <div wire:ignore>
                                                                    <select class="select2" id="aprobadorTi"
                                                                        wire:model="selectedTi">
                                                                        <option value="">Aprobador TI</option>
                                                                        @foreach ($aprobadores as $aprobador)
                                                                            <option value="{{ $aprobador->id }}">
                                                                                {{ $aprobador->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-3">
                                                                <button class="btn btn-outline-info btn-sm mt-1"
                                                                    wire:click="actualizarAprobacion">
                                                                    Guardar Cambios
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    @elseif($ticket->aprobacion->estado === 'aprobado_funcional' || $ticket->aprobacion->estado === 'rechazado_ti')
                                                        <h5>Flujo de Aprobación en Proceso</h5>
                                                        <p>El flujo de aprobación fue lanzado el
                                                            <strong>{{ $ticket->aprobacion->created_at->format('d/m/Y H:i') }}</strong>.
                                                        </p>
                                                        <p><strong>Líder funcional:</strong>
                                                            {{ $ticket->aprobacion->aprobadorFuncional->name }}</p>
                                                        <p><strong>Aprobador TI:</strong>
                                                            {{ $ticket->aprobacion->aprobadorTi->name }}</p>
                                                        <p>Para ver el estado del flujo, observa el timeline del ticket.
                                                        </p>
                                                        <hr>
                                                    @elseif ($ticket->aprobacion->estado === 'rechazado_funcional')
                                                        <h5>Flujo de Aprobación Cerrado</h5>
                                                        <p>El flujo de aprobación fue rechazado. Motivo:</p>
                                                        <p><strong>{{ $ticket->aprobacion->comentarios_funcional ?? $ticket->aprobacion->comentarios_ti }}</strong>
                                                        </p>
                                                        <hr>
                                                    @elseif ($ticket->aprobacion->estado === 'aprobado')
                                                        <h5>Flujo de Aprobación Completado</h5>
                                                        <p>El flujo de aprobación ha sido completado y aprobado. Por
                                                            favor ejecuta el requerimiento del usuario.</p>
                                                        <p><strong>Líder funcional:</strong>
                                                            {{ $ticket->aprobacion->aprobadorFuncional->name }}</p>
                                                        <p><strong>Aprobador TI:</strong>
                                                            {{ $ticket->aprobacion->aprobadorTi->name }}</p>
                                                        <hr>
                                                    @endif
                                                @else
                                                    <h5>Crear flujo de aprobación</h5>
                                                    <div class="row">
                                                        <div class="form-group col-5">
                                                            <p><strong>Líder funcional</strong><b style="color: red">
                                                                    *</b></p>
                                                            <div wire:ignore>
                                                                <select class="select2" id="aprobadorFuncional"
                                                                    wire:model="selectedFuncional">
                                                                    <option value="">Seleccionar...</option>
                                                                    @foreach ($usuarios as $usuario)
                                                                        <option value="{{ $usuario->id }}">
                                                                            {{ $usuario->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @error('selectedFuncional')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-5">
                                                            <p><strong>Aprobador TI</strong><b style="color: red">
                                                                    *</b></p>
                                                            <div wire:ignore>
                                                                <select class="select2" id="aprobadorTi"
                                                                    wire:model="selectedTi">
                                                                    <option value="">Seleccionar...</option>
                                                                    @foreach ($aprobadores as $aprobador)
                                                                        <option value="{{ $aprobador->id }}">
                                                                            {{ $aprobador->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @error('selectedTi')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div
                                                            class="form-group col-2 d-flex align-items-center justify-content-end">
                                                            <input type="submit"
                                                                class="btn btn-outline-info btn-sm mt-4"
                                                                value="Iniciar Flujo" wire:click="flujoAprobacion">
                                                        </div>
                                                    </div>
                                                    <hr>
                                                @endif
                                            @endif

                                            @if ($escalar)
                                                @if ($ticket->escalar == false)
                                                    <p>Se cambiará el estado del ticket por: <strong>ANS
                                                            DETENIDO</strong></p>
                                                    <p>Si vas a detener el ANS por escalar a consultoría recuerda
                                                        mantener informado al usuario sobre cualquier novedad
                                                        (Nombre de la consultoría,# del caso y cambio de estados), lo
                                                        puedes
                                                        hacer mediante la caja de comentarios, si es necesario
                                                        adjunta evidencias.
                                                    </p>
                                                    <p>Durante el tiempo que este el estado en: <strong>ANS
                                                            DETENIDO</strong>. No podras marcar respuestas como
                                                        sulución, hasta que cambies manualmente el estado a: <strong>EN
                                                            ATENCIÓN</strong></p>
                                                    <textarea wire:model="justificacion" class="form-control" placeholder="Escribe la justificación aquí..."></textarea>

                                                    @error('justificacion')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="d-flex">
                                                        <button wire:click="consultoria"
                                                            class="float-right btn btn-sm btn-outline-info">Confirmar</button>
                                                    </div>
                                                    <hr>
                                                @elseif ($ticket->escalar == true && $ticket->estado_id == 9)
                                                    <p><strong>Activar ANS</strong></p>
                                                    <p><i>Si cambias el estado, podras marcar respuestas como
                                                            solución</i></p>
                                                    <div class="d-flex col-12">
                                                        <button wire:click="consultoriaCambio"
                                                            class="float-left btn btn-sm btn-outline-info">Activar
                                                            ANS</button>
                                                    </div>
                                                    <hr>
                                                @else
                                                    <div class="justify-content-center align-items-center">
                                                        <p><strong>¿Volver a detener el ANS?</strong></p>
                                                        <textarea wire:model="justificacion" class="form-control" placeholder="Escribe la justificación aquí..."></textarea>

                                                        @error('justificacion')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <button wire:click="consultoria"
                                                            class=" btn btn-sm btn-outline-info">Confirmar</button>
                                                    </div>
                                                    <hr>
                                                @endif
                                            @endif

                                            @if ($cambio)
                                                @if ($ticket->cambio)
                                                    @if ($ticket->cambio->estado === 'pendiente')
                                                        <h5>Flujo de cambio en Proceso</h5>
                                                        <p>El flujo de cambio fue lanzado el
                                                            <strong>{{ $ticket->cambio->created_at->format('d/m/Y H:i') }}</strong>.
                                                        </p>
                                                        <p><strong>Líder funcional:</strong>
                                                            {{ $ticket->cambio->aprobadorFuncionalCambio->name }}</p>
                                                        <p><strong>Aprobador TI:</strong>
                                                            {{ $ticket->cambio->aprobadorTiCambio->name }}</p>
                                                        <p>Para ver el estado del flujo, observa el timeline del ticket.
                                                        </p>
                                                        <hr>
                                                        <h5>Editar líderes del flujo</h5>
                                                        <div class="row">
                                                            <div class="form-group col-5">
                                                                <div wire:ignore>
                                                                    <select class="select2" id="aprobadorFuncional"
                                                                        wire:model="selectedFuncional">
                                                                        <option value="">Líder funcional</option>
                                                                        @foreach ($usuarios as $usuario)
                                                                            <option value="{{ $usuario->id }}">
                                                                                {{ $usuario->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-4">
                                                                <div wire:ignore>
                                                                    <select class="select2" id="aprobadorTi"
                                                                        wire:model="selectedTi">
                                                                        <option value="">Aprobador TI</option>
                                                                        @foreach ($aprobadores as $aprobador)
                                                                            <option value="{{ $aprobador->id }}">
                                                                                {{ $aprobador->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-3">
                                                                <button class="btn btn-outline-info btn-sm mt-1"
                                                                    wire:click="actualizarCambio">
                                                                    Guardar Cambios
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    @elseif($ticket->cambio->estado === 'aprobado_funcional' || $ticket->cambio->estado === 'rechazado_ti')
                                                        <h5>Flujo de cambio en Proceso</h5>
                                                        <p>El flujo de cambio fue lanzado el
                                                            <strong>{{ $ticket->cambio->created_at->format('d/m/Y H:i') }}</strong>.
                                                        </p>
                                                        <p><strong>Líder funcional:</strong>
                                                            {{ $ticket->cambio->aprobadorFuncionalCambio->name }}</p>
                                                        <p><strong>Aprobador TI:</strong>
                                                            {{ $ticket->cambio->aprobadorTiCambio->name }}</p>
                                                        <p>Para ver el estado del flujo, observa el timeline del ticket.
                                                        </p>
                                                        <hr>
                                                    @elseif ($ticket->cambio->estado === 'rechazado_funcional')
                                                        <h5>Flujo de Aprobación Cerrado</h5>
                                                        <p>El flujo de aprobación fue rechazado. Motivo:</p>
                                                        <p><strong>{{ $ticket->cambio->comentarios_funcional ?? $ticket->cambio->comentarios_ti }}</strong>
                                                        </p>
                                                        <hr>
                                                    @elseif ($ticket->cambio->estado === 'aprobado')
                                                        <h5>Flujo de Aprobación Completado</h5>
                                                        <p>El flujo de aprobación ha sido completado y aprobado. Por
                                                            favor ejecuta el requerimiento del usuario.</p>
                                                        <p><strong>Líder funcional:</strong>
                                                            {{ $ticket->cambio->aprobadorFuncionalCambio->name }}</p>
                                                        <p><strong>Aprobador TI:</strong>
                                                            {{ $ticket->cambio->aprobadorTiCambio->name }}</p>
                                                        <hr>
                                                    @endif
                                                @else
                                                    <h5>Crear flujo de cambio</h5>
                                                    <div class="row">
                                                        <div class="form-group col-5">
                                                            <div wire:ignore>
                                                                <select class="select2" id="aprobadorFuncional"
                                                                    wire:model="selectedFuncional">
                                                                    <option value="">Líder funcional</option>
                                                                    @foreach ($usuarios as $usuario)
                                                                        <option value="{{ $usuario->id }}">
                                                                            {{ $usuario->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @error('selectedFuncional')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-3">
                                                            <div wire:ignore>
                                                                <select class="select2" id="aprobadorTi"
                                                                    wire:model="selectedTi">
                                                                    <option value="">Aprobador TI</option>
                                                                    @foreach ($aprobadores as $aprobador)
                                                                        <option value="{{ $aprobador->id }}">
                                                                            {{ $aprobador->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @error('selectedTi')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-3">
                                                            <p> <span class="input-group-prepend">
                                                                    <label for="fileCambio"
                                                                        class="custom-file-upload">
                                                                        <i class="fa fa-paperclip"></i>
                                                                    </label>
                                                                    <input type="file" id="fileCambio"
                                                                        name="file" class="d-none"
                                                                        wire:model="newFileCambio">
                                                                </span>
                                                            </p>
                                                        </div>
                                                        @if ($newFileCambio)
                                                            <div
                                                                class="d-flex ml-1 col-12 align-items-center border-file p-2 rounded-file">
                                                                <div class="mr-2">
                                                                    <i
                                                                        class="fa fa-check-circle text-success-file"></i>
                                                                </div>
                                                                <div class="flex-grow-1-file">
                                                                    <span>{{ $newFileCambio->getClientOriginalName() }}</span>
                                                                </div>
                                                                <div class="text-muted-file">
                                                                    Subida completa
                                                                </div>
                                                                <div class="ml-2">
                                                                    <button
                                                                        class="btn btn-link-file text-danger-file p-0"
                                                                        wire:click="removeFileCambio">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="form-group col-3">
                                                            <input
                                                                type="submit"class="btn btn-outline-info btn-sm mt-4"
                                                                value="Iniciar Flujo" wire:click="flujoCambio">
                                                        </div>
                                                        <div class="col-12 d-flex justify-content-center">
                                                            <div wire:loading wire:target="newFileCambio"
                                                                class="" role="alert">
                                                                <div class="spinner-border text-primary"
                                                                    role="status">
                                                                    <span class="text-center"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <hr>
                            </div>
                            <div class="row ">
                                <div class="col-12 comentario">
                                    @if ($ticket->estado_id != 1)
                                        <div>
                                            @foreach ($ticket->comentarios as $comentario)
                                                <div class="card">
                                                    <div class="direct-chat-infos clearfix mt-1">
                                                        <span
                                                            class="direct-chat-name float-left ml-2">{{ $comentario->user->name ?? 'Anónimo' }}</span>
                                                        <span
                                                            class="direct-chat-timestamp float-left ml-2">{{ $comentario->created_at->format('d M Y h:i a') }}</span>
                                                        @if ($comentario->tipo == 2)
                                                            <span
                                                                class="badge color-verde-claro mr-2 float-right">Solución
                                                                {{ $ticket->comentario += 1 }}
                                                            </span>
                                                        @else
                                                            @if ($comentario->tipo == 3)
                                                                <span
                                                                    class="badge estado-por-iniciar mr-2 float-right">Solución
                                                                    no aceptada
                                                                    {{ $ticket->comentario += 1 }}
                                                                </span>
                                                            @else
                                                                @if (
                                                                    $ticket->cambio &&
                                                                        $ticket->cambio->estado == 'aprobado' &&
                                                                        $comentario->user_id == $ticket->cambio->aprobador_user_id)
                                                                    <div
                                                                        class="d-flex justify-content-end row mr-2 mb-2">
                                                                        @if ($ticket->estado_id == 10 && $comentario->check_comentario == true)
                                                                            <h5 class="badge text-white"
                                                                                style="background-color: #666">Enviado
                                                                                para aprobación de set de
                                                                                pruebas</h5>
                                                                        @elseif($ticket->cambio->check_aprobado_ti == true && $comentario->check_comentario == true)
                                                                            <h5 class="badge text-bg-dark"
                                                                                style="background-color: #a3da92;">
                                                                                {{ $ticket->cambio->aprobadorTiCambio->name }}
                                                                                aprobó el paso a producción</h5>
                                                                        @else
                                                                            <div class="dropdown d-none">
                                                                                @if ($ticket->cambio->check_aprobado_ti != true && $comentario->check_comentario != true)
                                                                                    <button
                                                                                        class="btn btn-info btn-sm dropdown-toggle"
                                                                                        type="button"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false">
                                                                                    </button>
                                                                                @endif
                                                                                <div class="dropdown-menu ">
                                                                                    <button
                                                                                        wire:click="mandarParaAprobacion({{ $comentario->id }})"
                                                                                        class="dropdown-item ">Enviar
                                                                                        para aprobación de set de
                                                                                        pruebas</button>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                                <span
                                                                    class="badge color-respuesta-azul mr-2 float-right">Respuesta
                                                                    {{ $comentario->tipo == 1 ? 'Privada' : '' }}
                                                                    {{ $ticket->comentario += 1 }}
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div
                                                        class="direct-chat-text mr-2 mb-2 {{ $comentario->tipo == 2 ? 'color-verde-claro' : 'bg-light' }} ">
                                                        {!! $comentario->comentario !!}
                                                        @if ($comentario->archivos->count())
                                                            <strong>Archivos:</strong>
                                                            <ul class="list-unstyled">
                                                                @foreach ($comentario->archivos as $archivo)
                                                                    <li>
                                                                        <a href="{{ Storage::url($archivo->ruta) }}"
                                                                            target="_blank">
                                                                            {{ str_replace('-', ' ', basename($archivo->ruta, '.pdf')) }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div>
                                            @if ($ticket->estado_id != 4 && $ticket->estado_id != 5)
                                                <div class="card">
                                                    <div class="card-body p-0">
                                                        <div class="d-flex align-items-start">
                                                            <div wire:ignore class="w-100">
                                                                <textarea name="editor" id="editor" class="form-control border-0" cols="30" rows="5"
                                                                    placeholder="Escribe tu mensaje aquí..."></textarea>
                                                            </div>
                                                            @error('newComment')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-between align-items-center"
                                                        style="background-color: #eeeeee">
                                                        <div class="input-group d-flex justify-content-between">
                                                            <div class="d-flex align-items-center">
                                                                <span class="input-group-prepend">
                                                                    <label for="file" class="custom-file-upload">
                                                                        <i class="fa fa-paperclip"></i>
                                                                    </label>
                                                                    {{-- <input type="file" id="file"
                                                                        name="file" multiple class="d-none"
                                                                        wire:model="newFile"> --}}
                                                                    <input type="file" id="file"
                                                                        name="files[]" multiple class="d-none"
                                                                        wire:model="newFiles">

                                                                </span>
                                                            </div>
                                                            <div class="input-group-append">
                                                                <select wire:model="commentType" id="tipoComentario"
                                                                    class="form-control form-control-sm">
                                                                    <option value="0">Público</option>
                                                                    <option value="1">Privado</option>
                                                                    @if (Auth::id() == $ticket->asignado_a)
                                                                        @php
                                                                            $esCambioSimple =
                                                                                $ticket->cambio &&
                                                                                $ticket->cambio->tipo_cambio == 0;
                                                                        @endphp

                                                                        @if (
                                                                            !in_array($ticket->estado_id, [8, 9, 10, 11, 12, 14, 15, 18]) &&
                                                                                $ticket->finalizar != true &&
                                                                                (($ticket->estado_id == 3 && (!$ticket->cambio || $esCambioSimple)) ||
                                                                                    ($ticket->estado_id == 17 && $ticket->cambio) ||
                                                                                    in_array($ticket->estado_id, [7, 16])))
                                                                            @if (!$ticket->solucion())
                                                                                <option value="2">Solución
                                                                                </option>
                                                                            @endif
                                                                        @endif

                                                                        @if (
                                                                            $ticket->cambio &&
                                                                                $ticket->cambio->estado == 'aprobado' &&
                                                                                $ticket->cambio->check_aprobado_ti == false &&
                                                                                $ticket->cambio->tipo_cambio == true &&
                                                                                $ticket->estado_id != 11 &&
                                                                                $ticket->estado_id != 10 &&
                                                                                $ticket->estado_id != 9 &&
                                                                                $ticket->finalizar != true)
                                                                            @if (!$ticket->solucion())
                                                                                <option value="5">Set de pruebas
                                                                                </option>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                    @if (
                                                                        $ticket->cambio &&
                                                                            $ticket->cambio->check_aprobado_ti == true &&
                                                                            $ticket->colaboradores->contains('id', Auth::id()) &&
                                                                            $ticket->estado_id == 14)
                                                                        @if (!$ticket->solucion())
                                                                            <option value="6">Validar Productivo
                                                                            </option>
                                                                        @endif
                                                                    @endif
                                                                    @if (
                                                                        $ticket->aprobacion &&
                                                                            $ticket->estado_id == 15 &&
                                                                            ($ticket->colaboradores->contains('id', Auth::id()) || $ticket->asignado_a == Auth::id()))
                                                                        @if (!$ticket->solucion())
                                                                            <option value="7">Validar Acceso
                                                                            </option>
                                                                        @endif
                                                                    @endif
                                                                    @if (
                                                                        $ticket->cambio &&
                                                                            $ticket->cambio->tipo_cambio == true &&
                                                                            $ticket->estado_id != 4 &&
                                                                            $ticket->asignado_a == Auth::id() &&
                                                                            $ticket->finalizar != true)
                                                                        <option value="8">Finalizar</option>
                                                                    @endif
                                                                </select>
                                                                <button wire:click="addComment"
                                                                    class="btn btn-outline-info btn-sm">Responder
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-12 d-flex justify-content-center">
                                            <div wire:loading wire:target="newFiles" class="" role="alert">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="text-center"></span>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($newFiles)
                                            @foreach ($newFiles as $file)
                                                <div class="d-flex align-items-center border-file p-2 rounded-file">
                                                    <div class="mr-2">
                                                        <i class="fa fa-check-circle text-success-file"></i>
                                                    </div>
                                                    <div class="flex-grow-1-file">
                                                        <span>{{ $file->getClientOriginalName() }}</span>
                                                    </div>
                                                    <div class="ml-2">
                                                        <button class="btn btn-link-file text-danger-file p-0"
                                                            wire:click="removeFile({{ $loop->index }})">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3" style="position: sticky; top: 20px; align-self: flex-start;">
                    @if ($esSupervisor && $ticket->finalizar == 1)
                        <div class="card">
                            <div class="card-header col-md-12">
                                <div class="d-flex align-items-center" style="background-color: #eeeeee">
                                    <div class="col-md-12">
                                        <h5>Finalizar Ticket</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="estado_aprobacion_funcional">Finalizar Ticket:</label>
                                    <select wire:model="estado_aprobacion_supervisor" id="estado_aprobacion_funcional"
                                        class="form-control">
                                        <option value="">-- Seleccione --</option>
                                        <option value="aprobado_supervisor">SI</option>
                                        <option value="rechazado_supervisor">NO</option>
                                    </select>
                                </div>
                                @if ($estado_aprobacion_supervisor === 'rechazado_supervisor')
                                    <div class="form-group">
                                        <label for="comentariosRechazo">Comentario (Obligatorio si
                                            rechaza):</label>
                                        <textarea wire:model="comentario_rechazo_supervisor"  rows="3" class="form-control"></textarea>
                                    </div>
                                @endif
                                <div class="d-flex">
                                    <button wire:click="aprobarFinalizarTicket"
                                        class="btn btn-outline-info btn-sm float-right">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header col-md-12">
                            <div class="d-flex  align-items-center" style="background-color: #eeeeee">
                                <div class="col-md-6">
                                    <h5>Participantes</h5>
                                </div>
                                <div class="col-md-6">
                                    @if ($ticket->estado_id != 4 && $ticket->estado_id != 5 && Auth::id() == $ticket->asignado_a)
                                        <button wire:click="participantes"
                                            class="ml-1  {{ $participante ? 'icono-notificacion' : 'icono-colaborador' }} float-right">
                                            <i class="fas {{ $participante ? 'fa-minus' : 'fa-plus-square' }}"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- <div class="card-body">
                            @if ($participante)
                                <div class="row">
                                    <div class="form-group col-12">
                                        <p><strong>Agregar un colaborador</strong><b style="color: red"> *</b></p>
                                        <div wire:ignore>
                                            <select class="select2" id="colaborador">
                                                <option value="">Seleccionar...</option>
                                                @foreach ($agentes as $agente)
                                                    <option value="{{ $agente->id }}">{{ $agente->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('selectedUser')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <input type="submit" class="btn btn-outline-info btn-sm float-right"
                                            value="Asignar" wire:click="asignarColaborador">
                                    </div>
                                    <hr>
                                </div>
                            @endif
                            <p><strong>Usuario :</strong> {{ $ticket->usuario->name }}</p>
                            <p><strong>Agente TI :</strong> {{ $ticket->asignado->name }}</p>
                            @if (count($supervisores) > 0)
                                <h5>Supervisores:</h5>
                                @foreach ($supervisores as $s)
                                    <p>{{ $s->name }}</p>
                                @endforeach
                            @endif
                            @if ($ticket->Colaboradors)
                                @foreach ($ticket->colaboradors as $colaborador)
                                    <p><strong>Colaborador :</strong> {{ $colaborador->user->name }}</p>
                                @endforeach
                            @endif
                            @if ($ticket->cambio)
                                <h5>Aprobadores:</h5>
                                <p><strong>Líder funcional:</strong>
                                    {{ $ticket->cambio->aprobadorFuncionalCambio->name }}</p>
                                <p><strong>Aprobador TI:</strong>
                                    {{ $ticket->cambio->aprobadorTiCambio->name }}</p>
                                @if ($ticket->cambio->tipo_cambio !== null)
                                    <p><span
                                            class="badge badge-warning p-2">{{ $ticket->cambio->tipo_cambio ? 'Cambio Complejo' : 'Cambio Simple' }}</span>
                                    </p>
                                @endif
                            @endif
                            @if ($ticket->aprobacion)
                                <h5>Flujo de accesos</h5>
                                <p><strong>Líder funcional:</strong>
                                    {{ $ticket->aprobacion->aprobadorFuncional->name }}</p>
                                <p><strong>Aprobador TI:</strong>
                                    {{ $ticket->aprobacion->aprobadorTi->name }}</p>
                            @endif
                        </div> --}}
                        <div class="card-body custom-ticket-card">
                            @if ($participante)
                                <div class="section mb-3">
                                    <h5>Agregar un colaborador <b class="required">*</b></h5>
                                    <div wire:ignore>
                                        <select class="select2 form-control" id="colaborador">
                                            <option value="">Seleccionar...</option>
                                            @foreach ($agentes as $agente)
                                                <option value="{{ $agente->id }}">{{ $agente->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('selectedUser')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <div class="text-right mt-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            wire:click="asignarColaborador">
                                            Asignar
                                        </button>
                                    </div>

                                </div>
                                <hr>
                            @endif

                            <div class="section">
                                {{-- <h5>Información del Ticket</h5> --}}
                                <ul class="info-list">
                                    <li><strong>Usuario:</strong> {{ $ticket->usuario->name }}</li>
                                    <li><strong>Correo:</strong> {{ $ticket->usuario->email }}</li>
                                    <li><strong>Sociedad:</strong> {{ $ticket->usuario->sociedad->nombre }}</li>
                                    <li><strong>Agente TI:</strong> {{ $ticket->asignado->name }}</li>
                                </ul>
                            </div>

                            @if (count($supervisores) > 0)
                                <div class="section">
                                    <h5>Supervisores</h5>
                                    <ul class="info-list">
                                        @foreach ($supervisores as $s)
                                            <li>{{ $s->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($ticket->Colaboradors && $ticket->Colaboradors->count())
                                <div class="section">
                                    <h5>Colaboradores</h5>
                                    <ul class="info-list">
                                        @foreach ($ticket->colaboradors as $colaborador)
                                            <li>{{ $colaborador->user->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($ticket->cambio)
                                <div class="section">
                                    <h5>Aprobadores de Cambio</h5>
                                    <ul class="info-list">
                                        <li><strong>Líder funcional:</strong>
                                            {{ $ticket->cambio->aprobadorFuncionalCambio->name }}</li>
                                        <li><strong>Aprobador TI:</strong>
                                            {{ $ticket->cambio->aprobadorTiCambio->name }}</li>
                                    </ul>
                                    @if ($ticket->cambio->tipo_cambio !== null)
                                        <span class="badge badge-warning tipo-cambio">
                                            {{ $ticket->cambio->tipo_cambio ? 'Cambio Complejo' : 'Cambio Simple' }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            @if ($ticket->aprobacion)
                                <div class="section">
                                    <h5>Flujo de Accesos</h5>
                                    <ul class="info-list">
                                        <li><strong>Líder funcional:</strong>
                                            {{ $ticket->aprobacion->aprobadorFuncional->name }}</li>
                                        <li><strong>Aprobador TI:</strong>
                                            {{ $ticket->aprobacion->aprobadorTi->name }}</li>
                                    </ul>
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Flujo del Ticket</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            {{-- <p><strong>Estado Actual:</strong> {{ $flowData['currentState'] }}</p> --}}
                            <div id="flow-diagram" style="position: relative; background: #f9f9f9; padding: 10px;">
                                <!-- El flujo se renderizará aquí -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @else
        <div class="overlay dark">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>
    @endif

    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                function initializeSelect2() {
                    $('.select2').select2();

                    $('#colaborador').on('change', function() {
                        @this.set('selectedUser', $(this).val());
                    });

                    $('#newAgente').on('change', function() {
                        @this.set('selectedNewAgente', $(this).val());
                    });

                    $('#aprobadorFuncional').on('change', function() {
                        @this.set('selectedFuncional', $(this).val());
                    });
                    $('#aprobadorTi').on('change', function() {
                        @this.set('selectedTi', $(this).val());
                    });
                }

                //-------------------------------------------------------

                // Renderizar flujo inicial
                const flowData = @json($flowData);
                renderFlowDiagram(flowData);

                // Volver a renderizar el flujo después de una actualización de Livewire
                Livewire.hook('message.processed', (message, component) => {
                    const updatedFlowData = @json($flowData);
                    renderFlowDiagram(updatedFlowData);
                });

                Livewire.on('updateFlowDiagram', function(updatedFlowData) {
                    setTimeout(() => {
                        renderFlowDiagram(updatedFlowData);
                    }, 50);
                });


                function renderFlowDiagram(flowData) {
                    console.log('Datos recibidos en frontend:', flowData);
                    const container = document.getElementById('flow-diagram');
                    if (!container) return;
                    container.innerHTML = '';

                    // Estilos base
                    container.style.fontFamily = 'Arial, sans-serif';
                    container.style.padding = '15px';
                    container.style.color = '#333';

                    // Título (vacío por ahora)
                    const titleElement = document.createElement('h5');
                    titleElement.innerText = '';
                    titleElement.style.margin = '0 0 25px 0';
                    titleElement.style.fontSize = '16px';
                    container.appendChild(titleElement);

                    // Contenedor de la línea de tiempo
                    const timeline = document.createElement('div');
                    timeline.style.position = 'relative';
                    timeline.style.paddingLeft = '30px';
                    container.appendChild(timeline);

                    // Línea vertical gris principal
                    const mainLine = document.createElement('div');
                    mainLine.style.position = 'absolute';
                    mainLine.style.left = '10px';
                    mainLine.style.top = '0';
                    mainLine.style.width = '2px';
                    mainLine.style.backgroundColor = '#E0E0E0';
                    timeline.appendChild(mainLine);

                    // Variables de control de altura
                    let lastItemHeight = 0;
                    let totalHeight = 0;

                    // Mostrar estados visitados
                    if (flowData.flowStates && flowData.flowStates.length > 0) {
                        const lastIndex = flowData.flowStates.length - 1;

                        flowData.flowStates.forEach((state, index) => {
                            const item = document.createElement('div');
                            item.style.position = 'relative';
                            item.style.marginBottom = '25px';
                            item.style.display = 'flex';
                            item.style.alignItems = 'center';
                            item.style.minHeight = '24px';

                            const isLast = index === lastIndex;

                            // Punto indicador
                            const dot = document.createElement('div');
                            dot.style.width = '14px';
                            dot.style.height = '14px';
                            dot.style.borderRadius = '50%';
                            dot.style.position = 'absolute';
                            dot.style.left = '-26px';
                            dot.style.backgroundColor = isLast ? '#2196F3' : '#9E9E9E';
                            dot.style.border = '2px solid white';
                            dot.style.boxShadow = '0 0 0 2px ' + (isLast ? '#2196F3' : '#9E9E9E');
                            dot.style.zIndex = '2';
                            item.appendChild(dot);

                            // Texto del estado
                            const stateText = document.createElement('span');
                            stateText.innerText = state.estado;
                            stateText.style.color = isLast ? '#2196F3' : '#616161';
                            stateText.style.fontWeight = isLast ? 'bold' : 'normal';
                            stateText.style.fontSize = '14px';
                            stateText.style.lineHeight = '1.4';
                            item.appendChild(stateText);

                            timeline.appendChild(item);

                            // Altura
                            lastItemHeight = item.offsetHeight + 25;
                            totalHeight += lastItemHeight;
                            mainLine.style.height = totalHeight + 'px';
                        });
                    }

                    // Mostrar siguientes pasos (acciones posibles en verde)
                    if (flowData.nextStates) {
                        const greenLine = document.createElement('div');
                        greenLine.style.position = 'absolute';
                        greenLine.style.left = '10px';
                        greenLine.style.top = totalHeight + 'px';
                        greenLine.style.width = '2px';
                        greenLine.style.backgroundColor = '#4CAF50';
                        greenLine.style.zIndex = '1';
                        timeline.appendChild(greenLine);

                        let greenSectionHeight = 0;

                        // nextStates como objeto (caso especial)
                        if (typeof flowData.nextStates === 'object' && !Array.isArray(flowData.nextStates)) {
                            Object.entries(flowData.nextStates).forEach(([action, isActive]) => {
                                const item = document.createElement('div');
                                item.style.position = 'relative';
                                item.style.marginBottom = '25px';
                                item.style.display = 'flex';
                                item.style.alignItems = 'center';
                                item.style.minHeight = '24px';

                                const dot = document.createElement('div');
                                dot.style.width = '14px';
                                dot.style.height = '14px';
                                dot.style.borderRadius = '50%';
                                dot.style.position = 'absolute';
                                dot.style.left = '-26px';
                                dot.style.backgroundColor = isActive ? '#4CAF50' : '#9E9E9E';
                                dot.style.border = '2px solid white';
                                dot.style.boxShadow = `0 0 0 2px ${isActive ? '#4CAF50' : '#9E9E9E'}`;
                                dot.style.zIndex = '2';
                                item.appendChild(dot);

                                const actionText = document.createElement('span');
                                actionText.innerText = action.replace(/^\d+\.\s*/, '');
                                actionText.style.color = isActive ? '#4CAF50' : '#9E9E9E';
                                actionText.style.fontSize = '14px';
                                actionText.style.lineHeight = '1.4';
                                actionText.style.fontWeight = isActive ? 'bold' : 'normal';
                                item.appendChild(actionText);

                                timeline.appendChild(item);

                                const itemHeight = item.offsetHeight + 25;
                                greenSectionHeight += itemHeight;
                                greenLine.style.height = greenSectionHeight + 'px';
                            });
                        } else if (Array.isArray(flowData.nextStates)) {
                            flowData.nextStates.forEach((action) => {
                                const item = document.createElement('div');
                                item.style.position = 'relative';
                                item.style.marginBottom = '25px';
                                item.style.display = 'flex';
                                item.style.alignItems = 'center';
                                item.style.minHeight = '24px';

                                const dot = document.createElement('div');
                                dot.style.width = '14px';
                                dot.style.height = '14px';
                                dot.style.borderRadius = '50%';
                                dot.style.position = 'absolute';
                                dot.style.left = '-26px';
                                dot.style.backgroundColor = '#4CAF50';
                                dot.style.border = '2px solid white';
                                dot.style.boxShadow = '0 0 0 2px #4CAF50';
                                dot.style.zIndex = '2';
                                item.appendChild(dot);

                                const actionText = document.createElement('span');
                                actionText.innerText = action.replace(/^\d+\.\s*/, '');
                                actionText.style.color = '#4CAF50';
                                actionText.style.fontSize = '14px';
                                actionText.style.lineHeight = '1.4';
                                item.appendChild(actionText);

                                timeline.appendChild(item);

                                const itemHeight = item.offsetHeight + 25;
                                greenSectionHeight += itemHeight;
                                greenLine.style.height = greenSectionHeight + 'px';
                            });
                        }
                    }

                }

                $(document).ready(function() {
                    const $cardBody = $('#flow-diagram').closest('.card-body');

                    // Función para mantener el scroll abajo
                    function keepScrollDown() {
                        $cardBody.scrollTop($cardBody[0].scrollHeight);
                    }

                    // Ejecutar al inicio
                    keepScrollDown();

                    // Opcional: Si el contenido cambia dinámicamente
                    // Observar cambios en el contenido y ajustar el scroll
                    const observer = new MutationObserver(function() {
                        keepScrollDown();
                    });

                    observer.observe(document.getElementById('flow-diagram'), {
                        childList: true,
                        subtree: true
                    });
                });


                //--------------------------------------------------------------------------

                function initializeEditor() {
                    ClassicEditor
                        .create(document.querySelector('#editor'))
                        .then(function(editor) {
                            editorInstance = editor; // Guardamos la instancia del editor
                            editor.model.document.on('change:data', () => {
                                @this.set('newComment', editor.getData());
                            });
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }

                initializeSelect2();
                initializeEditor();

                // Reinitialize after Livewire updates the DOM
                Livewire.hook('message.processed', (message, component) => {
                    initializeSelect2();
                });

                Livewire.on('editorVisible', () => {
                    initializeEditor();
                });

                $('#tipoComentario').on('change', function() {
                    @this.set('commentType', $(this).val());
                });

                Livewire.on('verificarOtravez', i => {
                    alertClickCallback('Asignar Impacto',
                        `Al asignar el impacto, se actualizará la prioridad del ticket, el estado cambiará a: en atención. Por lo que el sistema asumirá que está bien categorizado.`,
                        'warning',
                        'Si, confirmar', 'Cancelar',
                        function() {
                            @this.actualizarImpacto();
                        });
                });

                Livewire.on('resetearEditor', i => {
                    if (editorInstance) { // Verificar si editorInstance está definido
                        editorInstance.setData(''); // Reseteamos el contenido del editor
                    } else {
                        console.error("Editor instance is not defined.");
                    }
                });

                Livewire.on('borrarRecordatorio', () => {
                    toastRight('success', 'Recordatorio eliminado!');
                });

                Livewire.on('tareaEditada', () => {
                    toastRight('success', 'Se editó la tarea!');
                });

                Livewire.on('tareaNoAutorizada', () => {
                    toastRight('warning',
                        'Solo se pueden asignar tareas cuando el ticket este en estado: EN PRUEBAS DE USUARIO!'
                    );
                });

                Livewire.on('faltaDocumentoTecnico', () => {
                    toastRight('warning', 'Es necesario adjuntar el documento técnico');
                });

                Livewire.on('tareaCreada', () => {
                    toastRight('success', 'Se registro la tarea correctamente!');
                });

                Livewire.on('confirmacionSolicitada', () => {
                    toastRight('success', 'Se ha enviado la solicitud de confirmacion!');
                });

                Livewire.on('nuevoTransporte', () => {
                    toastRight('success',
                        'Se registro la tarea correctamente y se le notifico al aprobador TI para que apruebe!'
                    );
                });

                Livewire.on('colaboradorOk', () => {
                    toastRight('success', 'Se ha agregado el colaborador al ticket!');
                });

                Livewire.on('agenteExiste', () => {
                    toastRight('warning', 'El usuario ya está asignado como agente de TI.');
                });

                Livewire.on('colaboradorExiste', () => {
                    toastRight('warning', 'El usuario ya está asignado como colaborador en este ticket.');
                });

                Livewire.on('showToast', (data) => {
                    toastRight(data.type, data.message);
                });

                Livewire.on('redirectAfterDelay', function() {
                    setTimeout(function() {
                        window.location.href = '/gestion';
                    }, 3000);
                });

            });
        </script>
    @endpush
</div>
