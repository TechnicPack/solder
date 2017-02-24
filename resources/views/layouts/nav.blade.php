<nav class="nav has-shadow" id="top">
  <div class="container">
    <div class="nav-left">
      <a class="nav-item" href="/">
        Technic Solder
      </a>
    </div>
    <span class="nav-toggle" onclick="toggleNav()">
      <span></span>
      <span></span>
      <span></span>
    </span>
    <div id="nav-menu" class="nav-right nav-menu">
      <a href="/home" class="nav-item is-tab">
        Dashboard
      </a>
      <a href="#" class="nav-item is-tab">
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
            <a class="button" href="{{ route('login') }}">
              Log in
            </a>
            <a class="button is-info" href="{{ route('register') }}">
              Sign up
            </a>
          </span>
      @else
          <span class="nav-item" href="{{ route('login') }}">
              <a class="button" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                  Logout
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
              </form>
          </span>
      @endif
    </div>
  </div>
</nav>
