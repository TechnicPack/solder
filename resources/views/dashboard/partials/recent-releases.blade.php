<div class="box">
    <h1>Recently Uploaded Package Versions</h1>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Version</th>
            <th>Name</th>
            <th>Updated</th>
        </tr>
        </thead>
        <tbody>
        @foreach($releases as $release)
            <tr>
                <td>{{ $release->version }}</td>
                <td>
                    <a href="/library/{{ $release->package->slug }}">
                        <strong>{{ $release->package->name }}</strong>
                    </a>
                </td>
                <td class="is-narrow">{{ $release->created }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
