@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="columns">
            <div class="column is-one-quarter">
                <aside class="menu"><p class="menu-label">
                        Profile Settings
                    </p>
                    <ul class="menu-list">
                        <li>
                            <a href="/settings/api" class="is-active">
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
                <passport-personal-access-tokens></passport-personal-access-tokens>
            </div>
        </div>
    </section>
@endsection
