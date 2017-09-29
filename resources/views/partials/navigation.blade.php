<nav class="navbar border-bottom-grey-light" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        &nbsp;
    </div>
    <div class="navbar-menu">
        <div class="navbar-end">
            <a class="navbar-item" href="/library"> Library </a>
            <a class="navbar-item" href="/settings/keys"> Settings </a>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link"> {{ Auth()->user()->name }} </a>
                <div class="navbar-dropdown is-right is-radiusless">
                    <a class="navbar-item" href="/profile/tokens"> Account Settings </a>
                    <a class="navbar-item" href="javascript:{}" onclick="document.getElementById('logout').submit(); return false;"> Log Out </a>

                    <form id="logout" action="{{ route('auth.logout') }}" method="POST">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
