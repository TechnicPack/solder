<div class="box">
    <h1>Add Package</h1>
    <div class="box-body">
        <form action="/library/" method="post">
            {{ csrf_field() }}

            <!-- Name -->
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Name</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control is-expanded">
                            <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" onKeyUp="nameUpdated()" id="package-name" type="text" name="name" placeholder="Buildcraft" value="{{ old('name') }}"/>
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
                        <div class="control is-expanded">
                            <input class="input {{ $errors->has('slug') ? 'is-danger' : '' }}" onKeyUp="slugUpdated()" id="package-slug" type="text" name="slug" placeholder="buildcraft" value="{{ old('slug') }}"/>
                            @if($errors->has('slug'))
                                <p class="help is-danger">{{ $errors->first('slug') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Author -->
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Author</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control is-expanded">
                            <input class="input {{ $errors->has('author') ? 'is-danger' : '' }}" type="text" name="author" placeholder="SpaceToad" value="{{ old('author') }}"/>
                            @if($errors->has('author'))
                                <p class="help is-danger">{{ $errors->first('author') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Website URL -->
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Website URL</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control is-expanded">
                            <input class="input {{ $errors->has
                            ('website_url') ? 'is-danger' : '' }}" type="text" name="website_url" placeholder="http://..." value="{{ old('website_url') }}"/>
                            @if($errors->has('website_url'))
                                <p class="help is-danger">{{ $errors->first('website_url') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donation URL -->
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Donation URL</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control is-expanded">
                            <input class="input {{ $errors->has('donation_url') ? 'is-danger' : '' }}" type="text" name="donation_url" placeholder="http://..." value="{{ old('donation_url') }}"/>
                            @if($errors->has('donation_url'))
                                <p class="help is-danger">{{ $errors->first('donation_url') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Description</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control is-expanded">
                            <textarea class="textarea {{ $errors->has('description') ? 'is-danger' : '' }}" name="description">{{ old('description') }}</textarea>
                            @if($errors->has('description'))
                                <p class="help is-danger">{{ $errors->first('description') }}</p>
                            @endif
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
                        <button class="button is-primary" type="submit">Add Package</button>
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
                document.getElementById("package-slug").value = slugify(document.getElementById("package-name").value);
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
