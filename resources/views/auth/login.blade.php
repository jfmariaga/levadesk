@extends('adminlte::auth.login')

@section('css')
<style>
    body.login-page {
        background: url('{{ asset('img/Banner.jpg') }}') no-repeat center center fixed;
        background-size: cover;
    }
</style>
@endsection
