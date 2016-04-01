@extends('layouts.lite')

<?php
$stack = '';
$stackNum = 1;
foreach ($e->getTrace() as $trace) {
    $stack .= " $stackNum. ";
    $stack .= $trace['function'] ? sprintf('at %s%s%s(%s)', $trace['short_class'], $trace['type'], $trace['function'], '') : '';
    $stack .= $trace['file'] ? sprintf('in %s line %s', basename($trace['file']), $trace['line']) : '';
    $stack .= "\n";
    $stackNum++;
}
$title = urlencode($e->getMessage() . ' [' . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . ']');
$body = urlencode(
        "### Description of bug\nWrite a description...\n\n" .
        "### Steps to reproduce\n - Step 1\n - Step 2\n - ...\n\n" .
        "### Environment\n - Solder: " . '' . "\n - Env: " . app('env') . "\n - DB: " . Config::get('database.default') . "\n - Laravel: " . app()->version() . "\n - PHP: " . phpversion() . "\n - Server: " . $_SERVER["SERVER_SOFTWARE"] . "\n - User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n\n" .
        "### Stack Trace\n```\n" . $stack . "\n```"
);
?>


@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-10 col-md-offset-1">

                <img alt="Technic-logo" class="logo" src="/img/error.png">
                <h1>Look's like we've hit a snag!</h1>
                <hr>

                <p class="lead">{{ $e->getMessage() }} Blame the developer</p>

                <hr>

                <ul class="nav nav-pills nav-justified">
                    <li role="presentation"><i class="fa fa-fw fa-fire"></i> Error #{{ $e->getCode() }}</li>
                    <li role="presentation"><i class="fa fa-fw fa-exclamation"></i> {{ $e->getClass() }}</li>
                    <li role="presentation"><i class="fa fa-fw fa-file-code-o"></i> in {{ basename($e->getFile()) }}
                    </li>
                    <li role="presentation"><i class="fa fa-fw fa-i-cursor"></i> line {{ $e->getLine() }}</li>
                    <li role="presentation" class="toggle"><i class="fa fa-fw fa-search"></i> Stack Trace</li>
                    <li role="presentation"><a
                                href="https://github.com/indemnity83/technicsolder/issues/new?title={{ $title }}&body={{ $body }}"
                                target="_blank"><i class="fa fa-fw fa-github"></i> Get Help</a></li>
                </ul>
                <br>
                <samp id="trace" class="text-left hidden">
                    <ol>
                        @foreach($e->getTrace() as $trace)
                            <li>
                                @if($trace['function']) {{ sprintf('at %s%s%s(%s)', $trace['short_class'], $trace['type'], $trace['function'], '') }} @endif
                                @if($trace['file']) <span
                                        class="text-muted">{{ sprintf('in %s line %s', basename($trace['file']), $trace['line']) }}</span> @endif
                            </li>
                        @endforeach
                    </ol>
                </samp>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="application/javascript">
        $('.toggle').click(function () {
            $('#trace').toggleClass('hidden show');
        });
    </script>


@stop