@component('layouts.app')

<section class="hero is-primary">
@include('layouts.nav')

<!-- Hero content: will be in the middle -->
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title">
                Modpacks
            </h1>
            <h2 class="subtitle">
                Edit {{ $modpack->name }}
            </h2>
        </div>
    </div>

    <!-- Hero footer: will stick at the bottom -->
    <div class="hero-foot">
        <nav class="tabs">
            <div class="container">
                <ul>
                    <li><a href="{{ route('modpacks.index') }}">Overview</a></li>
                    <li><a href="{{ route('modpacks.create') }}">Create new</a></li>
                    <li><a href="{{ route('modpacks.edit', $modpack->id) }}">Edit {{ $modpack->name }}</a></li>
                    <li class="is-active"><a>Show {{ $modpack->name }}</a></li>
                </ul>
            </div>
        </nav>
    </div>
</section>

    <section class="section">
        <div class="container">
            @if (session('status'))
                <div class="notification is-info">
                    {{ session('status') }}
                </div>
            @endif

            <div class="content">
                <h2>Description</h2>
                <p>{{ $modpack->description }}</p>

                <h2>Overview</h2>
                <p>{{ $modpack->overview }}</p>

                <h2>Help</h2>
                <p>{{ $modpack->help }}</p>

                <h2>License</h2>
                <p>{{ $modpack->license }}</p>
            </div>
        </div>
    </section>

@endcomponent
