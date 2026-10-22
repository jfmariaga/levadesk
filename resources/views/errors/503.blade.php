<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página en Mantenimiento</title>
    <link href="{{ asset('css/error.css') }}" rel="stylesheet">
    <style>
        .contactos {
            max-width: 960px;
            margin: 10px auto 30px;
            padding: 0 16px;
            font-family: 'Montserrat', sans-serif;
        }

        .contactos .aviso {
            background: #fff4e5;
            border-left: 5px solid #be0b0b;
            padding: 14px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
            color: #333;
            line-height: 1.5;
        }

        .contactos .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
        }

        .contactos .sociedad {
            background: #fff;
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            padding: 14px 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .contactos .sociedad h3 {
            margin: 0 0 10px;
            font-size: 16px;
            color: #be0b0b;
        }

        .contactos ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .contactos li {
            padding: 6px 0;
            border-top: 1px solid #f0f0f0;
            font-size: 14px;
        }

        .contactos li:first-child {
            border-top: none;
        }

        .contactos li a {
            display: block;
            color: #555;
            font-size: 13px;
            word-break: break-all;
        }
    </style>
</head>

<body>
    @php
        // Lista fija: la página debe mostrarse aunque la base de datos no esté disponible durante el mantenimiento.
        $supervisoresPorSociedad = [
            'LEVAPAN' => [
                ['Wilson Perez Lizarazo', 'wilson.perez@levapan.com'],
                ['Yamile Campos', 'yamile.campos@levapan.com'],
                ['Angela Valdéz', 'angela.valdez@levapan.com'],
            ],
            'LEVACOL' => [
                ['Wilson Perez Lizarazo', 'wilson.perez@levapan.com'],
                ['Yamile Campos', 'yamile.campos@levapan.com'],
                ['Angela Valdéz', 'angela.valdez@levapan.com'],
            ],
            'TULUÁ' => [
                ['Wilson Perez Lizarazo', 'wilson.perez@levapan.com'],
                ['Yamile Campos', 'yamile.campos@levapan.com'],
                ['Angela Valdéz', 'angela.valdez@levapan.com'],
            ],
            'PANAL' => [
                ['Andrés Macias', 'coordinador.tecnologia@panalsas.com'],
                ['Wilson Perez Lizarazo', 'wilson.perez@levapan.com'],
                ['Yamile Campos', 'yamile.campos@levapan.com'],
                ['Angela Valdéz', 'angela.valdez@levapan.com'],
            ],
            'ECUADOR' => [
                ['Cesar Torres H', 'ctorresh@levapan.com.ec'],
                ['Yamile Campos', 'yamile.campos@levapan.com'],
                ['Angela Valdéz', 'angela.valdez@levapan.com'],
            ],
            'REPÚBLICA DOMINICANA' => [
                ['Alfonso Rodriguez', 'alfonso.rodriguez@levapan.com.do'],
                ['Angela Valdéz', 'angela.valdez@levapan.com'],
            ],
        ];
    @endphp

    <h1>Estamos en mantenimiento</h1>
    <p class="zoom-area">
        Estamos trabajando para mejorar tu experiencia. <br><br>
        <b>Vuelve pronto, estaremos de regreso en unos momentos.</b>
    </p>

    <section class="error-container">
        <span>Mantenimiento</span><br>
        <span><span class="screen-reader-text">⚙</span></span>
    </section>

    <section class="contactos">
        <div class="aviso">
            Mientras dura el mantenimiento, puedes comunicarte con los supervisores de tu sociedad
            <b>únicamente para temas urgentes que comprometan la continuidad del negocio</b>.
        </div>

        <div class="grid">
            @foreach ($supervisoresPorSociedad as $sociedad => $supervisores)
                <div class="sociedad">
                    <h3>{{ $sociedad }}</h3>
                    <ul>
                        @foreach ($supervisores as [$nombre, $email])
                            <li>
                                <b>{{ $nombre }}</b>
                                <a href="mailto:{{ $email }}">{{ $email }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>

    <div class="link-container">
        <a href="{{ route('home') }}" class="more-link"><b>Intentar de nuevo</b></a>
    </div>
</body>

</html>
