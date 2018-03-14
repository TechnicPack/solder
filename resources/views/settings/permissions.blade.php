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
                            <a href="/settings/permissions" class="is-active">
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
                    <form action="/settings/permissions" method="post">
                        {{ csrf_field() }}

                        <h1>
                            User Permissions
                            <div class="field-body pull-right">
                                <div class="control">
                                    <button class="button is-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </h1>

                        <table class="table is-fullwidth">
                            <thead>
                            <tr style="height:12.5em">
                                <th>&nbsp;</th>
                                @foreach($roles as $role)
                                    <th class="is-narrow rotate">
                                        <div>{{ $role->tag }}</div>
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        {{ $user->username }}
                                        @if($user->is_admin)
                                            <span class="tag is-light">site admin</span>
                                        @endif
                                    </td>
                                    @foreach($roles as $role)
                                        <td class="has-text-centered">
                                            <label class="checkbox">
                                                <input name="users[{{ $user->id }}][]" value="{{ $role->id }}" type="checkbox" {{ $user->roles->contains($role) || $user->is_admin ? 'checked' : '' }}  {{ $user->is_admin ? 'disabled' : '' }}>
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
