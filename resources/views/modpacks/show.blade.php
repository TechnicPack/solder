@component('layouts.app')

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    {{ $modpack->name }}
                </h1>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if (session('status'))
                <div class="notification is-info">
                    {{ session('status') }}
                </div>
            @endif

            <div class="tabs is-right">
                <ul>
                    <li class="is-active"><a href="{{ route('modpacks.show', $modpack->id) }}">Show</a></li>
                    <li><a href="{{ route('modpacks.edit', $modpack->id) }}">Edit</a></li>
                </ul>
            </div>

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
