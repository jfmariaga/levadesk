@extends('layouts.app')

@section('subtitle', 'Grupos')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Grupos')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('grupo.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.grupo.modal_add_grupo')
@endsection
