<div>
    <style>
        .timeline-horizontal {
            display: flex;
            overflow-x: auto;
            padding: 20px;
            white-space: nowrap;
        }

        .timeline-item {
            display: inline-block;
            background: #f4f4f4;
            border-radius: 10px;
            margin: 5px 20px;
            padding: 10px 20px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .timeline-icon {
            width: 30px;
            height: 30px;
            background-color: #777;
            border-radius: 50%;
            position: absolute;
            left: -15px;
            /* Adjust this value if needed */
            top: 10px;
        }

        .timeline-content {
            padding-left: 40px;
            /* Adjust based on the size of the timeline-icon */
        }

        .timeline-content h2 {
            font-size: 16px;
            font-weight: bold;
            color: #555;
        }

        .timeline-content p {
            font-size: 14px;
            color: #666;
        }
    </style>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @if ($ticket_old)
                <div class="modal-header">
                    <h4 class="modal-title">Ticket {{ $ticket_old->nomenclatura }}</h4>
                    <button type="button" class="close" data-dismiss="modal" wire:click="resetForm">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Cuadro 1: Timeline del Ticket -->
                        <div class="col-lg-4 col-md-12">
                            <h5>Timeline del Ticket</h5>
                            <div class="timeline">
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
                        </div>

                        <!-- Cuadro 2: Información del Ticket -->
                        <div class="col-lg-4 col-md-12">
                            <h5>Detalles del Ticket</h5>
                            <p><strong>Título:</strong> {{ $ticket_old->titulo }}</p>
                            <p><strong>Descripción:</strong> {{ $ticket_old->descripcion }}</p>
                            <p><strong>Sociedad:</strong> {{ $ticket_old->sociedad->nombre }}</p>
                            <p><strong>Tipo de Solicitud:</strong> {{ $ticket_old->tipoSolicitud->nombre }}</p>
                            <p><strong>Categoría:</strong> {{ $ticket_old->categoria->nombre }}</p>
                            <p><strong>Subcategoría:</strong> {{ $ticket_old->subcategoria->nombre }}</p>
                            <p><strong>Estado:</strong> {{ $ticket_old->estado->nombre }}</p>
                            <p><strong>Urgencia:</strong> {{ $ticket_old->urgencia->nombre }}</p>
                            <p><strong>Asignado a :</strong> {{ $ticket_old->asignado->name }}</p>
                        </div>

                        <!-- Cuadro 3: Gestión de Comentarios y Archivos -->
                        <div class="col-lg-4 col-md-12">
                            <h5>Comentarios</h5>
                            <div class="direct-chat-messages">
                                @foreach ($ticket_old->comentarios as $comentario)
                                    <div
                                        class="direct-chat-msg {{ $comentario->user_id == Auth::id() ? 'right' : '' }}">
                                        <div class="direct-chat-infos clearfix">
                                            <span
                                                class="direct-chat-name {{ $comentario->user_id == Auth::id() ? 'float-right' : 'float-left' }}">{{ $comentario->user->name ?? 'Anónimo' }}</span>
                                            <span
                                                class="direct-chat-timestamp {{ $comentario->user_id == Auth::id() ? 'float-left' : 'float-right' }}">{{ $comentario->created_at->format('d M Y h:i a') }}</span>
                                        </div>
                                        <div class="direct-chat-text">
                                            {{ $comentario->comentario }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <form wire:submit.prevent="addComment">
                                <div class="input-group">
                                    <input type="text" placeholder="Escribe un comentario ..." class="form-control"
                                        wire:model="newComment">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </span>
                                </div>
                            </form>
                            <form wire:submit.prevent="addFile">
                                <div class="input-group mt-3">
                                    <input type="file" class="form-control" wire:model="newFile">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Subir</button>
                                    </span>
                                </div>
                            </form>
                            <h5 class="mt-3">Archivos Adjuntos</h5>
                            <ul>
                                @foreach ($ticket_old->archivos as $archivo)
                                    <li><a href="{{ Storage::url($archivo->ruta) }}"
                                            target="_blank">{{ $archivo->ruta }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="overlay dark">
                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                </div>
            @endif
        </div>
    </div>
</div>
