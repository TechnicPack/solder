<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Solder')</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/app.css">

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>

    <div id="app" class="columns is-gapless full-height">
        <div class="column is-narrow has-background-dark has-text-grey-light">
            @include('partials.directory')
        </div>

        @if (View::hasSection('menu'))
            <div class="column is-one-quarter is-2-fullhd has-background-primary">
                @yield('menu')
            </div>
        @endif

        <div class="column has-background-light">
            <div class="container">
                @include('partials.navigation')

                @yield('content')
            </div>

            <footer class="footer">
                <div class="container">
                    <div class="content has-text-centered">
                        <p class="has-text-grey">
                            Made with
                            <a href="http://patreon.com/indemnity83">
                                <i title="love" class="fa fa-heart" aria-hidden="true"></i>
                                <span class="sr-only">love</span>
                            </a>
                            &amp;
                            <a href="http://ko-fi.com/solder">
                                <i title="coffee" class="fa fa-coffee" aria-hidden="true"></i>
                                <span class="sr-only">coffee</span>
                            </a>
                            in
                            <a class="has-text-grey" href="https://www.google.com/maps/place/Yuba%20City,CA">Yuba City CA.</a>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="/js/app.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @stack('afterScripts')
</body>
</html>
