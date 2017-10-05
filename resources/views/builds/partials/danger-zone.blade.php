<div class="box is-danger">
    <h1>Danger Zone</h1>
    <div class="box-body">
        <ul class="list-group">
            @if($build->status == 'private')
                <li class="level list-group-item">
                    <div class="level-left">
                        <div class="level-item">
                            <div class="content">
                                <strong>Make this build public</strong><br />
                                Make this build visible to anyone.
                            </div>
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item">
                            <form method="post" action="/modpacks/{{ $build->modpack->slug }}/{{ $build->version }}">
                                {{ csrf_field() }}
                                {{ method_field('patch') }}

                                <input type="hidden" name="status" value="public" />
                                <button class="button is-danger is-outlined" type="submit">Make public</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endif
            @if($build->status == 'public')
                <li class="level list-group-item">
                    <div class="level-left">
                        <div class="level-item">
                            <div class="content">
                                <strong>Make this build private</strong><br />
                                Hide this build from the public.
                            </div>
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item">
                            <form method="post" action="/modpacks/{{ $build->modpack->slug }}/{{ $build->version }}">
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
                            <strong>Change build version</strong><br />
                            The build version is used as the public key.
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <form method="post" action="/modpacks/{{ $build->modpack->slug }}/{{ $build->version }}">
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
                        <form method="post" action="/modpacks/{{ $build->modpack->slug }}/{{ $build->version }}">
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
