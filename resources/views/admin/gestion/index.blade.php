@extends('layouts.app')

@section('subtitle', 'Gestión de tickets')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Gestión de tickets')

@section('content_body')
    @livewire('gestion.index')
@stop
