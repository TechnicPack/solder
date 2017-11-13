<div class="box">
    <h1>Create Modpack</h1>
    <div class="box-body">
        <form action="{{ route('modpacks.store') }}" method="post" id="create-modpack" enctype="multipart/form-data">
        {{ csrf_field() }}

        <!-- Name -->
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Name</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" onKeyUp="nameUpdated()" id="modpack-name" name="name" placeholder="Attack of the B-Team" value="{{ old('name') }}" />
                            @if($errors->has('name'))
                                <p class="help is-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slug -->
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Slug</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input {{ $errors->has('slug') ? 'is-danger' : '' }}" onKeyUp="slugUpdated()" id="modpack-slug" name="slug" placeholder="attack-of-the-bteam" value="{{ old('slug') }}" />
                            @if($errors->has('slug'))
                                <p class="help is-danger">{{ $errors->first('slug') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Icon -->
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

            <!-- Status -->
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

            <!-- Submit -->
            <div class="field is-horizontal">
                <div class="field-label">
                    &nbsp;
                </div>
                <div class="field-body">
                    <div class="control">
                        <button class="button is-primary" type="submit">Add Modpack</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('afterScripts')
    <script>
        var calculatedSlug = true;

        function nameUpdated() {
            if(calculatedSlug) {
                document.getElementById("modpack-slug").value = slugify(document.getElementById("modpack-name").value);
            }
        }

        function slugUpdated() {
            calculatedSlug = false;
        }

        function slugify(text) {
            // https://gist.github.com/mathewbyrne/1280286
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '')             // Trim - from end of text
                .replace(/[\s_-]+/g, '-');
        }
    </script>
@endpush
