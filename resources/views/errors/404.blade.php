<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Error - Página no encontrada</title>
    <link href="{{ asset('css/error.css') }}" rel="stylesheet">
</head>

<body>
    <h1>Oops... parece que esta página no existe.</h1>
    <p class="zoom-area"> No te preocupes, estamos aquí para ayudarte. <br> <br> <b>Regresa a nuestra página principal y
            encuentra lo que necesitas. </b></p>
    <section class="error-container">
        <span>4</span>
        <span><span class="screen-reader-text">0</span></span>
        <span>4</span>
    </section>
    <div class="link-container">
        <a href="{{ route('home') }}" class="more-link"><b>Volver al
                inicio</b></a>
    </div>
</body>

</html>
