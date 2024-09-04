@extends('layouts.app')

@section('subtitle', 'Aprobaciones-Cambio')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Aprobaciones-Cambio')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('aprobacion.aprobacion-cambios')
        </div>
    </div>
@stop

