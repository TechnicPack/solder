<div class="box">
    <h1>Builds</h1>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Build</th>
            <th>Minecraft</th>
            <th>Resources</th>
            <th>Promoted</th>
            <th>Latest</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($modpack->builds as $build)
            <tr>
                <td>
                    <a href="/modpacks/{{ $build->modpack->slug }}/{{ $build->version }}">
                        <strong>{{ $build->version }}</strong>
                        @if($build->status == 'private')
                            <span class="tag">private</span>
                        @endif()
                    </a>
                </td>
                <td>{{ $build->minecraft }}</td>
                <td>{{ count($build->releases) }}</td>
                <td></td>
                <td></td>
                <td class="is-narrow">
                    <form method="post" action="/modpacks/{{ $modpack->slug }}/{{ $build->version }}">
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
