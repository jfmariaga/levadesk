@extends('layouts.app')

@section('subtitle', 'Urgencia')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Urgencia')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('urgencia.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.urgencia.modal_add_urgencia')
@endsection
