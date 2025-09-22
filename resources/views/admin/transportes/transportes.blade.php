@extends('layouts.app')

@section('subtitle', 'Dashboard')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Dashboard Tareas/OT')

@section('content_body')
    <div class="fluid">
        @livewire('transportes.index')
    </div>
@stop
