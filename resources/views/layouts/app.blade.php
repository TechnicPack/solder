<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Solder')</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

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
            @include('partials.navigation')

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @stack('afterScripts')
</body>
</html>
