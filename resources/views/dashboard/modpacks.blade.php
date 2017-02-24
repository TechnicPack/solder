<section class="panel">
    <p class="panel-heading">
      Recently Updated Modpack Builds
    </p>
    <div class="panel-block">
        <table class="table">
            <tr>
                <th>Build</th>
                <th>Modpack</th>
                <th>Minecraft</th>
                <th>Updated</th>
            </tr>
            @foreach($recentBuilds as $build)
            <tr>
                <td><a href="#">{{ $build->version }}</a></td>
                <td><a href="#">{{ $build->modpack->name }}</a></td>
                <td>{{ $build->game_version }}</td>
                <td>{{ $build->updated_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</section>
