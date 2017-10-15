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
                        <a href="/settings/about" class="is-active">
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
                        <a href="/settings/users">
                                <span class="icon">
                                    <i class="fa fa-fw fa-user-circle"></i>
                                </span>
                            Users
                        </a>
                    </li>
                </ul>
            </aside>
        </div>
        <div class="column">

            {{--TODO: Add some content, this view is here for users who don't have permissions to do anything else in the admin area--}}

        </div>
    </div>
</section>
@endsection
