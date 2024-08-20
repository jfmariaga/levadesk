@extends('layouts.app')

@section('subtitle', 'Cargos')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Cargos')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('cargos.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.cargo.modal_add_cargo')
@endsection
