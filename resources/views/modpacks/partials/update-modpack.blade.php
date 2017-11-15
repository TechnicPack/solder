<div class="box">
    <h1>Modpack Settings</h1>
    <div class="box-body">
        <form method="post" action="{{ route('modpacks.update', $modpack) }}" enctype="multipart/form-data">
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
