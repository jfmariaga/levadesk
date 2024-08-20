@extends('layouts.app')

@section('subtitle', 'Ticket')
@section('content_header_title', 'Inicio')
@section('content_header_subtitle', 'Ticket')

@section('content_body')
    <div class="container-fluid">
        <div class="row mt-3">
            {{-- <div class="col-md-6">
                <div class="card">
                    @livewire('ticket.form-tickets')
                </div>
            </div> --}}
            <div class="col-md-12">
                <div class="card">
                    @livewire('ticket.index')
                </div>
            </div>
        </div>
    </div>
@stop

@section('modals')
    @include('admin.ticket.modal_add_ticket')
    @include('admin.ticket.modal_ver_ticket')
@endsection
