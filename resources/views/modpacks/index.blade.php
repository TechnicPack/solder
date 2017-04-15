@component('layouts.app')

    @slot('hero')
        <h1 class="title">Modpacks</h1>
        <h2 class="subtitle">See all available modpacks here</h2>
    @endslot

    @slot('links')
        <ul>
            <li class="is-active"><a>Overview</a></li>
            <li><a href="{{ route('modpacks.create') }}">Create new</a></li>
        </ul>
    @endslot

<section class="section">
    <div class="container">
        @if (session('status'))
            <div class="notification is-info">
                {{ session('status') }}
            </div>
        @endif

        <solder-modpacks></solder-modpacks>
    </div>
</section>

@endcomponent
