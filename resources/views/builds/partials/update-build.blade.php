<div class="box">
    <h1>Build Settings</h1>
    <div class="box-body">
        <form method="post" action="/modpacks/{{ $build->modpack->slug }}/{{ $build->version }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Minecraft Version</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input {{ $errors->has('minecraft_version') ? 'is-danger' : '' }}" name="minecraft_version" value="{{ old('minecraft_version', $build->minecraft_version) }}" />
                            @if($errors->has('minecraft_version'))
                                <p class="help is-danger">{{ $errors->first('minecraft_version') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Forge Version</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input {{ $errors->has('forge_version') ? 'is-danger' : '' }}" name="forge_version" value="{{ old('forge_version', $build->forge_version) }}" />
                            @if($errors->has('forge_version'))
                                <p class="help is-danger">{{ $errors->first('forge_version') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Java Version</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input {{ $errors->has('java_version') ? 'is-danger' : '' }}" name="java_version" value="{{ old('java_version', $build->java_version) }}" />
                            @if($errors->has('java_version'))
                                <p class="help is-danger">{{ $errors->first('java_version') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Required Memory</label>
                </div>
                <div class="field-body">
                    <div class="field has-addons">
                        <div class="control is-expanded">
                            <input class="input {{ $errors->has('required_memory') ? 'is-danger' : '' }}" name="required_memory" value="{{ old('required_memory', $build->required_memory) }}" />
                            @if($errors->has('required_memory'))
                                <p class="help is-danger">{{ $errors->first('required_memory') }}</p>
                            @endif
                        </div>
                        <div class="control">
                            <p class="button is-static">MB</p>
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
