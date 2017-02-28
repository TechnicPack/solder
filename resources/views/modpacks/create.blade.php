@component('layouts.app')

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">Create Modpack</h1>
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

            <form method="post" action="{{ route('modpacks.store') }}">
                {{ csrf_field() }}

                {{-- name --}}
                <label class="label">Name</label>
                <p class="control">
                    <input class="input" type="text" name="name" value="{{ old('name') }}">
                </p>

                {{-- slug --}}
                <label class="label">Slug</label>
                <p class="control">
                    <input class="input" type="text" name="slug" value="{{ old('slug') }}">
                </p>

                {{-- description --}}
                <label class="label">Description</label>
                <p class="control">
                    <input class="input" type="text" name="description" value="{{ old('description') }}">
                </p>

                {{-- privacy --}}
                <label class="label">Privacy</label>
                <p class="control">
                    <span class="select">
                        <select class="input" name="privacy">
                            <option value="public">Public</option>
                            <option value="unlisted">Unlisted</option>
                            <option value="private">Private</option>
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
                    <textarea class="input" name="overview" rows="5">{{ old('overview') }}</textarea>
                </p>

                {{-- help --}}
                <label class="label">Help</label>
                <p class="control">
                    <textarea class="input" name="help" rows="5">{{ old('help') }}</textarea>
                </p>

                {{-- license --}}
                <label class="label">License</label>
                <p class="control">
                    <textarea class="input" name="license" rows="5">{{ old('license') }}</textarea>
                </p>

                {{-- Submit --}}
                <div class="control is-grouped">
                    <p class="control is-horizontal">
                        <button class="button is-primary" type="submit">Create</button>
                    </p>
                    <p class="control">
                        <a class="button is-link" href="{{ route('modpacks.index') }}">Cancel</a>
                    </p>
                </div>

            </form>
        </div>
    </section>

@endcomponent
