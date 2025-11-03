@extends('layouts.app')

@section('subtitle', 'Flujo Terceros')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Flujo Terceros')

@section('content_body')
    <div class="fluid">
        @livewire('flujos-terceros.indesx')
    </div>
@stop

@section('modals')
@endsection
