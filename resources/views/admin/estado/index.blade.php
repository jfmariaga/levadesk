@extends('layouts.app')

@section('subtitle', 'Estados')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Estados')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('estado.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.estado.modal_add_estado')
@endsection
