@extends('layouts.app')

@section('subtitle', 'Categorias')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Dashboard')

@section('content_body')
    <div class="fluid">
        @livewire('graficas.ticket-sociedad-chart')
    </div>
@stop
