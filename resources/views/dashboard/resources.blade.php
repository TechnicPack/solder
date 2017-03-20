<section class="panel">
    <p class="panel-heading">
      Recently Added Resource Versions
    </p>
    <div class="panel-block">
        <table class="table">
            <tr>
                <th>Resource</th>
                <th>Version</th>
                <th>Minecraft</th>
                <th>Created</th>
            </tr>
            @foreach($recentVersions as $version)
            <tr>
                <td><a href="{{ route('resources.edit', $version->resource->id) }}">{{ $version->resource->name }}</a></td>
                <td><a href="{{ route('versions.show', ['resource' => $version->resource->id, 'version' => $version->id]) }}">{{ $version->version }}</a></td>
                <td>{{-- TODO: Application doesn't actually implement this yet --}}</td>
                <td>{{ $version->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</section>
