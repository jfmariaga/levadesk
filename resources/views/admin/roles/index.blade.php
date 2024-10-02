@extends('layouts.app')

@section('subtitle', 'Roles')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Roles y permisos')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('roles.role-manager')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.roles.modal_add')
@endsection
