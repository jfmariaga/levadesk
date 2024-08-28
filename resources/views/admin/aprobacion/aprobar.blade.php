@extends('layouts.app')

@section('subtitle', 'Aprobaciones')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Aprobaciones')

@section('content_body')
    <div class="row">
        <a href="{{ route('aprobacion') }}" class="btn btn-sm btn-outline-secondary ml-3 mb-2 float-right"><i
                class="fas fa-angle-double-left"></i> Volver</a>
    </div>
    <div class="fluid">
        @livewire('aprobacion.aprobar')
    </div>
@stop
