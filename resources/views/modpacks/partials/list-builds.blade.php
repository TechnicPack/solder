<div class="box">
    <h1>Builds</h1>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Build</th>
            <th>Minecraft</th>
            <th>Forge</th>
            <th>Java</th>
            <th>Memory</th>
            <th class="has-text-centered">Promoted</th>
            <th class="has-text-centered">Latest</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($modpack->builds as $build)
            <tr>
                <td>
                    <a href="{{ route('builds.show', [$modpack, $build]) }}">
                        <strong>{{ $build->version }}</strong>
                        @if($build->status == 'private')
                            <span class="tag">private</span>
                        @endif()
                        @if($build->status == 'draft')
                            <span class="tag">draft</span>
                        @endif()
                    </a>
                </td>
                <td>{{ $build->minecraft_version }}</td>
                <td>{{ $build->forge_version }}</td>
                <td>{{ $build->java_version }}</td>
                <td>{{ $build->required_memory }}</td>
                <td class="has-text-centered is-narrow">
                    <form method="post" action="{{ route('modpacks.update', $build->modpack) }}">
                        {{ csrf_field() }}
                        {{ method_field('patch') }}
                        <input type="hidden" name="recommended_build_id" value="{{ $build->id }}" />

                        @if($build->id == $modpack->recommended_build_id)
                            <button class="button is-small is-success" type="submit">
                                <i class="fa fa-fw fa-heart"></i>
                            </button>
                        @else
                            <button class="button is-small" type="submit">
                                <i class="fa fa-fw fa-heart-o"></i>
                            </button>
                        @endif
                    </form>
                </td>
                <td class="has-text-centered is-narrow">
                    <form method="post" action="{{ route('modpacks.update', $build->modpack) }}">
                        {{ csrf_field() }}
                        {{ method_field('patch') }}
                        <input type="hidden" name="latest_build_id" value="{{ $build->id }}" />

                        @if($build->id == $modpack->latest_build_id)
                            <button class="button is-small is-success" type="submit">
                                <i class="fa fa-fw fa-star"></i>
                            </button>
                        @else
                            <button class="button is-small" type="submit">
                                <i class="fa fa-fw fa-star-o"></i>
                            </button>
                        @endif
                    </form>
                </td>
                <td class="has-text-right">
                    <form method="post" action="{{ route('builds.destroy', [$modpack, $build]) }}">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="button is-danger is-small is-outlined">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
