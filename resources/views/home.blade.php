@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Bienvenido')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Bienvenido')

{{-- Content body: main page content --}}

@section('content_body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <p>Bienvenid@ <strong>{{ auth()->user()->name }}</strong> Este es el resumen de tus Tickets</p>

                @livewire('tarjetas.tarjetas-usuario')
            </div>
        </div>
        @if (auth()->user()->hasRole('Admin'))
            <div class="row mt-2">
                .{{--  punto --}}
                {{-- <div class="col-md-6 col-lg-6 mt-2">
                @livewire('graficas.ticket-estado-chart')
            </div> --}}
                {{-- <div class="col-md-6 col-lg-6 mt-2">
                    @livewire('graficas.ticket-sociedad-chart')
                </div> --}}
            </div>
        @endif
    </div>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script>
        // console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@endpush
