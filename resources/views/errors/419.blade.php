<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 Error - Sesión expirada</title>
    <link href="{{ asset('css/error.css') }}" rel="stylesheet">
</head>

<body>
    <h1>¡Ups! Parece que tu sesión ha terminado.</h1>
    <p class="zoom-area"> Esto puede ocurrir cuando estás inactivo por un tiempo. <br> <br> <b>Actualiza la página o
            inicia sesión nuevamente para continuar. </b></p>
    <section class="error-container">
        <span>4</span>
        <span>1</span>
        <span>9</span>
    </section>
    <div class="link-container">
        <a href="{{ route('home') }}" class="more-link"><b>Volver al
                inicio</b></a>
    </div>
</body>

</html>
