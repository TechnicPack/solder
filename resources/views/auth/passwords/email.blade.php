@component('layouts.app')

    @slot('hero')
        <h1 class="title">Reset Password</h1>
    @endslot

<section class="section">
    <div class="container">

        @if (session('status'))
        <div class="notification is-success">
            {{ session('status') }}
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

        <form method="post" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            {{-- email --}}
            <label class="label">Email Address</label>
            <p class="control">
                <input class="input is-expanded" type="text" name="email" value="{{ old('email') }}">
            </p>

            {{-- Submit --}}
            <div class="control is-grouped">
                <p class="control is-horizontal">
                    <button class="button is-primary" type="submit">Send Password Reset Link</button>
                </p>
            </div>
        </form>

    </div>
</section>

@endcomponent
