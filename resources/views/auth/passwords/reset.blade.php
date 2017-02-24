@component('layouts.app')

<section class="hero is-primary">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Reset Password
            </h1>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">

        @if (session('status'))
        <div class="notification is-success">
            @foreach($errors->all() as $message)
            {{ session('status') }}
            @endforeach
        </div>
        @endif

        @if( count($errors) )
        <div class="notification is-warning">
            <ul>
                @foreach($errors->all() as $message)
                <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="post" action="{{ route('password.request') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            {{-- email --}}
            <label class="label">Email Address</label>
            <p class="control">
                <input class="input is-expanded" type="text" name="email" value="{{ $email or old('email') }}">
            </p>

            {{-- password --}}
            <label class="label">Password</label>
            <p class="control">
                <input class="input is-expanded" type="password" name="password">
            </p>

            {{-- password-confirm --}}
            <label class="label">Confirm Password</label>
            <p class="control">
                <input class="input is-expanded" type="password" name="password_confirmation">
            </p>

            {{-- Submit --}}
            <div class="control is-grouped">
                <p class="control is-horizontal">
                    <button class="button is-primary" type="submit">Reset Password</button>
                </p>
            </div>

        </form>

    </div>
</section>

@endcomponent
