@component('layouts.app')

    @slot('hero')
        <h1 class="title">{{ $resource->name }}</h1>
        <h2 class="subtitle">by {{ $resource->author }}</h2>
    @endslot

    @slot('links')
        <ul>
            <li class="is-active"><a href="{{ route('versions.index', $resource->id) }}">Versions</a></li>
            <li><a href="{{ route('resources.edit', $resource->id) }}">Overview</a></li>
        </ul>
    @endslot

    <nav class="nav has-shadow">
        <div class="container">
            <div class="nav-left">
                <a class="nav-item is-tab is-active" href="{{ route('versions.create', $resource->id) }}">New</a>
                @foreach($resource->versions as $nav)
                    <a class="nav-item is-tab" href="{{ route('versions.show', ['resource' => $resource->id, 'version' => $nav->id]) }}">{{ $nav->version }}</a>
                @endforeach
            </div>
        </div>
    </nav>


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

            <form method="post" action="{{ route('versions.store', $resource->id) }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="columns is-multiline">
                    <div class="column is-one-third">
                        <label class="label">Version</label>
                        <div class="control">
                            <input class="input" type="text" name="version">
                        </div>
                    </div>


                    <div class="column is-one-third">
                        <label class="label">Mod</label>
                        <div class="control">
                            <input class="is-disabled" type="file" name="mod">
                        </div>
                    </div>

                    <div class="column is-one-third ">
                        <label class="label">Config</label>
                        <div class="control">
                            <input class="is-disabled" type="file" name="config">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="control is-grouped">
                    <p class="control is-horizontal">
                        <button class="button is-primary" type="submit">Create</button>
                    </p>
                </div>

            </form>
        </div>
    </section>
@endcomponent
