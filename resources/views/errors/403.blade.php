<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Error - Acceso Denegado</title>
    <link href="{{ asset('css/error.css') }}" rel="stylesheet">
</head>

<body>
    <h1>Acceso Denegado.</h1>
    <p class="zoom-area"> Parece que no tienes los permisos necesarios para ingresar a esta página o recurso. <br> <br>
        <b>Regresa a nuestra página principal y encuentra lo que necesitas. </b>
    </p>
    <section class="error-container">
        <span>4</span>
        <span><span class="screen-reader-text">0</span></span>
        <span>3</span>
    </section>
    <div class="link-container">
        <a href="{{ route('home') }}" class="more-link"><b>Volver al
                inicio</b></a>
    </div>
</body>

</html>
