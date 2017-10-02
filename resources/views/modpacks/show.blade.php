@extends('layouts.app')

@section('menu')
    @include('partials.modpack-menu', ['modpack' => $modpack])
@endsection

@section('content')
    <section class="section">

        @assistant
        <div class="notification is-info">
            <figure class="image is-64x64 is-pulled-left" style="margin-right: 1rem;">
                <img src="/img/steve.png" />
            </figure>
            <p class="is-size-4">Modpack Build Management</p>
            <p>
                Modpacks are made up of multiple versions, called 'builds'. Builds
                help you organize changes and upgrades to your modpack without
                breaking players worlds. A build is what the launcher will download
                and run, so it needs to have a unique version number and the version
                of Minecraft you want launched.
            </p>
        </div>
        @endassistant

        <div class="level has-text-capitalized is-size-6">
            <div class="level-left"></div>
            <div class="level-right">
                <div class="level-item has-padding-right-3">
                    <small>{{ $modpack->slug }}</small>
                </div>
                <div class="level-item">
                    <small>{{ $modpack->status }}</small>&nbsp;
                    <span class="icon has-text-{{ $modpack->status }}">
                      <i class="fa fa-circle"></i>
                    </span>
                </div>

            </div>
        </div>

        <div class="box">
            <h1>Add Build</h1>
            <div class="box-body">
                <form method="post" action="/modpacks/{{ $modpack->slug }}/builds">
                    {{ csrf_field() }}

                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Version</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input {{ $errors->has('version') ? 'is-danger' : '' }}" name="version" type="text" placeholder="1.0.0">
                                    @if($errors->has('version'))
                                        <p class="help is-danger">{{ $errors->first('version') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Minecraft</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input {{ $errors->has('minecraft') ? 'is-danger' : '' }}" name="minecraft" type="text" placeholder="1.7.10">
                                    @if($errors->has('minecraft'))
                                        <p class="help is-danger">{{ $errors->first('minecraft') }}</p>
                                    @endif
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
                                <button class="button is-primary" type="submit">Add Build</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="box">
            <h1>Modpack Settings</h1>
            <div class="box-body">
                <form method="post" action="/modpacks/{{ $modpack->slug }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('patch') }}

                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Name</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" onKeyUp="nameUpdated()" id="modpack-name" name="name" placeholder="Attack of the B-Team" value="{{ old('name', $modpack->name) }}" />
                                    @if($errors->has('name'))
                                        <p class="help is-danger">{{ $errors->first('name') }}</p>
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
                        <div class="field-label">
                            &nbsp;
                        </div>
                        <div class="field-body">
                            <div class="control">
                                <button class="button" type="submit">Update</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="box is-danger">
            <h1>Danger Zone</h1>
            <div class="box-body">
                <ul class="list-group">
                    @if($modpack->status == 'private')
                    <li class="level list-group-item">
                        <div class="level-left">
                            <div class="level-item">
                                <div class="content">
                                    <strong>Make this modpack public</strong><br />
                                    Make this modpack visible to anyone.
                                </div>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <form method="post" action="/modpacks/{{ $modpack->slug }}">
                                    {{ csrf_field() }}
                                    {{ method_field('patch') }}

                                    <input type="hidden" name="status" value="public" />
                                    <button class="button is-danger is-outlined" type="submit">Make public</button>
                                </form>
                            </div>
                        </div>
                    </li>
                    @endif
                    @if($modpack->status == 'public')
                    <li class="level list-group-item">
                        <div class="level-left">
                            <div class="level-item">
                                <div class="content">
                                    <strong>Make this modpack private</strong><br />
                                    Hide this modpack from the public.
                                </div>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <form method="post" action="/modpacks/{{ $modpack->slug }}">
                                    {{ csrf_field() }}
                                    {{ method_field('patch') }}

                                    <input type="hidden" name="status" value="private" />
                                    <button class="button is-danger is-outlined" type="submit">Make private</button>
                                </form>
                            </div>
                        </div>
                    </li>
                    @endif
                    <li class="level list-group-item">
                        <div class="level-left">
                            <div class="level-item">
                                <div class="content">
                                    <strong>Change modpack slug</strong><br />
                                    When you change the modpack slug, the modpack will no longer be accessible by the current slug.
                                </div>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <form method="post" action="/modpacks/{{ $modpack->slug }}">
                                    {{ csrf_field() }}
                                    {{ method_field('patch') }}

                                    <div class="field has-addons">
                                        <div class="control">
                                            <input class="input is-danger" name="slug" type="text" value="{{ $modpack->slug }}">
                                        </div>
                                        <div class="control">
                                            <button type="submit" class="button is-danger is-outlined">
                                                Change
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li class="level list-group-item">
                        <div class="level-left">
                            <div class="level-item">
                                <div class="content">
                                    <strong>Delete this modpack</strong><br />
                                    Once you delete a modpack, there is no going back. Please be certain.
                                </div>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <form method="post" action="/modpacks/{{ $modpack->slug }}">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <button class="button is-danger is-outlined" type="submit">Delete this modpack</button>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
@endsection
