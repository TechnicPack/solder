@component('layouts.app')

    @slot('hero')
        <h1 class="title">Resources</h1>
        <h2 class="subtitle">See all available resources here</h2>
    @endslot

    @slot('links')
        <ul>
            <li class="is-active"><a>Overview</a></li>
            <li><a href="{{ route('resources.create') }}">Create new</a></li>
        </ul>
    @endslot

<section class="section">
    <div class="container">
        @if (session('status'))
            <div class="notification is-info">
                {{ session('status') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Created</th>
                    <th>&nbsp</th>
                </tr>
            </thead>

            <tbody>
                @foreach($resources as $resource)
                <tr>
                    <!-- Name -->
                    <th style="vertical-align: middle;">
                        {{ $resource->name }}
                        <a href="{{ $resource->website }}"><i class="fa fa-fw fa-external-link"></i></a>
                    </th>

                    <!-- Author -->
                    <td style="vertical-align: middle;">
                        {{ $resource->author }}
                    </td>

                    <!-- Created -->
                    <td style="vertical-align: middle;">
                        {{ $resource->created_at }}
                    </td>

                    <!-- Actions -->
                    <td style="vertical-align: middle; text-align: right">
                        <a href="{{ route('versions.index', $resource->id) }}" class="button is-outlined is-primary">Versions</a>
                        <a href="{{ route('resources.edit', $resource->id) }}" class="button is-outlined">Overview</a>
                        <a class="button is-outlined is-danger" onclick="event.preventDefault();document.getElementById('modpack-{{ $resource->slug }}').submit();">Delete</a>

                        <form id="modpack-{{ $resource->slug }}"
                              action="{{ route('resources.destroy', $resource->id) }}" method="POST"
                              style="display: none;">
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@endcomponent
