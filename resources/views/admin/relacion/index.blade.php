@extends('layouts.app')

@section('subtitle', 'Grupo-Sociedad-Subcategoría')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Grupo-Sociedad-Subcategoría')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('sociedad-subcategoria-grupo')
        </div>
    </div>
@stop
