@component('layouts.app')

<section class="hero is-primary">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Login
            </h1>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">

        @if( count($errors) )
        <div class="notification is-warning">
            <ul>
                @foreach($errors->all() as $message)
                <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="post" action="{{ route('login') }}">
            {{ csrf_field() }}

                {{-- email --}}
                <label class="label">Email Address</label>
                <p class="control">
                    <input class="input is-expanded" type="text" name="email" value="{{ old('email') }}">
                </p>

                {{-- password --}}
                <label class="label">Password</label>
                <p class="control">
                    <input class="input is-expanded" type="password" name="password">
                </p>

                {{-- remember --}}
                <p class="control">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </p>

                {{-- Submit --}}
                <div class="control is-grouped">
                    <p class="control is-horizontal">
                        <button class="button is-primary" type="submit">Submit</button>
                    </p>
                    <p class="control">
                        <a class="button is-link" href="/">Cancel</a>
                    </p>
                    <p class="control">
                        <a class="button is-link" href="{{ route('password.request') }}">Forgot Password?</a>
                    </p>
                </div>

        </form>

    </div>
  </section>

@endcomponent
