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


        .cabezera-aprobacion h5 {
            font-size: 0.9rem;
            /* Reduce ligeramente el tamaño de la fuente */
            white-space: nowrap;
            overflow: visible;
            text-align: center;
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
                                        class="text-muted">{{ $ticket->sociedad->nombre }}>>{{ $ticket->tipoSolicitud->nombre }}>>{{ $ticket->categoria->nombre }}>>{{ $ticket->subcategoria->nombre }}{{ $ticket->aplicacion ? '>>' . $ticket->aplicacion->nombre : '' }}</i>
                                </p>
                                @if ($ticket->excepcion)
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><strong>Usuario:</strong> {{ $ticket->excepcion->usuario_sap }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Modulo:</strong> {{ $ticket->excepcion->modulo }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Fecha inicio:</strong> {{ $ticket->excepcion->fecha_inicio }}
                                            </p>
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
                                            <p><strong>Impacto:</strong> {{ $ticket->impacto->nombre }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        @if ($prioridad != 'NULL')
                                            <p><strong>Prioridad:</strong> {{ $ticket->prioridad }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        @if ($ticket->cambio)
                                            <p><strong>Adjunto</strong></p>
                                            @foreach ($ticket->archivos as $archivo)
                                                @if ($archivo->comentario_id == null)
                                                    <li>
                                                        <a href="{{ Storage::url($archivo->ruta) }}" target="_blank">
                                                            {{ str_replace('-', ' ', basename($archivo->ruta, '.pdf')) }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif
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
                                <div class="row">
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
                                                            @if ($comentario->tipo == 2 && Auth::id() == $ticket->usuario_id)
                                                                @if ($ticket->estado_id != 4)
                                                                    <div
                                                                        class="d-flex justify-content-end row mr-2 mb-2">
                                                                        <button
                                                                            wire:click="aceptarSolucion({{ $comentario->id }})"
                                                                            class="btn btn-outline-info btn-sm">
                                                                            <i class="fas fa-check-circle"></i>
                                                                        </button>
                                                                        <button
                                                                            wire:click="rechazarSolucion({{ $comentario->id }})"
                                                                            class="btn btn-outline-danger btn-sm ml-2">
                                                                            <i class="fas fa-times-circle"></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                                <span
                                                                    class="badge color-verde-claro mr-2 float-right">Solución
                                                                    {{ $ticket->comentario += 1 }}
                                                                </span>
                                                            @else
                                                                @if ($comentario->tipo == 3)
                                                                    <span
                                                                        class="badge estado-por-iniciar mr-2 float-right">Solución
                                                                        no aceptada. Respuesta
                                                                        {{ $ticket->comentario += 1 }}
                                                                    </span>
                                                                @else
                                                                    <div
                                                                        class="d-flex justify-content-end row mr-2 mb-2">

                                                                        @if (
                                                                            $ticket->estado_id == 10 &&
                                                                                $ticket->cambio->check_aprobado &&
                                                                                $ticket->cambio->aprobador_final_ti_id == Auth::id() &&
                                                                                $comentario->check_comentario == true)
                                                                            <button onclick="confirmProducion()"
                                                                                class="btn btn-outline-info btn-sm">
                                                                                <i class="fas fa-check-circle"></i>
                                                                            </button>
                                                                            <button
                                                                                onclick="confirmDelete({{ $comentario->id }})"
                                                                                class="btn btn-outline-danger btn-sm ml-2">
                                                                                <i class="fas fa-times-circle"></i>
                                                                            </button>
                                                                        @elseif($ticket->cambio->check_aprobado_ti == true && $comentario->check_comentario == true)
                                                                            <h5 class="badge text-bg-dark"
                                                                                style="background-color: #a3da92;">Se
                                                                                aprobó el paso a producción</h5>
                                                                        @endif
                                                                    </div>

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
                                            @if ($ticket->estado_id != 4 && $ticket->estado_id != 5)
                                                <div>
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
                                                                        <label for="file"
                                                                            class="custom-file-upload">
                                                                            <i class="fa fa-paperclip"></i>
                                                                        </label>
                                                                        <input type="file" id="file"
                                                                            name="file" class="d-none"
                                                                            wire:model="newFile">
                                                                    </span>
                                                                </div>
                                                                <div class="input-group-append">
                                                                    <button wire:click="addComment"
                                                                        class="btn btn-outline-info btn-sm">Responder
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
                </div>

                <div class="col-12 col-md-3 mb-3">
                    <div class="card">
                        <div class="card-header cabezera-aprobacion col-md-12">
                            <div class="d-flex align-items-center" style="background-color: #eeeeee">
                                <div class="col-md-6">
                                    <h5>Aprobación de Cambios</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (
                                    $estado_aprobacion_old === 'pendiente' ||
                                    $estado_aprobacion_old === 'aprobado_funcional' ||
                                    $estado_aprobacion_old === 'rechazado_funcional' ||
                                    $estado_aprobacion_old === 'rechazado_ti' ||
                                    $estado_aprobacion_old === 'aprobado_ti')
                                @if (
                                    $aprobador_funcional_id === auth()->user()->id &&
                                        ($estado_aprobacion_old === 'pendiente' || $estado_aprobacion_old === 'rechazado_ti'))
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
                                            <label for="comentariosRechazo">Comentario (Obligatorio si
                                                rechaza):</label>
                                            <textarea wire:model="comentariosRechazo" id="comentariosRechazo" rows="3" class="form-control"></textarea>
                                        </div>
                                    @endif
                                    <div class="d-flex">
                                        <button wire:click="aprobarFuncionalCambio"
                                            class="btn btn-outline-info btn-sm float-right">Confirmar</button>
                                    </div>
                                @elseif($aprobador_ti_id === auth()->user()->id && $estado_aprobacion_old === 'aprobado_funcional')
                                    <div class="form-group">
                                        <label for="estado_aprobacion_ti">Aprobación TI:</label>
                                        <select wire:model="estado_aprobacion" class="form-control">
                                            <option value="">-- Seleccione --</option>
                                            <option value="aprobado_ti">Aprobar</option>
                                            <option value="rechazado_ti">Rechazar</option>
                                        </select>
                                    </div>
                                    @if ($estado_aprobacion === 'rechazado_ti')
                                        <div class="form-group">
                                            <label for="comentariosRechazo">Comentario (Obligatorio si
                                                rechaza):</label>
                                            <textarea wire:model="comentariosRechazo" id="comentariosRechazo" rows="3" class="form-control"></textarea>
                                        </div>
                                    @endif
                                    <button wire:click="aprobarTiCambio"
                                        class="btn btn-outline-info btn-sm">Confirmar</button>
                                @else
                                    <p>Ya aprobaste este ticket</p>
                                @endif
                            @else
                                @if ($ticket->estado_id == 10 && $ticket->cambio->check_aprobado && $ticket->cambio->aprobador_final_ti_id == Auth::id())
                                    <p>Aprobar set de pruebas</p>
                                @else
                                    <p>El flujo de aprobación ya ha sido completado.</p>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header cabezera-aprobacion col-md-12">
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
                    <div class="card mt-3" style="max-height: 400px; overflow-y: auto;">
                        <div class="card-header">
                            <h5>Flujo del Ticket</h5>
                        </div>
                        <div class="card-body">
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
                Livewire.on('showToast', (data) => {
                    toastRight(data.type, data.message);
                });

                $('#estado_aprobacion').on('change', function() {
                    @this.set('estado_aprobacion_ti', $(this).val());
                });

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

                initializeEditor();

                Livewire.on('editorVisible', () => {
                    initializeEditor();
                });

                Livewire.on('resetearEditor', i => {
                    if (editorInstance) { // Verificar si editorInstance está definido
                        editorInstance.setData(''); // Reseteamos el contenido del editor
                    } else {
                        console.error("Editor instance is not defined.");
                    }
                });

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

                    // Título
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

                    // Variables para control de posición
                    let lastItemHeight = 0;
                    let totalHeight = 0;

                    // Mostrar estados visitados
                    if (flowData.flowStates && flowData.flowStates.length > 0) {
                        flowData.flowStates.forEach((state) => {
                            const item = document.createElement('div');
                            item.style.position = 'relative';
                            item.style.marginBottom = '25px';
                            item.style.display = 'flex';
                            item.style.alignItems = 'center';
                            item.style.minHeight = '24px';

                            // Punto indicador
                            const dot = document.createElement('div');
                            dot.style.width = '14px';
                            dot.style.height = '14px';
                            dot.style.borderRadius = '50%';
                            dot.style.position = 'absolute';
                            dot.style.left = '-26px';
                            dot.style.backgroundColor = state.estado === flowData.currentState ? '#2196F3' :
                                '#9E9E9E';
                            dot.style.border = '2px solid white';
                            dot.style.boxShadow = '0 0 0 2px ' + (state.estado === flowData.currentState ?
                                '#2196F3' : '#9E9E9E');
                            dot.style.zIndex = '2';
                            item.appendChild(dot);

                            // Texto del estado
                            const stateText = document.createElement('span');
                            stateText.innerText = state.estado;
                            stateText.style.color = state.estado === flowData.currentState ? '#2196F3' :
                                '#616161';
                            stateText.style.fontWeight = state.estado === flowData.currentState ? 'bold' :
                                'normal';
                            stateText.style.fontSize = '14px';
                            stateText.style.lineHeight = '1.4';
                            item.appendChild(stateText);

                            timeline.appendChild(item);

                            // Actualizar altura de la línea principal
                            lastItemHeight = item.offsetHeight + 25;
                            totalHeight += lastItemHeight;
                            mainLine.style.height = totalHeight + 'px';
                        });
                    }

                    // Mostrar siguientes pasos (en verde)
                    if (flowData.nextStates) {
                        // Línea verde para acciones
                        const greenLine = document.createElement('div');
                        greenLine.style.position = 'absolute';
                        greenLine.style.left = '10px';
                        greenLine.style.top = totalHeight + 'px';
                        greenLine.style.width = '2px';
                        greenLine.style.backgroundColor = '#4CAF50';
                        greenLine.style.zIndex = '1';
                        timeline.appendChild(greenLine);

                        let greenSectionHeight = 0;

                        // Verificar si nextStates es un objeto (caso especial estado 11)
                        if (typeof flowData.nextStates === 'object' && !Array.isArray(flowData.nextStates)) {
                            // Procesar el objeto de acciones condicionales
                            Object.entries(flowData.nextStates).forEach(([action, isActive]) => {
                                const item = document.createElement('div');
                                item.style.position = 'relative';
                                item.style.marginBottom = '25px';
                                item.style.display = 'flex';
                                item.style.alignItems = 'center';
                                item.style.minHeight = '24px';

                                // Punto verde (activo) o gris (inactivo)
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

                                // Texto de la acción (verde si está activa, gris si no)
                                const actionText = document.createElement('span');
                                actionText.innerText = action.replace(/^\d+\.\s*/, '');
                                actionText.style.color = isActive ? '#4CAF50' : '#9E9E9E';
                                actionText.style.fontSize = '14px';
                                actionText.style.lineHeight = '1.4';
                                actionText.style.fontWeight = isActive ? 'bold' : 'normal';
                                item.appendChild(actionText);

                                timeline.appendChild(item);

                                // Actualizar altura de la línea verde
                                const itemHeight = item.offsetHeight + 25;
                                greenSectionHeight += itemHeight;
                                greenLine.style.height = greenSectionHeight + 'px';
                            });
                        } else if (Array.isArray(flowData.nextStates)) {
                            // Procesamiento normal para arrays
                            flowData.nextStates.forEach((action) => {
                                const item = document.createElement('div');
                                item.style.position = 'relative';
                                item.style.marginBottom = '25px';
                                item.style.display = 'flex';
                                item.style.alignItems = 'center';
                                item.style.minHeight = '24px';

                                // Punto verde
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

                                // Texto de la acción
                                const actionText = document.createElement('span');
                                actionText.innerText = action.replace(/^\d+\.\s*/, '');
                                actionText.style.color = '#4CAF50';
                                actionText.style.fontSize = '14px';
                                actionText.style.lineHeight = '1.4';
                                item.appendChild(actionText);

                                timeline.appendChild(item);

                                // Actualizar altura de la línea verde
                                const itemHeight = item.offsetHeight + 25;
                                greenSectionHeight += itemHeight;
                                greenLine.style.height = greenSectionHeight + 'px';
                            });
                        }
                    }
                }
            });

            function confirmDelete(id) {
                alertClickCallback('Rechazar',
                    'No aprobar el paso a producción', 'warning',
                    'Confirmar', 'Cancelar', async () => {
                        const res = await @this.rechazarSet(id);
                        toastRight('error', 'No se aprobó el paso a producción!');
                    });
            }

            function confirmProducion() {
                alertClickCallback('Confirmar',
                    'Confirmar paso a producción', 'success',
                    'Confirmar', 'Cancelar', async () => {
                        const res = await @this.aprobarSet();
                        toastRight('success', 'Se aprobó el paso a producción!');
                    });
            }
        </script>
    @endpush
</div>
