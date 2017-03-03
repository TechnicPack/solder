<div class="hero-head">
    <header class="nav">
        <div class="container">

            <div class="nav-left">
                <a class="nav-item"><img alt="Logo" src="/img/logo.svg"> <span class="title">solder</span></a>
            </div>

            <span class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </span>

            <div class="nav-right nav-menu">
                <a class="nav-item is-tab" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-item is-tab" href="{{ route('modpacks.index') }}">Modpacks</a>
                <a class="nav-item is-tab" href="#">Resources</a>
                <a class="nav-item is-tab" href="#">Settings</a>

                @if (Auth::guest()) <span class="nav-item">
                    <a class="button is-inverted is-primary" href="{{ route('login') }}">Log in</a>
                    <a class="button is-inverted is-primary" href="{{ route('register') }}">Sign up</a>
                </span> @else <span class="nav-item">
                    <a class="button is-inverted is-primary" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span>Logout</span></a>

                    <form action="{{ route('logout') }}" id="logout-form" method="post" name="logout-form" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </span> @endif

            </div>

        </div>
    </header>
</div>
