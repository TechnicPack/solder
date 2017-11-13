<div class="box is-danger">
    <h1>Danger Zone</h1>
    <div class="box-body">
        <ul class="list-group">
            <li class="level list-group-item">
                <div class="level-left">
                    <div class="level-item">
                        <div class="content">
                            <strong>Update build status</strong><br />
                            Change the visibility status of this build.
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <form method="post" action="{{ route('builds.update', [$build->modpack, $build]) }}">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}

                            <input type="hidden" name="status" value="draft" />
                            <button class="button {{ $build->status == 'draft' ? 'is-static' : 'is-danger' }} is-outlined" type="submit">Draft</button>
                        </form>
                    </div>
                    <div class="level-item">
                        <form method="post" action="{{ route('builds.update', [$build->modpack, $build]) }}">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}

                            <input type="hidden" name="status" value="private" />
                            <button class="button {{ $build->status == 'private' ? 'is-static' : 'is-danger' }} is-outlined" type="submit">Private</button>
                        </form>
                    </div>
                    <div class="level-item">
                        <form method="post" action="{{ route('builds.update', [$build->modpack, $build]) }}">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}

                            <button class="button {{ $build->status == 'public' ? 'is-static' : 'is-danger' }} is-outlined" type="submit">Public</button>
                            <input type="hidden" name="status" value="public" />
                        </form>
                    </div>
                </div>
            </li>
            <li class="level list-group-item">
                <div class="level-left">
                    <div class="level-item">
                        <div class="content">
                            <strong>Change build version</strong><br />
                            The build version is used as the public key.
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <form method="post" action="{{ route('builds.update', [$build->modpack, $build]) }}">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}

                            <div class="field has-addons">
                                <div class="control">
                                    <input class="input is-danger" name="version" type="text" value="{{ old('version', $build->version) }}">
                                    @if($errors->has('version'))
                                        <p class="help is-danger">{{ $errors->first('version') }}</p>
                                    @endif
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
                            <strong>Delete this build</strong><br />
                            Once you delete a build, there is no going back. Please be certain.
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <form method="post" action="{{ route('builds.destroy', [$build->modpack, $build]) }}">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="button is-danger is-outlined" type="submit">Delete this build</button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
