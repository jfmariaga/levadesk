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

        .rating i {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
        }

        .rating i.selected,
        .rating i:hover,
        .rating i:hover~i {
            color: gold;
        }
    </style>
    @if ($ticket)
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-9 mb-3">
                    <div class="card">
                        <div class="col-12 mt-1 mr-2">
                            <div class="card-header col-md-12">
                                <div class="mb-1 d-flex  align-items-center" style="background-color: #eeeeee">
                                    <div class="col-md-7 mt-2">
                                        <p><strong>{{ $ticket->estado->nombre }}</strong></p>
                                    </div>
                                    <div class="col-md-5">
                                        <p class="text-right mt-2">
                                            <span
                                                class="solicitud-badge font-weight-bold">{{ $ticket->nomenclatura }}</span>
                                        </p>
                                    </div>
                                </div>
                                <h5>{{ $ticket->titulo }}, {{ $ticket->descripcion }}</h5>
                                <p><i
                                        class="text-muted">{{ $ticket->sociedad->nombre }}>>{{ $ticket->tipoSolicitud->nombre }}>>{{ $ticket->categoria->nombre }}>>{{ $ticket->subcategoria->nombre }}</i>
                                </p>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p><strong>Urgencia:</strong> {{ $ticket->urgencia->nombre }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        @if ($ticket->impacto)
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
                                                <li><a href="{{ Storage::url($archivo->ruta) }}"
                                                        target="_blank">Adjunto</a></li>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="card-body bg-light">
                                <div class="row">
                                    <h5>Timeline</h5>
                                    <button wire:click="toggleTimelineTicket" class="btn-flecha">
                                        <i
                                            class="fas {{ $showTimelineTicket ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        @if ($showTimelineTicket)
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3 mb-3">
                    <div class="card">
                        <div class="card-header col-md-12">
                            <div class="d-flex align-items-center" style="background-color: #eeeeee">
                                <div class="col-md-6">
                                    <h5>Participantes</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p><strong>Usuario :</strong> {{ $ticket->usuario->name }}</p>
                            <p><strong>Agente TI :</strong> {{ $ticket->asignado->name }}</p>
                            @if ($ticket->Colaboradors)
                                @foreach ($ticket->colaboradors as $colaborador)
                                    <p><strong>Colaborador :</strong> {{ $colaborador->user->name }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-4 mt-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Aprobación de Acceso</h5>
                        </div>
                        <div class="card-body">
                            @if (
                                    $estado_aprobacion_old === 'pendiente' ||
                                    $estado_aprobacion_old === 'aprobado_funcional' ||
                                    $estado_aprobacion_old === 'rechazado_funcional' ||
                                    $estado_aprobacion_old === 'rechazado_ti' ||
                                    $estado_aprobacion_old === 'aprobado_ti')
                                @if ($aprobador_funcional_id === auth()->user()->id && ($estado_aprobacion_old === 'pendiente' || $estado_aprobacion_old === 'rechazado_ti'))
                                    <div class="form-group">
                                        <label for="estado_aprobacion_funcional">Aprobación Funcional:</label>
                                        <select wire:model="estado_aprobacion" id="estado_aprobacion_funcional"
                                            class="form-control">
                                            <option value="">-- Seleccione --</option>
                                            <option value="aprobado_funcional">Aprobar</option>
                                            <option value="rechazado_funcional">Rechazar</option>
                                        </select>
                                    </div>
                                    @if ($estado_aprobacion === 'rechazado_funcional')
                                        <div class="form-group">
                                            <label for="comentariosRechazo">Comentario (Obligatorio si rechaza):</label>
                                            <textarea wire:model="comentariosRechazo" id="comentariosRechazo" rows="3" class="form-control"></textarea>
                                        </div>
                                    @endif
                                    <button wire:click="aprobarFuncional" class="btn btn-primary">Confirmar</button>
                                @elseif($aprobador_ti_id === auth()->user()->id && ($estado_aprobacion_old  === 'aprobado_funcional'))
                                    <div class="form-group">
                                        <label for="estado_aprobacion_ti">Aprobación TI:</label>
                                        <select wire:model="estado_aprobacion"
                                            class="form-control">
                                            <option value="">-- Seleccione --</option>
                                            <option value="aprobado_ti">Aprobar</option>
                                            <option value="rechazado_ti">Rechazar</option>
                                        </select>
                                    </div>
                                    @if ($estado_aprobacion === 'rechazado_ti')
                                        <div class="form-group">
                                            <label for="comentariosRechazo">Comentario (Obligatorio si rechaza):</label>
                                            <textarea wire:model="comentariosRechazo" id="comentariosRechazo" rows="3" class="form-control"></textarea>
                                        </div>
                                    @endif
                                    <button wire:click="aprobarTi" class="btn btn-primary">Confirmar</button>
                                @else
                                    <p>Ya aprobaste este ticket</p>
                                @endif
                            @else
                                <p>El flujo de aprobación ya ha sido completado.</p>
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
                Livewire.on('showToast', (data) => {
                    toastRight(data.type, data.message);
                });

                $('#estado_aprobacion').on('change', function() {
                @this.set('estado_aprobacion_ti', $(this).val());
            });
            });
        </script>
    @endpush
</div>
