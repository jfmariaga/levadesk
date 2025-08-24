<div>
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-bell fa-lg"></i>
        <span class="badge badge-danger navbar-badge">
            {{ count($aprobaciones) + count($cambios) + count($tareas) + count($tareasCount) + count($porFinalizar)}}
        </span>
    </a>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        @if (count($aprobaciones) > 0 || count($cambios) > 0 || count($tareas) > 0)
            @if (count($aprobaciones) > 0)
                <span class="dropdown-item dropdown-header">
                    Accesos pendientes por aprobar:
                </span>
                @foreach ($aprobaciones as $aprobacion)
                    <a href="{{ url('/aprobar?ticket_id=' . $aprobacion->ticket_id) }}" class="dropdown-item">
                        Aprobar ticket # {{ $aprobacion->ticket->nomenclatura }}
                    </a>
                @endforeach
            @endif

            @if (count($cambios) > 0)
                <span class="dropdown-item dropdown-header">
                    Cambios pendientes por aprobar:
                </span>
                @foreach ($cambios as $cambio)
                    <a href="{{ url('/cambio?ticket_id=' . $cambio->ticket_id) }}" class="dropdown-item">
                        Aprobar cambio de ticket # {{ $cambio->ticket->nomenclatura }}
                    </a>
                @endforeach
            @endif

            @if (count($tareas) > 0)
                <span class="dropdown-item dropdown-header">
                    Tareas pendientes:
                </span>
                @foreach ($tareas as $tarea)
                    <a href="{{ url('/gestionar?ticket_id=' . $tarea->ticket_id) }}" class="dropdown-item">
                        Tarea pendiente del ticket # {{ $tarea->ticket->nomenclatura }}
                    </a>
                @endforeach
            @endif
            @if (count($tareasCount) > 0)
                <span class="dropdown-item dropdown-header">
                    Tareas pendientes por autorizar:
                </span>
                @foreach ($tareasCount as $tarea)
                    <a href="{{ url('/gestionar?ticket_id=' . $tarea->ticket_id) }}" class="dropdown-item">
                        Tarea por autorizar del ticket # {{ $tarea->ticket->nomenclatura }}
                    </a>
                @endforeach

            @endif
             @if (count($porFinalizar) > 0)
                <span class="dropdown-item dropdown-header">
                    Tickets por finalizar:
                </span>
                @foreach ($porFinalizar as $t)
                    <a href="{{ url('/gestionar?ticket_id=' . $t->id) }}" class="dropdown-item">
                        Finalizar ticket # {{ $t->nomenclatura }}
                    </a>
                @endforeach
            @endif
        @else
            <span class="dropdown-item dropdown-header">
                Sin notificaciones
            </span>
        @endif
    </div>
</div>
