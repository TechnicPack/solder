@extends('layouts.app')

@section('content')

    <section class="section">
        <div class="columns">
            <div class="column is-9 is-offset-2">
                <div class="box">
                    <h1>Create Modpack</h1>
                    <div class="box-body">
                        <form action="/modpacks" method="post">
                            {{ csrf_field() }}

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Name</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="name" type="text" placeholder="Attack of the B-Team">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Slug</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input" name="slug" type="text" placeholder="attack-of-the-bteam">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Status</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <div class="select is-fullwidth">
                                                <select name="status">
                                                    <option value="public" selected>Public</option>
                                                    <option value="private">Private</option>
                                                    <option value="draft">Draft</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label">
                                    &nbsp;
                                </div>
                                <div class="field-body">
                                    <div class="control">
                                        <button class="button is-primary" type="submit">Add Modpack</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

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


            </div>
        </div>
    </section>

@endsection
