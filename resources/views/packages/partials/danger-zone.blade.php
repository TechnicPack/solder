<div class="box is-danger">
    <h1>Danger Zone</h1>
    <div class="box-body">
        <ul class="list-group">
            <li class="level list-group-item">
                <div class="level-left">
                    <div class="level-item">
                        <div class="content">
                            <strong>Change package slug</strong><br />
                            The package slug is used as the public key.
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <form method="post" action="/library/{{ $package->slug }}">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}

                            <div class="field has-addons">
                                <div class="control">
                                    <input class="input is-danger" name="slug" type="text" value="{{ old('slug', $package->slug) }}">
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
                            <strong>Delete this package</strong><br />
                            Once you delete a package, there is no going back. Please be certain.
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <form method="post" action="/library/{{ $package->slug }}">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="button is-danger is-outlined" type="submit">Delete this package</button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
