@extends('layouts.app')

@section('content')

    <section class="section">
        <div class="columns">
            <div class="column is-one-quarter">
                <aside class="menu">
                    <p class="menu-label">
                        Profile
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
                        <li>
                            <a href="/settings/keys">
                                <span class="icon">
                                    <i class="fa fa-fw fa-key"></i>
                                </span>
                                Keys
                            </a>
                        </li>
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

            <platform-clients inline-template>
                <div class="column">

                    @include('settings.clients.create-client')

                    @include('settings.clients.tokens')

                </div>
            </platform-clients>
        </div>
    </section>
@endsection
