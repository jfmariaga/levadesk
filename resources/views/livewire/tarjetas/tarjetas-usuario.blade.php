<div>
    <style>
        .card {
            font-size: 0.9rem;
            margin: 3px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
    </style>
    @if (Auth::user()->id === $usuarioId->id)
        <div class="row">
            <div class="col-md-2">
                <div class="card small-box card-solicitudes-por-iniciar" wire:click="filtrarTicketsPorEstado([1])">
                    <div class="inner">
                        <h3>{{ $ticketsAbiertos }}</h3>
                        <p>Por iniciar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-folder"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card small-box card-solicitudes-en-proceso"
                    wire:click="filtrarTicketsPorEstado([3, 8, 7, 6, 9, 10, 11, 12, 13, 14, 15, 16])">
                    <div class="inner">
                        <h3>{{ $ticketsEnProceso }}</h3>
                        <p>En atención</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card small-box card-total-solucionados" wire:click="filtrarTicketsPorEstado([4])">
                    <div class="inner">
                        <h3>{{ $ticketsCerrados }}</h3>
                        <p>Solucionados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card small-box card-tickets-rechazados" wire:click="filtrarTicketsPorEstado([5])">
                    <div class="inner">
                        <h3>{{ $ticketsRechazados }}</h3>
                        <p>Rechazados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            @if (
                $usuarioId->roles->first()->name == 'Admin' ||
                    $usuarioId->roles->first()->name == 'Aprobador' ||
                    $usuarioId->roles->first()->name == 'Agente')
                <div class="col-md-2">
                    <div class="card small-box bg-info">
                        <div class="inner">
                            <h3>{{ $aprobacion }}</h3>
                            <p>Por aprobar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-thumbs-up"></i>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-2">
                <div class="card small-box color-azul" wire:click="filtrarTicketsPorEstado([])">
                    <div class="inner">
                        <h3>{{ $ticketsTotal }}</h3>
                        <p>Total tickets</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
