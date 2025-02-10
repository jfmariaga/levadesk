@extends('layouts.app')

@section('subtitle', 'Gestión de tickets')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Gestión de tickets')


@section('content_body')
    {{-- <div class="row">
        @if (auth()->user()->hasRole('Admin'))
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary ml-3 mb-2 float-right"><i
                    class="fas fa-angle-double-left"></i> Volver</a>
        @else
            <a href="{{ route('gestion') }}" class="btn btn-sm btn-outline-secondary ml-3 mb-2 float-right"><i
                    class="fas fa-angle-double-left"></i> Volver</a>
        @endif
    </div> --}}
    @livewire('gestion.show')
@stop
