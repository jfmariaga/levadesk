@extends('layouts.app')

@section('subtitle', 'Impacto')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Impacto')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('impacto.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.impacto.modal_add_impacto')
@endsection
