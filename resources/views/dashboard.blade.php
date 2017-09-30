@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="columns">
            <div class="column is-9 is-offset-2 is-6-fullhd is-offset-3-fullhd">

                @assistant
                <div class="notification is-info">
                    <figure class="image is-64x64 is-pulled-left" style="margin-right: 1rem;">
                        <img src="/img/steve.png" />
                    </figure>
                    <p class="is-size-4">Hi, welcome to Solder.</p>
                    <p>I'm Steve and this is your Dashboard. From here you can create modpacks and check on recent activity. If you're just getting started, use the form below to create your first modpack.</p>
                </div>
                @endassistant

                <div class="box">
                    <h1>Create Modpack</h1>
                    <div class="box-body">
                        <form action="/modpacks" method="post" id="create-modpack" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Name</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" onKeyUp="nameUpdated()" id="modpack-name" name="name" placeholder="Attack of the B-Team" value="{{ old('name') }}" />
                                            @if($errors->has('name'))
                                                <p class="help is-danger">{{ $errors->first('name') }}</p>
                                            @endif
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
                                            <input class="input {{ $errors->has('slug') ? 'is-danger' : '' }}" onKeyUp="slugUpdated()" id="modpack-slug" name="slug" placeholder="attack-of-the-bteam" value="{{ old('slug') }}" />
                                            @if($errors->has('slug'))
                                                <p class="help is-danger">{{ $errors->first('slug') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label">Icon</label>
                                </div>
                                <div class="field-body">
                                    <div class="field">
                                        <div class="control">
                                            <div class="columns">
                                                <div class="column is-narrow">
                                                    <div class="file {{ $errors->has('modpack_icon') ? 'is-danger' : '' }}">
                                                        <label class="file-label">
                                                            <input class="file-input" type="file" name="modpack_icon">
                                                            <span class="file-cta">
                                                                <span class="file-icon">
                                                                    <i class="fa fa-upload"></i>
                                                                </span>
                                                                <span class="file-label">
                                                                    Choose a file…
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="column is-flex" style="align-items: center;">
                                                    @if($errors->has('modpack_icon'))
                                                        <p class="is-size-7 has-text-danger">{{ $errors->first('modpack_icon') }}</p>
                                                    @else
                                                        <p class="is-size-7">Icon should be square and at least 50px wide.</p>
                                                    @endif
                                                </div>
                                            </div>
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
                                <td class="is-narrow">{{ $build->created }}</td>
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
                                <td class="is-narrow">{{ $release->created }}</td>
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

@push('afterScripts')
    <script>
        var calculatedSlug = true;

        function nameUpdated() {
            if(calculatedSlug) {
                document.getElementById("modpack-slug").value = slugify(document.getElementById("modpack-name").value);
            }
        }

        function slugUpdated() {
            calculatedSlug = false;
        }

        function slugify(text) {
            // https://gist.github.com/mathewbyrne/1280286
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '')             // Trim - from end of text
                .replace(/[\s_-]+/g, '-');
        }
    </script>
@endpush
