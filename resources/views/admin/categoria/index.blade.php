@extends('layouts.app')

@section('subtitle', 'Categorias')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Categorias')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('categoria.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.categoria.modal_add_categoria')
@endsection
