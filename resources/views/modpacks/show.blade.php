@extends('layouts.app')

@section('content')
    @component('components.assistant')
        Modpacks are made up of multiple versions, called 'builds'. Builds
        help you organize changes and upgrades to your modpack without
        breaking players worlds. A build is what the launcher will download
        and run, so it needs to have a unique version number and the version
        of Minecraft you want launched.
    @endcomponent

    <section class="section">
        <div class="level has-text-capitalized is-size-6">
            <div class="level-left"></div>
            <div class="level-right">
                <div class="level-item has-padding-right-3">
                    <p class="menu-label">{{ $modpack->slug }}</p>
                </div>
                <div class="level-item">
                    <p class="menu-label">
                        @if($modpack->is_published)
                            Published
                            <span class="icon has-text-success">
                              <i class="fa fa-circle"></i>
                            </span>
                        @else
                            Unublished
                            <span class="icon has-text-warning">
                              <i class="fa fa-circle"></i>
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @include('modpacks.partials.create-build')
        @includeWhen(count($modpack->builds), 'modpacks.partials.list-builds')
        @include('modpacks.partials.update-modpack')
        @include('modpacks.partials.danger-zone')

    </section>
@endsection
