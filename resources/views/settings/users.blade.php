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
                            <a href="/settings/keys">
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
                            <a href="/settings/users" class="is-active">
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
                    <h1>Add User</h1>
                    <div class="box-body">
                        <form action="/settings/users" method="post">
                            {{ csrf_field() }}

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Username</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input {{ $errors->has('username') ? 'is-danger' : '' }}" name="username" type="text" placeholder="John" value="{{ old('username') }}">
                                            @if($errors->has('username'))
                                                <p class="help is-danger">{{ $errors->first('username') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Email</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input {{ $errors->has('email') ? 'is-danger' : '' }}" name="email" type="text" placeholder="john@example.com" value="{{ old('email') }}">
                                            @if($errors->has('email'))
                                                <p class="help is-danger">{{ $errors->first('email') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Password</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input is-fullwidth {{ $errors->has('password') ? 'is-danger' : '' }}" name="password" type="password" value="{{ old('password') }}">
                                            @if($errors->has('password'))
                                                <p class="help is-danger">{{ $errors->first('password') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    &nbsp;
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <label class="checkbox">
                                                <input name="is_admin" type="checkbox" />
                                                User is a site administrator
                                            </label>
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
                                        <button class="button is-primary" type="submit">Add User</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box">
                    <h1>Active Users</h1>
                    <div class="box-body">
                        <ul class="list-group">
                            @foreach($users as $user)
                                <li class="level list-group-item">
                                    <div class="level-left">
                                        <div class="level-item" style="width:100px;">
                                            <span class="icon is-large">
                                                <i class="fa fa-user-circle fa-2x"></i>
                                            </span>
                                        </div>
                                        <div class="level-item">
                                            <div class="content">
                                                <p>
                                                    <strong>{{ $user->username }}</strong>
                                                    @if( $user->is_admin )
                                                        <span class="tag is-light">site admin</span>
                                                    @endif
                                                    <br>
                                                    <strong>Email:</strong> {{ $user->email }}<br>
                                                    <small>Added on {{ $user->created_at }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="level-right">
                                        <div class="level-item">
                                            <div class="field is-grouped">
                                                <p class="control">
                                                    <button onclick='document.getElementById("user-{{ $user->id }}").classList.add("is-active");' class="button is-outlined">Edit</button>
                                                </p>
                                                <form method="post" action="/settings/users/{{ $user->id }}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <button class="button is-danger is-outlined" {{ Auth()->user()->is($user) ? 'disabled' : '' }}>Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @foreach($users as $user)
        <div class="modal" id="user-{{ $user->id }}">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <h1>Update User</h1>
                    <div class="box-body">
                        <form action="/settings/users/{{ $user->id }}" method="post">
                            {{ csrf_field() }}

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Username</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="username" type="text" value="{{ $user->username }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Email</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="email" type="text" value="{{ $user->email }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Password</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="password" type="password">
                                                <p class="help">Leave blank to keep current password.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    &nbsp;
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <label class="checkbox">
                                                <input name="is_admin" type="checkbox" {{ $user->is_admin ? 'checked' : '' }}/>
                                                User is a site administrator
                                            </label>
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
                                        <button class="button is-primary" type="submit">Update User</button>
                                        <button class="button" onclick='document.getElementById("user-{{ $user->id }}").classList.remove("is-active");'>Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
