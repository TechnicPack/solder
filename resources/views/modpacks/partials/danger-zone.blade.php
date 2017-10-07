<div class="box is-danger">
    <h1>Danger Zone</h1>
    <div class="box-body">
        <ul class="list-group">
            @if(! $modpack->is_published)
                <li class="level list-group-item">
                    <div class="level-left">
                        <div class="level-item">
                            <div class="content">
                                <strong>Publish this modpack</strong><br />
                                Make this modpack visible to anyone.
                            </div>
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item">
                            <form method="post" action="/modpacks/{{ $modpack->slug }}">
                                {{ csrf_field() }}
                                {{ method_field('patch') }}

                                <input type="hidden" name="is_published" value="1" />
                                <button class="button is-danger is-outlined" type="submit">Make public</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endif
            @if($modpack->is_published)
                <li class="level list-group-item">
                    <div class="level-left">
                        <div class="level-item">
                            <div class="content">
                                <strong>Un-publish this modpack</strong><br />
                                Hide this modpack from the public.
                            </div>
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item">
                            <form method="post" action="/modpacks/{{ $modpack->slug }}">
                                {{ csrf_field() }}
                                {{ method_field('patch') }}

                                <input type="hidden" name="is_published" value="0" />
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
                            The modpack slug is used as the public key.
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
                                    <input class="input is-danger" name="slug" type="text" value="{{ old('slug', $modpack->slug) }}">
                                    @if($errors->has('slug'))
                                        <p class="help is-danger">{{ $errors->first('slug') }}</p>
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
