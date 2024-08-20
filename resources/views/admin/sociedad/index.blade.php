@extends('layouts.app')

@section('subtitle', 'Sociedad')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Sociedad')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('sociedad.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.sociedad.modal_add')
@endsection
