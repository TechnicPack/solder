@extends('layouts.lite')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">

            <div class="text-center">
                <img alt="Technic-logo" class="logo" height="70" src="{{ URL::asset('img/wrenchIcon.svg') }}">
                <h1>Reset your passwod</h1>
            </div>
            <hr>

            @if (session('status'))
                <div class="row alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                {!! csrf_field() !!}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" placeholder="Email Address" class="form-control input-lg" name="email" value="{{ $email or old('email') }}">
                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" placeholder="Password" class="form-control input-lg" name="password">
                    @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <input type="password" placeholder="Confirm Password" class="form-control input-lg" name="password_confirmation">
                    @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fa fa-btn fa-refresh"></i> Reset Password
                    </button>
                </div>
                <hr>

                <p class="text-center"><a class="text-muted" href="http://technicpack.net/">Powered by the Technic Platform</a></p>

            </form>
        </div>
    </div>
</div>
@endsection
