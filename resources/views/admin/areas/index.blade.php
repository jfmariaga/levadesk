@extends('layouts.app')

@section('subtitle', 'Áreas')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Seleccionar Área')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('seleccionar-area')
        </div>
    </div>
@stop


