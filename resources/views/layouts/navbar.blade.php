<!-- resources/views/layouts/navbar.blade.php -->

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Navbar items here -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <!-- Fullscreen widget (opcional) -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- Dropdown de notificaciones -->
        <!-- Dropdown de notificaciones -->
        @can('aprobacion')
            <li class="nav-item dropdown">
                @livewire('notifications') <!-- Notificaciones dinámicas -->
            </li>
        @endcan


        <!-- Dropdown de perfil de usuario -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <!-- Imagen del perfil del usuario -->
                <img src="{{ Auth::user()->adminlte_image() }}" class="img-circle elevation-2" alt="User Image"
                    width="30" height="30">
                <!-- Nombre del usuario -->
                <span class="ml-2">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- Rol del usuario -->
                <span class="dropdown-item text-center">{{ Auth::user()->adminlte_desc() }}</span>
                <div class="dropdown-divider"></div>
                <!-- Enlace al perfil -->
                <a href="{{ Auth::user()->adminlte_profile_url() }}" class="dropdown-item">
                    <i class="fas fa-fw fa-user"></i> Perfil
                </a>
                <div class="dropdown-divider"></div>
                <!-- Opción de cerrar sesión -->
                <a href="{{ route('logout') }}" class="dropdown-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
