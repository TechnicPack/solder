<div class="box">
    <h1>Package Settings</h1>
    <div class="box-body">
        <form method="post" action="/library/{{ $package->slug }}">
            {{ csrf_field() }}
            {{ method_field('patch') }}

            <!-- Name -->
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Name</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" name="name"  value="{{ old('name', $package->name) }}" />
                            @if($errors->has('name'))
                                <p class="help is-danger">{{ $errors->first('name') }}</p>
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
                            <input class="input {{ $errors->has('author') ? 'is-danger' : '' }}" type="text" name="author" placeholder="SpaceToad" value="{{ old('author', $package->author) }}"/>
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
                    <div class="field has-addons">
                        <div class="control is-expanded">
                            <input class="input {{ $errors->has('website_url') ? 'is-danger' : '' }}" type="text" name="website_url" placeholder="http://..." value="{{ old('website_url', $package->website_url) }}"/>
                            @if($errors->has('website_url'))
                                <p class="help is-danger">{{ $errors->first('website_url') }}</p>
                            @endif
                        </div>
                        <div class="control">
                            <a href="{{ $package->website_url }}" class="button" {{ $package->website_url == null ? 'disabled' : '' }}><i class="fa fa-fw fa-external-link"></i></a>
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
                    <div class="field has-addons">
                        <div class="control is-expanded">
                            <input class="input {{ $errors->has('donation_url') ? 'is-danger' : '' }}" type="text" name="donation_url" placeholder="http://..." value="{{ old('donation_url', $package->donation_url) }}"/>
                            @if($errors->has('donation_url'))
                                <p class="help is-danger">{{ $errors->first('donation_url') }}</p>
                            @endif
                        </div>
                        <div class="control">
                            <a href="{{ $package->donation_url }}" class="button" {{ $package->donation_url == null ? 'disabled' : '' }}><i class="fa fa-fw fa-usd"></i></a>
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
                            <textarea class="textarea {{ $errors->has('description') ? 'is-danger' : '' }}" name="description">{{ old('description', $package->description) }}</textarea>
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
                        <button class="button" type="submit">Update</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
