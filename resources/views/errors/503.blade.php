<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página en Mantenimiento</title>
    <link href="{{ asset('css/error.css') }}" rel="stylesheet">
</head>

<body>
    <h1>Estamos en mantenimiento</h1>
    <p class="zoom-area">
        Estamos trabajando para mejorar tu experiencia. <br><br>
        <b>Vuelve pronto, estaremos de regreso en unos momentos.</b>
    </p>

    <section class="error-container">
        <span>Mantenimiento</span><br>
        <span><span class="screen-reader-text">⚙</span></span>
    </section>

    <div class="link-container">
        <a href="{{ route('home') }}" class="more-link"><b>Intentar de nuevo</b></a>
    </div>
</body>

</html>
