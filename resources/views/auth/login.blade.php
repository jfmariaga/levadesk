@extends('adminlte::auth.login')

@section('css')
<style>
    body.login-page {
        background: url('{{ asset('img/Banner.jpg') }}') no-repeat center center fixed;
        background-size: cover;
    }
    .btn-microsoft {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 10px 16px;
        background: #fff;
        color: #2f2f2f;
        font-size: 15px;
        font-weight: 500;
        border: 1px solid #d0d5dd;
        border-radius: 8px;
        text-decoration: none;
        transition: background .15s, border-color .15s, box-shadow .15s;
    }
    .btn-microsoft:hover, .btn-microsoft:focus {
        background: #f7f8fa;
        border-color: #b5bcc8;
        color: #2f2f2f;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .12);
    }
    .btn-microsoft svg { flex: none; }
</style>
@endsection

@section('auth_footer')
    @parent
    @if(config('services.sso.activo'))
        <hr>
        <a href="{{ route('sso.redirect') }}" class="btn-microsoft">
            <svg width="20" height="20" viewBox="0 0 21 21" aria-hidden="true">
                <rect x="1" y="1" width="9" height="9" fill="#f25022"/>
                <rect x="11" y="1" width="9" height="9" fill="#7fba00"/>
                <rect x="1" y="11" width="9" height="9" fill="#00a4ef"/>
                <rect x="11" y="11" width="9" height="9" fill="#ffb900"/>
            </svg>
            <span>Continuar con Microsoft</span>
        </a>
    @endif
@endsection

@if(config('services.sso.activo') && config('services.sso.solo'))
    @push('css')
        <style>.login-card-body form[action$="/login"] { display: none; }</style>
    @endpush
@endif
