@extends('layouts.app')

@section('subtitle', 'Terceros')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Terceros')

@section('content_body')
    <div class="fluid">
        @livewire('terceros.index')
    </div>
@stop

@section('modals')
@endsection
