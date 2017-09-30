@extends('layouts.app')

@section('menu')
    @include('partials.modpack-menu', ['modpack' => $modpack])
@endsection

@section('content')
    <section class="section">

        @assistant
        <div class="notification is-primary">
            <figure class="image is-64x64 is-pulled-left" style="margin-right: 1rem;">
                <img src="/img/steve.png" />
            </figure>
            <p class="is-size-4">Modpack Build Management</p>
            <p>Modpacks are made up of multiple versions, called 'builds'. Builds help you organize changes and upgrades to your modpack without breaking players worlds.</p>
            <p>A build is what the launcher will download and run, so it needs to have a unique version number and the version of Minecraft you want launched.</p>
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
                                    <input class="input" name="version" type="text" placeholder="1.0.0">
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
                                    <input class="input" name="minecraft" type="text" placeholder="1.7.10">
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
    </section>
@endsection
