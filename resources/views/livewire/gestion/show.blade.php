<div>
    <style>
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
    </style>
    @if ($ticket)
        <div class="container-fluid">
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
                            <div class="mb-1 d-flex  align-items-center" style="background-color: #eeeeee">
                                <div class="col-md-4 mt-2">
                                    <p><strong>{{ $ticket->estado->nombre }}</strong></p>
                                </div>
                                <div class="col-md-8">
                                    <p class="text-right mt-2">
                                        @if ($ticket->estado_id != 4 && $ticket->estado_id != 5)
                                            @php
                                                $tiempoRestante = $ticket->tiempo_restante;
                                            @endphp

                                            @if ($tiempoRestante > 900)
                                                {{-- Más de 15 minutos --}}
                                                <span
                                                    style="background-color: #4CAF50; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold;">
                                                    <i class="fas fa-check-circle"></i> Tiempo restante ANS
                                                    {{ $tipoANS }}:
                                                    {{ $this->formatTiempoRestante($tiempoRestante) }}
                                                </span>
                                            @elseif ($tiempoRestante <= 900 && $tiempoRestante > 300)
                                                {{-- Entre 5 y 15 minutos --}}
                                                <span
                                                    style="background-color: #FF5722; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold;">
                                                    <i class="fas fa-exclamation-circle"></i> Tiempo restante ANS
                                                    {{ $tipoANS }}:
                                                    {{ $this->formatTiempoRestante($tiempoRestante) }}
                                                </span>
                                            @elseif ($tiempoRestante > 0)
                                                {{-- Menos de 5 minutos --}}
                                                <span
                                                    style="background-color: #eb2e20; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold;">
                                                    <i class="fas fa-times-circle"></i> Tiempo restante ANS
                                                    {{ $tipoANS }}:
                                                    {{ $this->formatTiempoRestante($tiempoRestante) }}
                                                </span>
                                            @else
                                                {{-- ANS no cumplido --}}
                                                <span
                                                    style="background-color: #ec3022; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold;">
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
                                @if ($tarea)
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
                                                @if ($ticket->estado_id == 1)
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
                                                        <p class="ml-2">¿Escalar a consultoría?</p>
                                                        <div class="float-right">
                                                            <label class="switch"
                                                                style="margin-left:10px; margin-top: 5px">
                                                                <input type="checkbox" wire:model="escalar">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
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
                                                    <!-- Mostrar la selección de aplicaciones solo si es SOPORTE DE APLICACIONES -->
                                                    @if ($subcategoria && in_array($subcategoria->nombre, ['SOPORTE DE APLICACIONES', 'DESARROLLO Y PERSONALIZACIONES']))
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
                                                    @if (
                                                        $ticket->aprobacion->estado === 'pendiente' ||
                                                            $ticket->aprobacion->estado === 'aprobado_funcional' ||
                                                            $ticket->aprobacion->estado === 'rechazado_ti')
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
                                                    <p>Se cambiará el estado del ticket por: <strong>Escalado a
                                                            consultoría</strong></p>
                                                    <p>Recuerda mantener informado al usuario sobre cualquier novedad
                                                        (Nombre de la consultoría,# del caso y cambio de estados), lo
                                                        puedes
                                                        hacer mediante la caja de comentarios, si es necesario
                                                        adjunta evidencias.
                                                    </p>
                                                    <p>Durante el tiempo que este el estado en: <strong>Escalado a
                                                            consultoría</strong>. No podras marcar respuestas como
                                                        sulución,
                                                        hasta que cambies manualmente el estado a: <strong>En
                                                            atención</strong></p>
                                                    <div class="d-flex">
                                                        <button wire:click="consultoria"
                                                            class="float-right btn btn-sm btn-outline-info">Confirmar</button>
                                                    </div>
                                                    <hr>
                                                @elseif ($ticket->escalar == true && $ticket->estado_id == 9)
                                                    <p><strong>Cambiar estado</strong></p>
                                                    <p><i>Si cambias el estado, se entendera que la consultoría ya
                                                            proporcionó una solución</i></p>
                                                    <div class="d-flex">
                                                        <button wire:click="consultoriaCambio"
                                                            class="float-right btn btn-sm btn-outline-info">Cambiar
                                                            estado</button>
                                                    </div>
                                                    <hr>
                                                @else
                                                    <div class="justify-content-center align-items-center">
                                                        <p>¿Volver a escalar?</p>
                                                        <button wire:click="consultoria"
                                                            class=" btn btn-sm btn-outline-info">Confirmar</button>
                                                    </div>
                                                    <hr>
                                                @endif
                                            @endif
                                            @if ($cambio)
                                                @if ($ticket->cambio)
                                                    @if (
                                                        $ticket->cambio->estado === 'pendiente' ||
                                                            $ticket->cambio->estado === 'aprobado_funcional' ||
                                                            $ticket->cambio->estado === 'rechazado_ti')
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
                                                                            <div class="dropdown">
                                                                                @if ($ticket->cambio->check_aprobado_ti != true && $comentario->check_comentario != true)
                                                                                    <button
                                                                                        class="btn btn-info btn-sm dropdown-toggle"
                                                                                        type="button"
                                                                                        data-toggle="dropdown"
                                                                                        aria-expanded="false">
                                                                                    </button>
                                                                                @endif
                                                                                <div class="dropdown-menu">
                                                                                    <button
                                                                                        wire:click="mandarParaAprobacion({{ $comentario->id }})"
                                                                                        class="dropdown-item">Enviar
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
                                                                    <input type="file" id="file"
                                                                        name="file" class="d-none"
                                                                        wire:model="newFile">
                                                                </span>
                                                            </div>
                                                            <div class="input-group-append">
                                                                <select wire:model="commentType" id="tipoComentario"
                                                                    class="form-control form-control-sm">
                                                                    <option value="0">Público</option>
                                                                    <option value="1">Privado</option>
                                                                    @if (Auth::id() == $ticket->asignado_a)
                                                                        @if ($ticket->estado_id != 9 && $ticket->estado_id != 10 && $ticket->estado_id != 15)
                                                                            @if (!$ticket->solucion())
                                                                                <option value="2">Solución
                                                                                </option>
                                                                            @endif
                                                                        @endif
                                                                        @if ($ticket->cambio && $ticket->cambio->estado == 'aprobado' && $ticket->cambio->check_aprobado_ti == false)
                                                                            @if (!$ticket->solucion())
                                                                                <option value="5">Set de pruebas
                                                                                </option>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                    @if ($ticket->cambio && $ticket->cambio->check_aprobado_ti == true && $ticket->colaboradores->contains('id', Auth::id()))
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
                                            <div wire:loading wire:target="newFile" class="" role="alert">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="text-center"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($newFile)
                                            <div class="d-flex align-items-center border-file p-2 rounded-file">
                                                <div class="mr-2">
                                                    <i class="fa fa-check-circle text-success-file"></i>
                                                </div>
                                                <div class="flex-grow-1-file">
                                                    <span>{{ $newFile->getClientOriginalName() }}</span>
                                                </div>
                                                <div class="text-muted-file">
                                                    Subida completa
                                                </div>
                                                <div class="ml-2">
                                                    <button class="btn btn-link-file text-danger-file p-0"
                                                        wire:click="removeFile">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
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
                        <div class="card-body">
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
                            @if ($ticket->Colaboradors)
                                @foreach ($ticket->colaboradors as $colaborador)
                                    <p><strong>Colaborador :</strong> {{ $colaborador->user->name }}</p>
                                @endforeach
                            @endif
                            @if ($ticket->cambio)
                                <h5>Flujo de cambios</h5>
                                <p><strong>Líder funcional:</strong>
                                    {{ $ticket->cambio->aprobadorFuncionalCambio->name }}</p>
                                <p><strong>Aprobador TI:</strong>
                                    {{ $ticket->cambio->aprobadorTiCambio->name }}</p>
                            @endif
                            @if ($ticket->aprobacion)
                                <h5>Flujo de accesos</h5>
                                <p><strong>Líder funcional:</strong>
                                    {{ $ticket->aprobacion->aprobadorFuncional->name }}</p>
                                <p><strong>Aprobador TI:</strong>
                                    {{ $ticket->aprobacion->aprobadorTi->name }}</p>
                            @endif
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
