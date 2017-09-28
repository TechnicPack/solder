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
                New Package
            </p>
        </header>
        <div class="card-content">

            <form method="post" action="/library">
                {{ csrf_field() }}
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p class="control is-expanded">
                                <input class="input" type="text" name="name" placeholder="Name">
                            </p>
                        </div>
                        <div class="field">
                            <p class="control is-expanded">
                                <input class="input" type="text" name="slug" placeholder="Slug">
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label">
                        <!-- Left empty for spacing -->
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <button class="button is-primary">
                                    Create Package
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</section>
@endsection
