@extends('layouts.app')

@section('content')

    <section class="section">
        <div class="columns">
            <div class="column is-9 is-offset-2 is-6-fullhd is-offset-3-fullhd">

                @assistant
                <div class="notification is-primary">
                    <figure class="image is-64x64 is-pulled-left" style="margin-right: 1rem;">
                        <img src="/img/steve.png" />
                    </figure>
                    <p class="is-size-4">Hi, welcome to Solder.</p>
                    <p>I'm Steve and this is your Dashboard. From here you can create modpacks and check on recent activity. If you're just getting started, use the form below to create your first modpack.</p>
                </div>
                @endassistant

                <create-modpack-form></create-modpack-form>

                @if(count($builds))
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
                                    <a href="/modpacks/{{ $build->modpack->slug }}">
                                        <strong>{{ $build->modpack->name }}</strong>
                                    </a>
                                </td>
                                <td>{{ $build->minecraft }}</td>
                                <td>{{ $build->created }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if(count($releases))
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
                                <td>{{ $release->created }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif


            </div>
        </div>
    </section>

@endsection
