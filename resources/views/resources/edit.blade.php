@component('layouts.app')

    @slot('hero')
        <h1 class="title">{{ $resource->name }}</h1>
        <h2 class="subtitle">by {{ $resource->author }}</h2>
    @endslot

    @slot('links')
        <ul>
            <li><a href="{{ route('versions.index', $resource->id) }}">Versions</a></li>
            <li class="is-active"><a href="{{ route('resources.edit', $resource->id) }}">Overview</a></li>
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

        <form method="post" action="{{ route('resources.update', $resource->id) }}">
            {{ csrf_field() }}
            {{ method_field('patch') }}

            <div class="columns is-multiline">
                <div class="column is-half">
                    <label class="label">Name</label>
                    <div class="control">
                        <input class="input" type="text" name="name" value="{{ old('name', $resource->name) }}">
                    </div>
                </div>
                <div class="column is-half">
                    <label class="label">Slug</label>
                    <div class="control">
                        <input class="input is-expanded" type="text" name="slug" value="{{ old('slug', $resource->slug) }}">
                    </div>
                </div>
                <div class="column is-half">
                    <label class="label">Author</label>
                    <div class="control">
                        <input class="input" type="text" name="author" value="{{ old('author', $resource->author) }}">
                    </div>
                </div>
                <div class="column is-half">
                    <label class="label">Website</label>
                    <div class="control">
                        <input class="input is-expanded" type="text" name="website" value="{{ old('website', $resource->website) }}">
                    </div>
                </div>
                <div class="column is-12">
                    <label class="label">Description</label>
                    <div class="control">
                        <textarea class="textarea" name="description">{{ old('description', $resource->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="control is-grouped">
                <p class="control is-horizontal">
                    <button class="button is-primary" type="submit">Update</button>
                </p>
                <p class="control">
                    <a class="button is-link" href="{{ route('versions.index', $resource->id) }}">Cancel</a>
                </p>
            </div>

        </form>
    </div>
</section>

@endcomponent
