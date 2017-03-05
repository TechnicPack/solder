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

    <section class="section">
        <div class="container">
            @if (session('status'))
                <div class="notification is-info">
                    {{ session('status') }}
                </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>Build</th>
                        <th>Tag</th>
                        <th>For Minecraft</th>
                        <th>Resources</th>
                        <th>Created</th>
                        <th>Privacy</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($builds as $build)
                    <tr>
                        <th>
                            <a href="{{ route('builds.show', ['modpack' => $modpack->id, 'build' => $build->id]) }}">{{ $build->version }}</a>
                        </th>
                        <td>
                            @if( $build->is_promoted )
                                <span class="tag is-success">
                                    Promoted
                                </span>
                            @endif
                        </td>
                        <td>{{ $build->game_version }}</td>
                        <td>{{ count($build->versions) }}</td>
                        <td>{{ $build->created_at->diffForHumans() }}</td>
                        <td><i class="fa fa-fw fa-{{ $build->privacy }}"></i> {{ $build->privacy }}</td>
                        <td class="text-right">
                            <a href="{{ route('builds.edit', ['modpack' => $modpack->id, 'build' => $build->id]) }}" class="button is-outlined is-small">Settings</a>
                            <a href="" class="button is-outlined is-small">Promote</a>
                            <a class="button is-outlined is-small" onclick="event.preventDefault();document.getElementById('build-{{ $build->id }}').submit();">Delete</a>

                            <form id="build-{{ $build->id }}"
                                  action="{{ route('builds.destroy', ['modpack' => $modpack->id, 'build' => $build->id]) }}" method="POST"
                                  style="display: none;">
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endcomponent
