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
                            <input class="input {{ $errors->has('version') ? 'is-danger' : '' }}" name="version" type="text" placeholder="1.0.0" value="{{ old('version') }}">
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
                            <input class="input {{ $errors->has('minecraft') ? 'is-danger' : '' }}" name="minecraft" type="text" placeholder="1.7.10" value="{{ old('minecraft') }}">
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
