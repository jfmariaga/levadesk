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
        <div class="row mt-2">
            @if (!auth()->user()->hasAnyRole(['Admin', 'Usuario']))
                <div class="col-md-12">
                    @livewire('supervisor-ticket.supervisor-tickets')
                </div>
            @endif

        </div>
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
