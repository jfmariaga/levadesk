<div>
    <style>
        .card {
            font-size: 0.9rem;
            margin: 10px;
            /* Añade margen alrededor de las tarjetas */
            border: none;
            border-radius: 10px;
        }
    </style>

    @if (Auth::user()->id === $usuarioId)
        <div class="row">
            <div class="col-md-3">
                <div class=" card small-box card-solicitudes-por-iniciar">
                    <div class="inner">
                        <h3>{{ $ticketsAbiertos }}</h3>
                        <p>Tickets Por Iniciar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-folder"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card small-box card-solicitudes-en-proceso">
                    <div class="inner">
                        <h3>{{ $ticketsEnProceso }}</h3>
                        <p>Tickets en atención</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card small-box card-total-solucionados">
                    <div class="inner">
                        <h3>{{ $ticketsCerrados }}</h3>
                        <p>Tickets Solucionados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card small-box color-azul">
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
