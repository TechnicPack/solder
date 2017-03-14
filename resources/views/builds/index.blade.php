@component('layouts.app')

    @slot('hero')
        <h1 class="title">{{ $modpack->name }}</h1>
        <h2 class="subtitle">{{ $modpack->description }}</h2>
    @endslot

    @slot('links')
        <ul>
            <li class="is-active"><a href="{{ route('builds.index', $modpack->id) }}">Builds</a></li>
            <li><a href="{{ route('modpacks.show', $modpack->id) }}">Overview</a></li>
            <li><a href="{{ route('modpacks.help', $modpack->id) }}">Help</a></li>
            <li><a href="{{ route('modpacks.license', $modpack->id) }}">License</a></li>
            <li><a href="{{ route('modpacks.edit', $modpack->id) }}">Settings</a></li>
        </ul>
    @endslot

    <section class="section">
        <div class="container">
            @if (session('status'))
                <div class="notification is-info">
                    {{ session('status') }}
                </div>
            @endif

            @include('builds.create')

            <solder-builds modpack-id="{{ $modpack->id }}"></solder-builds>
        </div>
    </section>

@endcomponent
