@extends('layouts.app')

@section('menu')
    <nav class="navbar is-primary border-bottom-dark" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <span class="navbar-item">
                Package Library
            </span>
        </div>
    </nav>

    <section class="section is-small">
        <aside class="menu">
            <p class="menu-label">
                Mods
            </p>
            <ul class="menu-list">
                @foreach($packages as $package)
                    <li>
                        <a href="/library/{{ $package->slug }}">
                            <article class="media">
                                <figure class="media-left">
                                    <p class="image is-32x32">
                                        <img src="/img/chest.png" />
                                    </p>
                                </figure>
                                <div class="media-content">
                                    <div class="content">
                                        <p>
                                            <strong>{{ $package->name }}</strong><br>
                                            <small># releases</small>
                                        </p>
                                    </div>
                                </div>
                            </article>
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>
    </section>
@endsection

@section('content')
    <section class="section">
        <nav class="level">
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Select a Package</p>
                </div>
            </div>
        </nav>
    </section>
@endsection
