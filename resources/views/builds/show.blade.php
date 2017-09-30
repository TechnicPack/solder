@extends('layouts.app')

@section('menu')
    @include('partials.modpack-menu', ['modpack' => $modpack, 'activeBuild' => $build])
@endsection

@section('content')
    <section class="section">
        @assistant
        <div class="notification is-primary">
            <figure class="image is-64x64 is-pulled-left" style="margin-right: 1rem;">
                <img src="/img/steve.png" />
            </figure>
            <p class="is-size-4">Bundles</p>
            <p>The last step in building your modpack in Solder is to bundle together your favorite mods and resource packs from your Solder library. Simply select a package and version and click Bundle.</p>
            <p>If you don't see any packages listed, you probably need to go to your <a href="/library">Library <i class="fa fa-fw fa-external-link"></i></a> and create some.</p>
        </div>
        @endassistant

        <div class="level has-text-capitalized is-size-6">
            <div class="level-left"></div>
            <div class="level-right">
                <div class="level-item has-padding-right-3">
                    <small>v{{ $build->version }}</small>
                </div>
                <div class="level-item has-padding-right-3">
                    <small>MC {{ $build->minecraft }}</small>
                </div>
                <div class="level-item has-padding-right-3">
                    <small>Java 1.8</small>
                </div>
                <div class="level-item has-padding-right-3">
                    <small>Mem 2048MB</small>
                </div>
                <div class="level-item">
                    <small>{{ $build->status }}</small>&nbsp;
                    <span class="icon has-text-{{ $build->status }}">
                      <i class="fa fa-circle"></i>
                    </span>
                </div>

            </div>
        </div>

        <div class="box">
            <h1>Bundle</h1>
            <div class="box-body">
                <form method="post" action="/bundles">
                    {{ csrf_field() }}
                    <input type="hidden" name="build_id" value="{{ $build->id }}" />
                    <release-picker />
                </form>
            </div>
        </div>

        @if(count($build->releases))
        <div class="box">
            <h1>Bundled Releases</h1>
            <build-table :releases='@json($build->releases)'></build-table>
        </div>
        @endif
    </section>
@endsection
