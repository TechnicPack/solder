@extends('layouts.app')

@section('content')
    @component('components.assistant')
        The last step in building your modpack in Solder is to bundle together
        your favorite mods and resource packs from your Solder library. Simply
        select a package and version and click Bundle. If you don't see any
        packages listed, you probably need to go to your
        <a href="/library">Library</a> and create some.
    @endcomponent

    <section class="section">
        <div class="level has-text-capitalized is-size-6">
            <div class="level-left"></div>
            <div class="level-right">
                <div class="level-item has-padding-right-3">
                    <a href="/modpacks/{{ $build->modpack->slug }}" class="menu-label">
                        <figure class="icon">
                            <i class="fa fa-fw fa-arrow-left"></i>
                        </figure>
                        {{ $build->modpack->slug }}
                    </a>
                </div>
                <div class="level-item has-padding-right-3">
                    <p class="menu-label">v{{ $build->version }}</p>
                </div>
                <div class="level-item has-padding-right-3">
                    <p class="menu-label">MC {{ $build->minecraft_version }}</p>
                </div>
                @if($build->forge_version != null)
                <div class="level-item has-padding-right-3">
                    <p class="menu-label">Forge {{ $build->forge_version }}</p>
                </div>
                @endif
                @if($build->java_version != null)
                    <div class="level-item has-padding-right-3">
                    <p class="menu-label">Java {{ $build->java_version }}</p>
                </div>
                @endif
                @if($build->required_memory > 0)
                <div class="level-item has-padding-right-3">
                    <p class="menu-label">Mem {{ $build->required_memory }}</p>
                </div>
                @endif
                <div class="level-item">
                    <p class="menu-label">{{ $build->status }}
                    <span class="icon has-text-{{ $build->status }}">
                      <i class="fa fa-circle"></i>
                    </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="box">
            <h1>Bundle</h1>
            <div class="box-body">
                <release-picker build_id="{{ $build->id }}" />
            </div>
        </div>

        @if(count($build->releases))
        <div class="box">
            <h1>Bundled Releases</h1>
            <build-table :releases='{{ json_encode($build->releases) }}'></build-table>
        </div>
        @endif
        
        @include('builds.partials.update-build')
        @include('builds.partials.danger-zone')
    </section>
@endsection
