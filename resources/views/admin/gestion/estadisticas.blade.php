@extends('layouts.app')

@section('subtitle', 'Mis Estadísticas')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Mis Estadísticas')

@section('content_body')
    <div class="row">
        <a href="{{ route('gestion') }}" class="btn btn-sm btn-outline-secondary ml-3 mb-2 float-right">
            <i class="fas fa-angle-double-left"></i> Volver
        </a>
    </div>
    @livewire('estadisticas.index')
@stop
