@component('layouts.app')

    @slot('hero')
        <h1 class="title">{{ $modpack->name }}</h1>
        <h2 class="subtitle">{{ $modpack->description }}</h2>
    @endslot

    @slot('links')
        <ul>
            <li class="is-active"><a href="{{ route('builds.index', $modpack->id) }}">Builds</a></li>
            <li><a href="{{ route('modpacks.show', $modpack->id) }}">Overview</a></li>
            <li><a href="{{ route('modpacks.help', $modpack->id) }}">Help</a></li>
            <li><a href="{{ route('modpacks.license', $modpack->id) }}">License</a></li>
            <li><a href="{{ route('modpacks.edit', $modpack->id) }}">Settings</a></li>
        </ul>
    @endslot

    <nav class="nav has-shadow">
        <div class="container">
            <div class="nav-left">
                <span class="nav-item is-tab"><strong>Build {{ $build->version }}</strong></span>
                <a class="nav-item is-tab is-active" href="#">Resources</a>
            </div>
        </div>
    </nav>

    <section class="section">
        <div class="container">
            @if (session('status'))
                <div class="notification is-info">
                    {{ session('status') }}
                </div>
            @endif

            {{-- this template will be replaced with a Vue component --}}
            @include('builds.add-resource')

            <table class="table">
                <thead>
                <tr>
                    <th>Resource</th>
                    <th>Version</th>
                    <th>Target</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($build->versions as $version)
                    <tr>
                        <th>{{ $version->resource->name }}</th>
                        <td>{{ $version->version }}</td>
                        <td>
                            <div class="block">
                                <span class="tag is-light">
                                    Not Implemented
                                </span>
                            </div>
                        </td>
                        <td class="text-right">
                            <a class="button is-small is-outlined"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endcomponent
