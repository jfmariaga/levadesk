@extends('layouts.app')

@section('subtitle', 'SubCategorias')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'SubCategorias')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('subcategoria.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.subcategoria.modal_add_subcategoria')
@endsection
