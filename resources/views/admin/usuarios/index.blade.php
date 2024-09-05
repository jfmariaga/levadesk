@extends('layouts.app')

@section('subtitle', 'Usuarios')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Usuarios')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('usuarios.index')
        </div>
    </div>
@stop

{{-- @section('modals')
    @include('admin.ans.modal_add_ans')
@endsection --}}
