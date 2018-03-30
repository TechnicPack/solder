<div class="box">
    <h1>Add Release</h1>
    <div class="box-body">
        <form action="/library/{{ $package->slug }}/releases" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Version</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control has-icons-left is-expanded">
                            <input class="input {{ $errors->has('version') ? 'is-danger' : '' }}" type="text" placeholder="1.2.3" name="version" value="{{ old('version') }}">
                            <span class="icon is-small is-left">
                                <i class="fa fa-code-fork"></i>
                            </span>
                            @if($errors->has('version'))
                                <p class="help is-danger">{{ $errors->first('version') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">File</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <div class="columns">
                                <div class="column is-narrow">
                                    <div class="file {{ $errors->has('archive') ? 'is-danger' : '' }}">
                                        <label class="file-label">
                                            <input class="file-input" type="file" name="archive">
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
                                    @if($errors->has('archive'))
                                        <p class="is-size-7 has-text-danger">{{ $errors->first('archive') }}</p>
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
                        <button class="button is-primary" type="submit">Add Release</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
