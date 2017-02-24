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
                <td><a href="#">{{ $version->resource->name }}</a></td>
                <td><a href="#">{{ $version->version }}</a></td>
                <td>{{-- TODO: Application doesn't actually implement this yet --}}</td>
                <td>{{ $version->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</section>
