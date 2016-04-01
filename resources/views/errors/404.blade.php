@extends('layouts.lite')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">

                <div class="text-center">
                    <img alt="Technic-logo" class="logo" src="/img/error.png">
                    <h1>404 Chunk Error</h1>
                </div>
                <hr>

                <p class="lead text-center">The page doesn't exist or some other horrible error has occurred.</p>

                <hr>
                <p class="text-center"><a href="{{ URL::previous()  }}">Go back</a> or visit the <a href="{{ url('/') }}">home page</a>.</p>
            </div>
        </div>
    </div>
@stop