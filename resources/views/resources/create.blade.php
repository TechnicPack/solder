@component('layouts.app')

    @slot('hero')
        <h1 class="title">Resources</h1>
        <h2 class="subtitle">Create a new resource</h2>
    @endslot

    @slot('links')
        <ul>
            <li><a href="{{ route('resources.index') }}">Overview</a></li>
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

        <form method="post" action="{{ route('resources.store') }}">
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
                        <input class="input is-expanded" type="text" name="slug">
                    </div>
                </div>
                <div class="column is-half">
                    <label class="label">Author</label>
                    <div class="control">
                        <input class="input" type="text" name="author">
                    </div>
                </div>
                <div class="column is-half">
                    <label class="label">Website</label>
                    <div class="control">
                        <input class="input is-expanded" type="text" name="website">
                    </div>
                </div>
                <div class="column is-12">
                    <label class="label">Description</label>
                    <div class="control">
                        <textarea class="textarea" name="description"></textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="control is-grouped">
                <p class="control is-horizontal">
                    <button class="button is-primary" type="submit">Create</button>
                </p>
                <p class="control">
                    <a class="button is-link" href="{{ route('resources.index') }}">Cancel</a>
                </p>
            </div>

        </form>
    </div>
</section>

@endcomponent
