@component('layouts.app')

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">{{ $modpack->name }}</h1>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if (session('status'))
                <div class="notification is-info">
                    {{ session('status') }}
                </div>
            @endif

                @if( count($errors) )
                    <div class="notification is-warning">
                        <ul>
                            @foreach($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="tabs is-right">
                    <ul>
                        <li><a href="{{ route('modpacks.show', $modpack->id) }}">Show</a></li>
                        <li class="is-active"><a href="{{ route('modpacks.edit', $modpack->id) }}">Edit</a></li>
                    </ul>
                </div>

                <form method="post" action="{{ route('modpacks.update', $modpack->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('patch') }}

                    {{-- name --}}
                    <label class="label">Name</label>
                    <p class="control">
                        <input class="input is-expanded" type="text" name="name" value="{{ old('name', $modpack->name) }}">
                    </p>

                    {{-- slug --}}
                    <label class="label">Slug</label>
                    <p class="control">
                        <input class="input is-expanded" type="text" name="slug" value="{{ old('slug', $modpack->slug) }}">
                    </p>

                    {{-- description --}}
                    <label class="label">Description</label>
                    <p class="control">
                        <input class="input" type="text" name="description" value="{{ old('description', $modpack->description) }}">
                    </p>

                    {{-- privacy --}}
                    <label class="label">Privacy</label>
                    <p class="control">
                    <span class="select">
                        <select class="input" name="privacy">
                            <option value="public" {{ $modpack->privacy == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="unlisted" {{ $modpack->privacy == 'unlisted' ? 'selected' : '' }}>Unlisted</option>
                            <option value="private" {{ $modpack->privacy == 'private' ? 'selected' : '' }}>Private</option>
                        </select>
                    </span>
                    </p>

                    {{-- tags --}}
                    <label class="label">Tags</label>
                    <p class="control">
                        <input class="input is-disabled" type="text" name="tags[]" placeholder="Not Yet Implemented">
                    </p>

                    {{-- icon --}}
                    <label class="label">Icon</label>
                    <p class="control">
                        <input type="file" name="icon" accept="image/*">
                    </p>

                    {{-- logo --}}
                    <label class="label">Logo</label>
                    <p class="control">
                        <input type="file" name="logo" accept="image/*">
                    </p>

                    {{-- background --}}
                    <label class="label">Background</label>
                    <p class="control">
                        <input type="file" name="background" accept="image/*">
                    </p>

                    {{-- overview --}}
                    <label class="label">Overview</label>
                    <p class="control">
                        <textarea class="input" name="overview" rows="5">{{ old('overview', $modpack->overview) }}</textarea>
                    </p>

                    {{-- help --}}
                    <label class="label">Help</label>
                    <p class="control">
                        <textarea class="input" name="help" rows="5">{{ old('help', $modpack->help) }}</textarea>
                    </p>

                    {{-- license --}}
                    <label class="label">License</label>
                    <p class="control">
                        <textarea class="input" name="license" rows="5">{{ old('license', $modpack->license) }}</textarea>
                    </p>

                    {{-- Submit --}}
                    <div class="control is-grouped">
                        <p class="control is-horizontal">
                            <button class="button is-primary" type="submit">Update</button>
                        </p>
                        <p class="control">
                            <a class="button is-link" href="{{ route('modpacks.show', $modpack->id) }}">Cancel</a>
                        </p>
                    </div>

                </form>
        </div>
    </section>

@endcomponent
