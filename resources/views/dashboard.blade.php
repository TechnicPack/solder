@extends('layouts.app')

@section('content')
    <section class="section">
        <h1 class="title">Solder</h1>
        <h2 class="subtitle">Welcome to Solder!</h2>
        <div class="columns">
            <div class="column">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Recently Updated Modpacks
                        </p>
                    </header>
                    <div class="card-content is-paddingless">
                <table class="table is-striped is-fullwidth">
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
                        <td>{{ $build->version }}</td>
                        <td>{{ $build->modpack->name }}</td>
                        <td>{{ $build->minecraft }}</td>
                        <td>{{ $build->created }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            Recently Updated Packages
                        </p>
                    </header>
                    <div class="card-content is-paddingless">
                <table class="table is-striped is-fullwidth">
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
                        <td>{{ $release->package->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($release->created_on)->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
