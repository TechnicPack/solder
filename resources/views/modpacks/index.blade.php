@component('layouts.app')

{{--<section class="hero is-primary">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">Modpacks</h1>
            <h2 class="subtitle"><a href="{{ route('modpacks.create') }}">Add Modpack</a></h2>
        </div>
    </div>
</section>--}}
<section class="hero is-primary">
    <!-- Hero header: will stick at the top -->
@include('layouts.nav')

    <!-- Hero content: will be in the middle -->
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title">
                Modpacks
            </h1>
            <h2 class="subtitle">
                See all available modpacks here
            </h2>
        </div>
    </div>

    <!-- Hero footer: will stick at the bottom -->
    <div class="hero-foot">
        <nav class="tabs">
            <div class="container">
                <ul>
                    <li class="is-active"><a>Overview</a></li>
                    <li><a>Create new</a></li>
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

        <div class="columns is-mobile is-multiline">

            @foreach($modpacks as $modpack)
                <div class="column is-3">
                    <div class="card">
                        <a href="{{ route('modpacks.show', $modpack->id) }}">
                            <div class="card-image">
                                <figure class="image is-3by2">
                                    <img src="{{ $modpack->background }}" alt="Image">
                                </figure>
                                <div class="white-to-black is-overlay"></div>
                                <img class="has-one-third-padding is-overlay" src="{{ $modpack->logo }}" alt="Image">
                            </div>
                        </a>
                        <div class="card-content">

                            <div class="content">
                                {{ $modpack->name }}
                                <br>
                                <small>{{ $modpack->privacy }}</small>
                            </div>

                        </div>
                        <footer class="card-footer">
                            <a href="{{ route('modpacks.edit', $modpack->id) }}" class="card-footer-item">Edit</a>
                            <a class="card-footer-item"
                               onclick="event.preventDefault();document.getElementById('modpack-{{ $modpack->slug }}').submit();">
                                Delete
                            </a>

                            <form id="modpack-{{ $modpack->slug }}"
                                  action="{{ route('modpacks.destroy', $modpack->id) }}" method="POST"
                                  style="display: none;">
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                            </form>
                        </footer>
                    </div>
                </div>
            @endforeach
                @foreach($modpacks as $modpack)
                    <div class="column is-3">
                        <div class="card">
                            <a href="{{ route('modpacks.show', $modpack->id) }}">
                                <div class="card-image">
                                    <figure class="image is-3by2">
                                        <img src="{{ $modpack->background }}" alt="Image">
                                    </figure>
                                    <div class="white-to-black is-overlay"></div>
                                    <img class="has-one-third-padding is-overlay" src="{{ $modpack->logo }}" alt="Image">
                                </div>
                            </a>
                            <div class="card-content">

                                <div class="content">
                                    {{ $modpack->name }}
                                    <br>
                                    <small>{{ $modpack->privacy }}</small>
                                </div>

                            </div>
                            <footer class="card-footer">
                                <a href="{{ route('modpacks.edit', $modpack->id) }}" class="card-footer-item">Edit</a>
                                <a class="card-footer-item"
                                   onclick="event.preventDefault();document.getElementById('modpack-{{ $modpack->slug }}').submit();">
                                    Delete
                                </a>

                                <form id="modpack-{{ $modpack->slug }}"
                                      action="{{ route('modpacks.destroy', $modpack->id) }}" method="POST"
                                      style="display: none;">
                                    {{ method_field('delete') }}
                                    {{ csrf_field() }}
                                </form>
                            </footer>
                        </div>
                    </div>
                @endforeach
                @foreach($modpacks as $modpack)
                    <div class="column is-3">
                        <div class="card">
                            <a href="{{ route('modpacks.show', $modpack->id) }}">
                                <div class="card-image">
                                    <figure class="image is-3by2">
                                        <img src="{{ $modpack->background }}" alt="Image">
                                    </figure>
                                    <div class="white-to-black is-overlay"></div>
                                    <img class="has-one-third-padding is-overlay" src="{{ $modpack->logo }}" alt="Image">
                                </div>
                            </a>
                            <div class="card-content">

                                <div class="content">
                                    {{ $modpack->name }}
                                    <br>
                                    <small>{{ $modpack->privacy }}</small>
                                </div>

                            </div>
                            <footer class="card-footer">
                                <a href="{{ route('modpacks.edit', $modpack->id) }}" class="card-footer-item">Edit</a>
                                <a class="card-footer-item"
                                   onclick="event.preventDefault();document.getElementById('modpack-{{ $modpack->slug }}').submit();">
                                    Delete
                                </a>

                                <form id="modpack-{{ $modpack->slug }}"
                                      action="{{ route('modpacks.destroy', $modpack->id) }}" method="POST"
                                      style="display: none;">
                                    {{ method_field('delete') }}
                                    {{ csrf_field() }}
                                </form>
                            </footer>
                        </div>
                    </div>
                @endforeach
                @foreach($modpacks as $modpack)
                    <div class="column is-3">
                        <div class="card">
                            <a href="{{ route('modpacks.show', $modpack->id) }}">
                                <div class="card-image">
                                    <figure class="image is-3by2">
                                        <img src="{{ $modpack->background }}" alt="Image">
                                    </figure>
                                    <div class="white-to-black is-overlay"></div>
                                    <img class="has-one-third-padding is-overlay" src="{{ $modpack->logo }}" alt="Image">
                                </div>
                            </a>
                            <div class="card-content">

                                <div class="content">
                                    {{ $modpack->name }}
                                    <br>
                                    <small>{{ $modpack->privacy }}</small>
                                </div>

                            </div>
                            <footer class="card-footer">
                                <a href="{{ route('modpacks.edit', $modpack->id) }}" class="card-footer-item">Edit</a>
                                <a class="card-footer-item"
                                   onclick="event.preventDefault();document.getElementById('modpack-{{ $modpack->slug }}').submit();">
                                    Delete
                                </a>

                                <form id="modpack-{{ $modpack->slug }}"
                                      action="{{ route('modpacks.destroy', $modpack->id) }}" method="POST"
                                      style="display: none;">
                                    {{ method_field('delete') }}
                                    {{ csrf_field() }}
                                </form>
                            </footer>
                        </div>
                    </div>
                @endforeach

        </div>
    </div>
</section>

@endcomponent
