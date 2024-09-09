@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle')
        | @yield('subtitle')
    @endif
@stop

{{-- Extend and customize the page content header --}}
@push('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/css/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/toastr/toastr.css?v={{ env('VERSION_STYLE') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/assets/jquery.min.js?v={{ env('VERSION_STYLE') }}"></script>
    <script type="text/javascript" src="/assets/toastr/toastr.js?v={{ env('VERSION_STYLE') }}"></script>
    <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.6.4/compressed/themes/default.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.6.4/compressed/themes/default.date.css">
    <style type="text/css">
        {{-- You can add AdminLTE customizations here --}}
    </style>
    <style>
        .modal-xl {
            max-width: 90%;
            /* Ajusta el porcentaje según lo necesario */
        }

        .picker {
            font-size: 12px;
            position: fixed;
            top: 50%;
            left: 50%;
            /* transform: translate(-50%, -50%); */
            z-index: 1050;
        }

        .picker__holder {
            max-width: 300px;
        }

        .picker__frame {
            box-shadow: none !important;
        }

        .picker__wrap {
            background: white;
        }

        .select2-container {
            width: 100% !important
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2.5em;
            /* Ajusta esto según la altura del select */
            text-align: center;
        }

        .select2-container--default .select2-selection--single {
            height: calc(2.5em + 2px);
            /* Ajusta esto según la altura del select */
            display: flex;
            align-items: center;
        }

        table td {
            vertical-align: middle !important;
        }

        .dataTables_length select {
            height: 30px !important;
        }

        .dataTables_length label {
            margin-top: 1rem !important;
        }

        .table {
            border-collapse: collapse !important;
        }

        .c_red {
            color: rgb(250, 74, 74) !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: black !important;
            /* Cambiar el color del texto a negro */
        }

        /* Tarjetas de resumen */
        .card-total-solucionados {
            background-color: #00B74A;
            color: #ffffff;
        }

        .card-solicitudes-en-proceso {
            background-color: #5C6BC0;
            color: #ffffff;
        }

        .card-solicitudes-por-iniciar {
            background-color: #ff6232;
            color: #ffffff;
        }

        .card-horas-soporte {
            background-color: #8BC34A;
            color: #ffffff;
        }

        /* Prioridades y Estados */
        .prioridad-alta {
            background-color: #FF5252;
            color: #ffffff;
        }

        .prioridad-media {
            background-color: #69F0AE;
            color: #ffffff;
        }

        .prioridad-baja {
            background-color: #40C4FF;
            color: #ffffff;
        }

        .estado-por-iniciar {
            background-color: #FF5252;
            color: #ffffff;
        }

        .estado-en-proceso {
            background-color: #69F0AE;
            color: #ffffff;
        }

        .estado-por-aprobacion {
            background-color: #40C4FF;
            color: #ffffff;
        }

        .solucionado-badge {
            background-color: #e6faf4;
            /* Color de fondo */
            color: #2ec4b6;
            /* Color del texto */
            padding: 5px 10px;
            /* Espaciado interno */
            border-radius: 15px;
            /* Bordes redondeados */
            font-weight: bold;
            /* Texto en negrita */
            font-size: 12px;
            /* Tamaño del texto */
            display: inline-block;
            /* Mostrar como elemento en línea */
        }

        /* Paleta de colores */
        .color-naranja {
            background-color: #FF5722;
            color: white;
        }

        .color-morado {
            background-color: #3F51B5;
            color: white;
        }

        .color-verde {
            background-color: #4CAF50;
            color: white;
        }

        .color-respuesta-azul {
            background-color: #4c8baf;
            color: white;
        }

        .color-azul {
            background-color: #2196F3;
            color: white;
        }

        .color-rojo {
            background-color: #F44336;
            color: white;
        }

        .color-verde-claro {
            background-color: #C8E6C9;
            color: #4CAF50;
        }

        .color-amarillo {
            background-color: #FFEB3B;
            color: #000;
        }

        /* Ejemplo de uso en tarjetas */
        .card-naranja {
            background-color: #FF5722;
            color: white;
            border-radius: 10px;
            padding: 15px;
        }

        .card-morado {
            background-color: #3F51B5;
            color: white;
            border-radius: 10px;
            padding: 15px;
        }

        .card-verde {
            background-color: #4CAF50;
            color: white;
            border-radius: 10px;
            padding: 15px;
        }

        .card-azul {
            background-color: #2196F3;
            color: white;
            border-radius: 10px;
            padding: 15px;
        }

        .card-rojo {
            background-color: #F44336;
            color: white;
            border-radius: 10px;
            padding: 15px;
        }

        .label-verde-claro {
            background-color: #C8E6C9;
            color: #4CAF50;
            border-radius: 10px;
            padding: 5px 10px;
        }

        .label-amarillo {
            background-color: #FFEB3B;
            color: #000;
            border-radius: 10px;
            padding: 5px 10px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 18px;
        }

        /* Ocultamos el checkbox html */
        .switch input {
            display: none;
        }

        /* Formateamos la caja del interruptor sobre la cual se deslizará la perilla de control o slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 18px;
            /* Borde redondo que se ajusta al tamaño reducido */
        }

        /* Pintamos la perilla de control o slider usando el selector before */
        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }

        /* Cambiamos el color de fondo cuando el checkbox esta activado */
        input:checked+.slider {
            background-color: #67bef5;
        }

        /* Deslizamos el slider a la derecha cuando el checkbox esta activado */
        input:checked+.slider:before {
            transform: translateX(16px);
            /* Ajustado para el tamaño reducido del switch */
        }

        /* Aplicamos efecto de bordes redondeados en slider y en el fondo del slider */
        .slider.round {
            border-radius: 20px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .label-switch {
            margin-left: 10px;
            /* Espacio adicional al lado izquierdo del label */
        }
    </style>
@endpush
@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
    @yield('header')
    @yield('modals')
@stop

{{-- Rename section content to content_body --}}

@section('content')
    @yield('content_body')
@stop

{{-- Create a common footer --}}

@section('footer')
    {{-- <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a style="color: rgb(207, 86, 86)" href="{{ config('app.company_url', 'home') }}">
            {{ config('app.company_name', 'Help Desk Levapan') }}
        </a>
    </strong> --}}
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
    <script>
        $(document).ready(function() {
            // Add your common script logic here...
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="/js/show_alerts.js" type="text/javascript"></script>
    <script src="/js/jsuites.js" type="text/javascript"></script>
    <script src="/js/sweetalert2.min.js" type="text/javascript"></script>
    {{-- <script src="/js/fancybox4.js" type="text/javascript"></script> --}}
    <script src="/js/basic.js" type="text/javascript"></script>

    <!-- CDN Alpine.js -->
    <script src="/js/alpine.min.js" defer></script>


    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    {{-- pickDate  --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.6.4/picker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.6.4/picker.date.js"></script> --}}


    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.6.4/compressed/picker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.6.4/compressed/picker.date.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.6.4/compressed/translations/es_ES.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
@endpush

{{-- Add common CSS customizations --}}
