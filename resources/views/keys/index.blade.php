@extends('layouts.app')

@section('menu')
    <nav class="navbar is-primary border-bottom-dark" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <span class="navbar-item">
                Settings
            </span>
        </div>
    </nav>

    <section class="section is-small">
        <aside class="menu">
            <p class="menu-label">
                Users & Authentication
            </p>
            <ul class="menu-list">
                <li>
                    <a href="/settings/keys" class="is-active">
                        API Keys
                    </a>
                </li>
            </ul>
        </aside>
    </section>
@endsection

@section('content')

    <section class="section">
        <h1 class="title is-4">
            Legacy Keys
            <a href="#add-legacy-key" class="button is-small is-primary is-pulled-right">
                Add Legacy Key
            </a>
        </h1>
        <hr>
        <p class="subtitle is-6">This is a list of legacy API keys with access to your server. Remove any keys that you do not recognize or are no longer using.</p>

        <ul class="list-group">

            @forelse($keys as $key)
            <li class="level list-group-item">
                <div class="level-left">
                    <div class="level-item" style="width:100px;">
                        <span class="icon is-large">
                            <i class="fa fa-key fa-2x"></i>
                        </span>
                    </div>
                    <div class="level-item">
                        <div class="content">
                            <p>
                                <strong>{{ $key->name }}</strong><br>
                                <strong>Token:</strong> {{ $key->token }}<br>
                                Added on {{ $key->created_at }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="level-right">
                    <div class="level-item">
                        <form method="post" action="/settings/keys/{{ $key->id }}">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="button is-danger is-outlined">Delete</button>
                        </form>
                    </div>
                </div>
            </li>
        @empty
                <li>
                    <p>There are no legacy API keys with access to your account.</p>
                </li>
        @endforelse
        </ul>

        <div id="add-legacy-key" class="box">
            <form action="/settings/keys" method="post">
                {{ csrf_field() }}
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" name="name" type="text" placeholder="Key Name">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Token</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" name="token" type="text" placeholder="API Key">
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
                            <button class="input" type="submit">Add Legacy Key</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>

@endsection
