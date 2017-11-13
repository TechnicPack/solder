<div class="box">
    <h1>Recently Updated Modpacks</h1>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Version</th>
            <th>Name</th>
            <th>Minecraft</th>
            <th>Updated</th>
        </tr>
        </thead>
        <tbody>
        @foreach($builds as $build)
            <tr>
                <td>
                    <a href="/modpacks/{{ $build->modpack->slug }}/{{ $build->version }}">
                        <strong>{{ $build->version }}</strong>
                    </a>
                </td>
                <td>
                    <a href="{{ route('modpacks.show', $build->modpack) }}">
                        <strong>{{ $build->modpack->name }}</strong>
                    </a>
                </td>
                <td>{{ $build->minecraft }}</td>
                <td class="is-narrow">{{ $build->created }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
