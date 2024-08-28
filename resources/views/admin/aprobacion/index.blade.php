@extends('layouts.app')

@section('subtitle', 'Aprobaciones')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Aprobaciones')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('aprobacion.aprobacion')
        </div>
    </div>
@stop

