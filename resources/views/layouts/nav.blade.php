<div class="hero-head">
    <header class="nav">
        <div class="container">
            <div class="nav-left">
                <a class="nav-item">
                    <img src="/img/logo.svg" alt="Logo">
                    <span class="title">solder</span>
                </a>
            </div>
            <span class="nav-toggle">
          <span></span>
          <span></span>
          <span></span>
        </span>
            <div class="nav-right nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item is-tab">
                    Dashboard
                </a>
                <a href="{{ route('modpacks.index') }}" class="nav-item is-tab">
                    Modpacks
                </a>
                <a href="#" class="nav-item is-tab">
                    Resources
                </a>
                <a href="#" class="nav-item is-tab">
                    Settings
                </a>
                @if (Auth::guest())
                    <span class="nav-item">
            <a class="button is-inverted" href="{{ route('login') }}">
              Log in
            </a>
            <a class="button is-info is-inverted" href="{{ route('register') }}">
              Sign up
            </a>
          </span>
                @else
                    <span class="nav-item" href="{{ route('login') }}">
              <a class="button is-inverted is-primary" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                  <span>Logout</span>
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
              </form>
          </span>
                @endif
            </div>
        </div>
    </header>
</div>