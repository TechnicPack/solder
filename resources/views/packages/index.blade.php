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
        <aside class="menu is-dark">
            <p class="menu-label">
                Mods
            </p>
            <ul class="menu-list">
                @foreach($packages as $packageItem)
                    <li>
                        <a href="/library/{{ $packageItem->slug }}">
                            <article class="media">
                                <figure class="media-left">
                                    <p class="image is-32x32">
                                        {{-- TODO: Add image support to packages for this placeholder --}}
                                        <img src="/img/chest.png" />
                                    </p>
                                </figure>
                                <div class="media-content">
                                    <div class="content">
                                        <p>
                                            <strong>{{ $packageItem->name }}</strong><br>
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

        @assistant
            <div class="notification is-primary">
                <figure class="image is-64x64 is-pulled-left" style="margin-right: 1rem;">
                    <img src="/img/steve.png" />
                </figure>
                <p class="is-size-4">This is your Library</p>
                <p>Here is where you store all the mods, resource packs, configs or whatever else you might want to bundle into a modpack. You'll need to create a package to keep multiple versions
                    of the same mod or resource pack together before you can start uploading files.</p>
            </div>
        @endassistant

        <div class="level">
            <div class="level-left"></div>
            <div class="level-right"></div>
        </div>

        <create-package-form></create-package-form>
    </section>
@endsection
