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
                @foreach($packages as $packageItem)
                    <li>
                        <a href="/library/{{ $packageItem->slug }}" class="{{ $packageItem->id == $package->id ? 'is-active' : '' }}">
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
            <p class="menu-label">
                Actions
            </p>
            <ul class="menu-list">
                <li>
                    <a href="/library/new">
                        <i class="fa fa-fw fa-plus"></i>
                        New Package
                    </a>
                </li>
            </ul>
        </aside>
    </section>
@endsection
@section('content')
    <section class="section">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    Release Management
                </p>
            </header>
            <div class="card-content">
                <form action="/library/{{ $package->slug }}/releases" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="field is-grouped">
                        <div class="file control">
                            <label class="file-label">
                                <input class="file-input" type="file" name="archive">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <i class="fa fa-upload"></i>
                                    </span>
                                    <span class="file-label">
                                        Choose a file…
                                    </span>
                                </span>
                                <span class="file-name">
                                    iron-tanks-2.0.0.zip
                                </span>
                            </label>
                        </div>

                        <div class="control has-icons-left is-expanded">
                            <input class="input" type="text" placeholder="Version" name="version">
                            <span class="icon is-small is-left">
                                <i class="fa fa-code-fork"></i>
                            </span>
                        </div>

                        <p class="control">
                            <button type="submit" class="button is-primary">
                                Upload
                            </button>
                        </p>
                    </div>
                </form>
            </div>
            <div class="card-content is-paddingless">
                <release-table :releases='@json($package->releases)'></release-table>
            </div>
        </div>
    </section>
@endsection
