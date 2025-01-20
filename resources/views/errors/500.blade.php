<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Error - Servidor</title>
    <link href="{{ asset('css/error.css') }}" rel="stylesheet">
</head>

<body>
    <h1>¡Ups! Algo no salió como esperábamos.</h1>
    <p class="zoom-area"> Nuestro equipo ya está trabajando para solucionarlo. <br> <br> <b>Regresa a nuestra página
            principal y vuelve a intentarlo en unos minutos. </b></p>
    <section class="error-container">
        <span>5</span>
        <span><span class="screen-reader-text">0</span></span>
        <span>0</span>

    </section>
    <div class="link-container">
        <a href="{{ route('home') }}" class="more-link"><b>Volver al
                inicio</b></a>
    </div>
</body>

</html>
