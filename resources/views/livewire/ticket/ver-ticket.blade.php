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
                                                    <i class="fas fa-star text-muted" style="font-size: 24px;"></i>
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
                                <div class="col-md-7 mt-2">
                                    <p><strong>{{ $ticket->estado->nombre }}</strong></p>
                                </div>
                                <div class="col-md-5">
                                    <p class="text-right mt-2">
                                        <span
                                            class="solicitud-badge  font-weight-bold">{{ $ticket->nomenclatura }}</span>
                                    </p>
                                </div>
                            </div>
                            <h5>
                                {{ $ticket->titulo }}, {{ $ticket->descripcion }}
                            </h5>
                            <p><i class="text-muted">{{ $ticket->sociedad->nombre }}>>{{ $ticket->tipoSolicitud->nombre }}>>{{ $ticket->categoria->nombre }}>>
                                    {{ $ticket->subcategoria->nombre }}{{$ticket->aplicacion? '>>'.$ticket->aplicacion->nombre: ''}}</i></p>
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
                                                <a href="{{ Storage::url($archivo->ruta) }}"
                                                    target="_blank">Adjunto</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row">
                                <h5>Timeline</h5>
                                <button wire:click="toggleTimelineTicket" class="btn-flecha">
                                    <i class="fas {{ $showTimelineTicket ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class=" col-md-12">
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
                                                        @if ($comentario->tipo == 2)
                                                            @if ($ticket->estado_id != 4)
                                                                <div class="d-flex justify-content-end row mr-2 mb-2">
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
                                                                            target="_blank">Adjunto</a>
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
                                                                    <label for="file" class="custom-file-upload">
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
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header col-md-12">
                            <div class="d-flex  align-items-center" style="background-color: #eeeeee">
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

                Livewire.on('mostrarSistemaCalificacion', (comentarioId) => {
                    let currentRating = 0;

                    Swal.fire({
                        title: 'Califica el Ticket',
                        html: `
            <div>
                <label>Calificación:</label>
                <div id="rating-stars" class="rating">
                    <i class="fas fa-star" data-value="1"></i>
                    <i class="fas fa-star" data-value="2"></i>
                    <i class="fas fa-star" data-value="3"></i>
                    <i class="fas fa-star" data-value="4"></i>
                    <i class="fas fa-star" data-value="5"></i>
                </div>
                <textarea id="calificacionComentario" class="swal2-textarea" placeholder="Escribe un comentario (opcional)"></textarea>
            </div>
            `,
                        showCancelButton: true,
                        confirmButtonText: 'Enviar Calificación',
                        preConfirm: () => {
                            const rating = currentRating;
                            const comentario = document.getElementById('calificacionComentario')
                                .value;

                            if (!rating) {
                                Swal.showValidationMessage(
                                    'Por favor, selecciona una calificación.');
                                return false;
                            }

                            return {
                                rating: rating,
                                comentario: comentario
                            };
                        }
                    }).then((result) => {
                        // Llamamos a la función de Livewire para guardar la calificación
                        @this.guardarCalificacion(comentarioId, result.value.rating, result.value
                            .comentario);
                    }).catch((error) => {
                        console.error('Error en Swal:', error);
                    });

                    const stars = document.querySelectorAll('#rating-stars .fa-star');
                    stars.forEach(star => {
                        star.addEventListener('click', function() {
                            const ratingValue = parseInt(this.getAttribute('data-value'));
                            currentRating = ratingValue;

                            stars.forEach(s => s.classList.remove('selected'));

                            this.classList.add('selected');
                            let previousSibling = this.previousElementSibling;
                            while (previousSibling) {
                                previousSibling.classList.add('selected');
                                previousSibling = previousSibling.previousElementSibling;
                            }
                        });

                        star.addEventListener('mouseover', function() {
                            stars.forEach(s => s.classList.remove('selected'));
                            this.classList.add('selected');
                            let previousSibling = this.previousElementSibling;
                            while (previousSibling) {
                                previousSibling.classList.add('selected');
                                previousSibling = previousSibling.previousElementSibling;
                            }
                        });
                    });
                });


                function initializeSelect2() {
                    $('.select2').select2();
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

                Livewire.on('resetearEditor', i => {
                    if (editorInstance) { // Verificar si editorInstance está definido
                        editorInstance.setData(''); // Reseteamos el contenido del editor
                    } else {
                        console.error("Editor instance is not defined.");
                    }
                });

                Livewire.on('showToast', (data) => {
                    toastRight(data.type, data.message);
                });


                Livewire.on('confirmarReapertura', i => {
                    alertClickCallback('¿Estás seguro?',
                        `El ticket será reabierto.`,
                        'warning',
                        'Si, confirmar', 'Cancelar',
                        function() {
                            @this.reabrirTicket();
                        });
                });
            });
        </script>
    @endpush
</div>
