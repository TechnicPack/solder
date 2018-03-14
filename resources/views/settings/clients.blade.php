@extends('layouts.app')

@section('content')

    <section class="section">
        <div class="columns">
            <div class="column is-one-quarter">
                <aside class="menu">
                    <p class="menu-label">
                        Profile Settings
                    </p>
                    <ul class="menu-list">
                        <li>
                            <a href="/settings/api">
                                <span class="icon">
                                    <i class="fa fa-fw fa-user-circle-o"></i>
                                </span>
                                API
                            </a>
                        </li>
                    </ul>
                    <p class="menu-label">
                        Solder Settings
                    </p>
                    <ul class="menu-list">
                        @can('index', App\Key::class)
                            <li>
                                <a href="/settings/keys">
                                    <span class="icon">
                                        <i class="fa fa-fw fa-key"></i>
                                    </span>
                                    Keys
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a href="/settings/clients" class="is-active">
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
                    <h1>Add Launcher Client</h1>
                    <div class="box-body">
                        <form action="/settings/clients" method="post">
                            {{ csrf_field() }}

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Title</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input {{ $errors->has('title') ? 'is-danger' : '' }}" name="title" type="text" placeholder="My Computer" value="{{ old('title') }}">
                                            @if($errors->has('title'))
                                                <p class="help is-danger">{{ $errors->first('title') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Client ID</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input {{ $errors->has('token') ? 'is-danger' : '' }}" name="token" type="text" placeholder="89085904-f97e-4f44-b340-2f42532d5179" value="{{ old('token') }}">
                                            @if($errors->has('token'))
                                                <p class="help is-danger">{{ $errors->first('token') }}</p>
                                            @endif
                                            <p class="help">You can find the Client ID by going to 'Launcher Options' in Technic Launcher.</p>
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
                                        <button class="button is-primary" type="submit">Add Launcher Client</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if(count($clients))
                <div class="box">
                    <h1>Active Launcher Clients</h1>
                    <div class="box-body">
                        <ul class="list-group">

                            @foreach($clients as $client)
                                <li class="level list-group-item">
                                    <div class="level-left">
                                        <div class="level-item" style="width:100px;">
                                            <span class="icon is-large">
                                                <i class="fa fa-2x fa-window-maximize"></i>
                                            </span>
                                        </div>
                                        <div class="level-item">
                                            <div class="content">
                                                <p>
                                                    <strong>{{ $client->title }}</strong><br>
                                                    <strong>Client ID:</strong> <code>{{ $client->token }}</code><br>
                                                    <small>Added on {{ $client->created_at }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="level-right">
                                        <div class="level-item">
                                            <form method="post" action="/settings/clients/{{ $client->id }}">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button class="button is-danger is-outlined">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>
@endsection
