@extends('layouts.app')

@section('content')
    <assistant id="modpacks" v-cloak>
        Modpacks are made up of multiple versions, called 'builds'. Builds
        help you organize changes and upgrades to your modpack without
        breaking players worlds. A build is what the launcher will download
        and run, so it needs to have a unique version number and the version
        of Minecraft you want launched.
    </assistant>

    <section class="section">
        <div class="level has-text-capitalized is-size-6">
            <div class="level-left"></div>
            <div class="level-right">
                <div class="level-item has-padding-right-3">
                    <p class="menu-label">{{ $modpack->slug }}</p>
                </div>
                <div class="level-item">
                    <p class="menu-label">{{ $modpack->status }}
                    <span class="icon has-text-{{ $modpack->status }}">
                      <i class="fa fa-circle"></i>
                    </span>
                    </p>
                </div>
            </div>
        </div>

        @can('update', $modpack)
            @include('modpacks.partials.create-build')
        @endcan

        @includeWhen(count($modpack->builds), 'modpacks.partials.list-builds')
        @include('modpacks.partials.add-collaborators')
        @includeWhen(count($modpack->collaborators), 'modpacks.partials.list-collaborators')

        @can('update', $modpack)
            @include('modpacks.partials.update-modpack')
            @include('modpacks.partials.danger-zone')
        @endcan

    </section>
@endsection
