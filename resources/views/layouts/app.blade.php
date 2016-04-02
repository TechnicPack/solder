<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Solder</title>

    <link href="{{ asset(elixir('css/bootstrap.css')) }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    @yield('stylesheet')

</head>

<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('/img/title.png') }}" alt="Solder"> v0.8.0
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav navbar">
                    {{-- TODO: Implement Update Check --}}
                    @if (true)
                        <li><a href="#" style="color:orangered;">Update Available! <i class="fa fa-exclamation-triangle"></i></a></li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="http://docs.solder.io/" target="_blank">Help <i class="fa fa-question"></i></a></li>
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4 col-md-3 container-sidebar">
                <ul class="nav nav-stacked nav-sidebar" id="nav-sidebar">
                    <li><a href="{{ url('/') }}">Dashboard</a></li>
                    <li>
                        <a data-toggle="collapse" data-parent="nav-sidebar" href="#nav-modpacks">
                            <i class="fa fa-folder fa-fw"></i>Modpacks <span class="caret"></span>
                        </a>
                        <ul id="nav-modpacks" class="nav nav-children nav-stacked collapse">
                            <li><a href="{{ route('modpack.index') }}">Modpack List</a></li>
                            <li><a href="{{ route('modpack.create') }}">Add Modpack</a></li>
                        </ul>
                    </li>
                    <li>
                        <a data-toggle="collapse" data-parent="nav-sidebar" href="#nav-mods">
                            <i class="fa fa-book fa-fw"></i>Mods <span class="caret"></span>
                        </a>
                        <ul id="nav-mods" class="nav nav-children nav-stacked collapse">
                            <li><a href="{{ route('mod.index') }}">Mod List</a></li>
                            <li><a href="{{ route('mod.create') }}">Add Mod</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-sm-8 col-md-9 container-content">

                @if (Session::has('flash_message'))
                    <div class="alert alert-success">
                        {{ Session::get('flash_messsage') }}
                    </div>
                @endif

                @yield('content')

            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset(elixir('js/app.js')) }}"></script>
    @yield('script')

</body>

</html>
