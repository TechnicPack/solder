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
        </aside>
    </section>
@endsection



@section('content')

    <section class="section">
                <div class="level">
                    <div class="level-left"></div>
                    <div class="level-right">
                        <div class="level-item">{{ $package->name }}</div>
                    </div>
                </div>

                <div class="box">
                    <h1>Add Release</h1>
                    <div class="box-body">
                        <form action="/library/{{ $package->slug }}/releases" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Version</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control has-icons-left is-expanded">
                                            <input class="input" type="text" placeholder="1.2.3" name="version">
                                            <span class="icon is-small is-left">
                                                <i class="fa fa-code-fork"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">File</label>
                                </div>
                                <div class="field-body">
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
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label">
                                    &nbsp;
                                </div>
                                <div class="field-body">
                                    <div class="control">
                                        <button class="button is-primary" type="submit">Add Release</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box">
                    <h1>Releases</h1>

                    <release-table :releases='@json($package->releases)'></release-table>
                </div>

    </section>
@endsection
