@extends('layouts.app')

@section('subtitle', 'Ver de tickets')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Ver ticket')


@section('content_body')
    <div class="row">
        <a href="{{route('ticket')}}" class="btn btn-sm btn-outline-secondary ml-3 mb-2 float-right"><i class="fas fa-angle-double-left"></i> Volver</a>
    </div>
    @livewire('ticket.ver-ticket')
@stop
