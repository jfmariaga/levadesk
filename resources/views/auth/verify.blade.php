@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Verificación de correo'))

@section('css')
    <style>
        .alert-success {
            background-color: #d4edda;
            /* Cambia este valor al color que desees */
            border-color: #c3e6cb;
            /* Cambia este valor si deseas un borde diferente */
            color: #155724;
            /* Cambia el color del texto si es necesario */
        }
    </style>
@endsection

@section('auth_body')

    @if (session('resent'))
        <div class="alert alert-success" style="background-color: #e0f7da; border-color: #c3e6cb; color: #155724;">
            {{ __('Un nuevo enlace de verificación ha sido enviado a tu correo.') }}
        </div>
    @endif

    {{ __('Antes de continuar, por favor revisa tu correo para ver el enlace de verificación.') }}
    {{ __('Si no recibiste el correo, puedes solicitar uno nuevo a continuación.') }}

    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Solicitar otro correo') }}</button>.
    </form>

@stop
