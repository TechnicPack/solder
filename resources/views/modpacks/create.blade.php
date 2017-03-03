@component('layouts.app')

    @slot('hero')
        <h1 class="title">Modpacks</h1>
        <h2 class="subtitle">Create a new modpack</h2>
    @endslot

    @slot('links')
        <ul>
            <li><a href="{{ route('modpacks.index') }}">Overview</a></li>
            <li class="is-active"><a>Create new</a></li>
        </ul>
    @endslot

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

            <div class="columns is-multiline">
                <div class="column is-half">
                    <label class="label">Name</label>
                    <div class="control">
                        <input class="input" type="text" name="name">
                    </div>
                </div>
                <div class="column is-half">
                    <label class="label">Slug</label>
                    <div class="control">
                        <input class="input is-expanded" type="text" name="slug"
                        >
                    </div>
                </div>
                <div class="column is-12">
                    <label class="label">Description</label>
                    <div class="control">
                        <input class="input" type="text" name="description"
                        >
                    </div>
                </div>
                <div class="column is-quarter">
                    <label class="label">Privacy</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="privacy">
                                <option value="public">Public
                                </option>
                                <option value="unlisted">
                                    Unlisted
                                </option>
                                <option value="private">Private
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="column is-quarter">
                    <label class="label">Icon</label>
                    <div class="control">
                        <input type="file" name="icon" accept="image/*">
                    </div>
                </div>
                <div class="column is-quarter">
                    <label class="label">Logo</label>
                    <div class="control">
                        <input type="file" name="logo" accept="image/*">
                    </div>
                </div>
                <div class="column is-quarter">
                    <label class="label">Background</label>
                    <div class="control">
                        <input type="file" name="background" accept="image/*">
                    </div>
                </div>
                <div class="column is-12">
                    <label class="label">Tags</label>
                    <div class="control">
                        <input class="input is-disabled" type="text" name="tags[]" placeholder="Not Yet Implemented">
                    </div>
                </div>
                <div class="column one-third">
                    <label class="label">Overview</label>
                    <div class="control">
                        <textarea class="textarea" name="overview"
                                  rows="5"></textarea>
                    </div>
                </div>
                <div class="column one-third">
                    <label class="label">Help</label>
                    <div class="control">
                        <textarea class="textarea" name="help" rows="5"></textarea>
                    </div>
                </div>
                <div class="column one-third">
                    <label class="label">License</label>
                    <div class="control">
                        <textarea class="textarea" name="license"
                                  rows="5"></textarea>
                    </div>
                </div>
            </div>

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
