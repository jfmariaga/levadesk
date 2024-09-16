<div>
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-bell fa-lg"></i>
        <span class="badge badge-danger navbar-badge">
            {{ count($aprobaciones) + count($cambios) }}
        </span>
    </a>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
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
    </div>
</div>
