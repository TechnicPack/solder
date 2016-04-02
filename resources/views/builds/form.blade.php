<div class="col-md-6">
    <h3>Build Management</h3>
    <p>TODO: Add some useful help info for the user here</p>

    <hr/>

    <div class="form-group @if ($errors->has('version')) has-error @endif">
        <label for="version">Build Number</label>
        <input type="text" name="version" class="form-control" id="version"
               value="{{ old('version', $build->version) }}">
        @if ($errors->has('version'))
            <p class="help-block">{{ $errors->first('version') }}</p>
        @endif
    </div>

    <div class="form-group @if ($errors->has('minecraft')) has-error @endif">
        <label for="minecraft">Minecraft Version</label>
        <select name="minecraft" class="form-control" id="minecraft">
            {{-- TODO: Implement a table with lookup --}}
            <option value="1.7.10">1.7.10</option>
        </select>
        @if ($errors->has('minecraft'))
            <p class="help-block">{{ $errors->first('minecraft') }}</p>
        @endif
    </div>

    <hr>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="published" {{ old('published', $build->published == 1) ? 'checked' : '' }}> Published
        </label>
    </div>

    <p>Only published builds are available in the API response for the modpack list.</p>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="private" {{ old('private', $build->private == 1) ? 'checked' : '' }}> Private
        </label>
    </div>

    <p>Private builds will only be available to clients that are linked to this modpack.</p>


</div>
<div class="col-md-6">

    <h3>Client Parameters</h3>
    <p>&nbsp;</p>
    <hr/>

    <div class="form-group @if ($errors->has('min_memory')) has-error @endif">
        <label for="min_memory">Minimum Memory</label>
        <div class="input-group">
            <input type="text" name="min_memory" class="form-control" id="min_memory"
                   value="{{ old('min_memory') }}">
            <div class="input-group-addon">MB</div>
        </div>
        @if ($errors->has('min_memory'))
            <p class="help-block">{{ $errors->first('min_memory') }}</p>
        @endif
    </div>

    <div class="form-group @if ($errors->has('min_java')) has-error @endif">
        <label for="min_java">Minimum Java Version</label>
        <select name="min_java" class="form-control" id="min_java">
            <option value="1.8">Java 1.8</option>
            <option value="1.7">Java 1.7</option>
            <option value="1.6">Java 1.6</option>
            <option value="">No Requirement</option>
        </select>
        @if ($errors->has('min_java'))
            <p class="help-block">{{ $errors->first('min_java') }}</p>
        @endif
    </div>

</div>
