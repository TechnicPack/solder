@component('layouts.app')

<section class="hero is-primary">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">
                Register
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

        <form method="post" action="{{ route('register') }}">
            {{ csrf_field() }}

            {{-- email --}}
            <label class="label">Name</label>
            <p class="control">
                <input class="input is-expanded" type="text" name="name" value="{{ old('name') }}">
            </p>

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

            {{-- password-confirm --}}
            <label class="label">Confirm Password</label>
            <p class="control">
                <input class="input is-expanded" type="password" name="password_confirmation">
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
                    <a class="button is-link" href="{{ route('login') }}">Login</a>
                </p>
            </div>

        </form>

    </div>
</section>

@endcomponent
