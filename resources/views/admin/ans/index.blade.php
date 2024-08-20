@extends('layouts.app')

@section('subtitle', 'ANS')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'ANS')

@section('content_body')
    <div class="fluid">
        <div class="card">
            @livewire('ans.index')
        </div>
    </div>
@stop

@section('modals')
    @include('admin.ans.modal_add_ans')
@endsection
