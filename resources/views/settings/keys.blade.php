@extends('layouts.app')

@section('content')

    <section class="section">
        <div class="columns">
            <div class="column is-one-quarter">
                <aside class="menu">
                    <p class="menu-label">
                        Settings
                    </p>
                    <ul class="menu-list">
                        <li>
                            <a href="/settings/about">
                                <span class="icon">
                                    <i class="fa fa-fw fa-tachometer"></i>
                                </span>
                                About
                            </a>
                        </li>
                        @can('index', App\Key::class)
                        <li>
                            <a href="/settings/keys" class="is-active">
                                <span class="icon">
                                    <i class="fa fa-fw fa-key"></i>
                                </span>
                                Keys
                            </a>
                        </li>
                        @endcan
                        <li>
                            <a href="/settings/clients">
                                <span class="icon">
                                    <i class="fa fa-fw fa-window-maximize"></i>
                                </span>
                                Clients
                            </a>
                        </li>
                        <li>
                            <a href="/settings/users">
                                <span class="icon">
                                    <i class="fa fa-fw fa-user-circle"></i>
                                </span>
                                Users
                            </a>
                        </li>
                        <li>
                            <a href="/settings/permissions">
                            <span class="icon">
                                <i class="fa fa-fw fa-universal-access"></i>
                            </span>
                                Permissions
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>

            <div class="column">
                <div class="box">
                    <h1>Add Key</h1>
                    <div class="box-body">
                        <form action="/settings/keys" method="post">
                            {{ csrf_field() }}
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    &nbsp;
                                </div>
                                <div class="field-body">
                                    <div class="notification">
                                        Keys provide the bearer with access to <strong>all private modpacks and builds</strong> but they only operate on the legacy api endpoints. Grant this sparingly.
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Name</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="name" type="text" placeholder="Technicpack.net">
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
                                            <input class="input" name="token" type="text">
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
                                        <button class="button is-primary" type="submit">Add Key</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if(count($keys))
                <div class="box">
                    <h1>Active Keys</h1>
                    <div class="box-body">
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
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>
@endsection
