<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- IncanatoIT">
    <meta name="author" content="Incanatoit.com">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Sistema Aportes - CDBF</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link href="css/plantilla.css" rel="stylesheet">

    <style>
        html {
        min-height: 100%;
        position: relative;
        }
        body {
        margin: 0;
        margin-bottom: 2px;
        }
        footer {
        background-color: black;
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 40px;
        color: white;
        }
    </style>
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden" style="padding-bottom: 70px; ">
    <div id="app">
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item ">
                <a class="nav-link nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="img/avatars/6.png" class="img-avatar">
                    <span class="d-md-down">{{Auth::user()->usuario}} </span>
                </a>
            </li>&nbsp;&nbsp;
        </ul>
    </header>

    <div class="app-body">
        @if(Auth::check())
            @if(Auth::user()->idrol==1)
                <!--contenido de la barra lateral admin-->
                @include('plantilla.sidebaradministrador')
            @elseif (Auth::user()->idrol==2)
                <!--contenido de la barra lateral vendedor-->
                @include('plantilla.sidebarafiliado')
            @else
            @endif
        @endif
        <!-- Contenido Principal -->
        @yield('contenido')
        <!-- /Fin del contenido principal -->
    </div>
    </div>
    <footer class="app-footer navbar-fixed-bottom">
        
        <a class="btn btn-default" href=" {{route('logout')}} " onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
            <i class="fa fa-lock"></i> Cerrar sesión
        </a>

        <form id="logout-form" action=" {{ route('logout')}} " method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

    </footer>
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
</body>
</html>