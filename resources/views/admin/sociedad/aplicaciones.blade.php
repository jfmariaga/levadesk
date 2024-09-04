@extends('layouts.app')

@section('subtitle', 'Sociedad | Aplicaciones')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Sociedad-Aplicaciones')

@section('content_body')
    <div class="row">
        <a href="{{ route('sociedad') }}" class="btn btn-sm btn-outline-secondary ml-3 mb-2 float-right"><i
                class="fas fa-angle-double-left"></i> Volver</a>
    </div>
    <div class="fluid">
        <div class="card">
            @livewire('aplicaciones.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.sociedad.modal_aplicacion_add')
@endsection
