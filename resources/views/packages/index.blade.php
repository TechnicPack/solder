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

                <div class="level">
                    <div class="level-left"></div>
                    <div class="level-right"></div>
                </div>

                <div class="box">
                    <h1>Add Package</h1>
                    <div class="box-body">
                        <form action="/library/" method="post">
                            {{ csrf_field() }}

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Name</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control is-expanded">
                                            <input class="input" type="text" name="name" />
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Slug</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control is-expanded">
                                            <input class="input" type="text" name="slug" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label">
                                    &nbsp;
                                </div>
                                <div class="field-body">
                                    <div class="control">
                                        <button class="button is-primary" type="submit">Add Package</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

    </section>
@endsection
