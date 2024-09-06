@extends('layouts.app')

@section('subtitle', 'Perfil')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Perfil')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('perfil.perfil')
        </div>
    </div>
@stop

{{-- @section('modals')
    @include('admin.impacto.modal_add_impacto')
@endsection --}}
