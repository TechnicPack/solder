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
                    <a class="nav-item is-tab" href="{{ route('versions.create', $resource->id) }}">New</a>
                @foreach($resource->versions as $nav)
                    <a class="nav-item is-tab {{ $version->id == $nav->id ? 'is-active' : '' }}" href="{{ route('versions.show', ['resource' => $resource->id, 'version' => $nav->id]) }}">{{ $nav->version }}</a>
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

            <table class="table">
                <thead>
                <tr>
                    <th>Filename</th>
                    <th>Location</th>
                    <th>Size</th>
                    <th>Checksum</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($version->assets as $asset)
                    <tr>
                        <!-- Filename -->
                        <th style="vertical-align: middle;">
                            {{ $asset->filename }}
                        </th>

                        <!-- Location -->
                        <th style="vertical-align: middle;">
                            {{ $asset->location }}
                        </th>

                        <!-- Filesize -->
                        <td style="vertical-align: middle;">
                            {{ $asset->size }}
                        </td>

                        <!-- MD5 -->
                        <th style="vertical-align: middle;">
                            {{ $asset->md5 }}
                        </th>

                        <!-- Actions -->
                        <td style="vertical-align: middle; text-align: right">
                            <a class="button is-outlined is-danger" onclick="event.preventDefault();document.getElementById('asset-{{ $asset->id }}').submit();">Delete</a>

                            <form id="asset-{{ $asset->id }}"
                                  action="#"
                                  method="POST"
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
