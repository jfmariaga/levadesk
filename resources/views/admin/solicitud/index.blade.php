@extends('layouts.app')

@section('subtitle', 'Solicitud')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Tipo de solicitud')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('solicitud.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.solicitud.modal_add_solicitud')
@endsection
