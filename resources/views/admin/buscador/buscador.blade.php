@extends('layouts.app')

@section('subtitle', 'Buscador')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Buscador de transportes')

@section('content_body')
    <div class="fluid">
        @livewire('buscador.index')
    </div>
@stop
