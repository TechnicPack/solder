<nav class="navbar is-primary border-bottom-dark" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
            <span class="navbar-item">
                {{ $modpack->name }}
            </span>
    </div>
</nav>
<section class="section is-small">
    <aside class="menu is-dark">
        <p class="menu-label">
            Builds
        </p>
        <ul class="menu-list">
            @foreach($modpack->builds as $build)
                <li>
                    <a
                        class="{{ isset($activeBuild) && $activeBuild->is($build) ? 'is-active' : '' }}"
                        href="/modpacks/{{ $modpack->slug }}/{{ $build->version }}"
                    >
                        <i class="fa fa-fw {{ $modpack->promoted_build_id == $build->id ? 'fa-star' : '' }}"></i>
                        {{ $build->version }}
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>
</section>
