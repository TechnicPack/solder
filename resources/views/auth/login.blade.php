@extends('layouts.lite')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">

            <div class="text-center">
                <img alt="Technic-logo" class="logo" height="70" src="{{ URL::asset('img/wrenchIcon.svg') }}">
                <h1>Technic Solder</h1>
            </div>
            <hr>

            @if (session('status'))
                <div class="row alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {!! csrf_field() !!}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" placeholder="Email Address" class="form-control input-lg" name="email" value="{{ old('email') }}"> @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span> @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" placeholder="Password" class="form-control input-lg" name="password"> @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span> @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fa fa-btn fa-sign-in"></i> Login
                    </button>
                </div>
                <hr>

                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> Remember Me
                        </label>
                        <a class="pull-right" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                    </div>
                </div>

                <p class="text-center"><a class="text-muted" href="http://technicpack.net/">Powered by the Technic Platform</a></p>

            </form>
        </div>
    </div>
</div>
@endsection
