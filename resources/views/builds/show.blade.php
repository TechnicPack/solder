@extends('layouts.app')

@section('menu')
    @include('partials.modpack-menu', ['modpack' => $modpack, 'activeBuild' => $build])
@endsection

@section('content')
    <section class="section">
        <nav class="level is-mobile">
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Build</p>
                    <p class="title">{{ $build->version }}</p>
                </div>
            </div>
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Minecraft</p>
                    <p class="title">{{ $build->minecraft }}</p>
                </div>
            </div>
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Java</p>
                    <p class="title">N/A</p>
                </div>
            </div>
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Memory</p>
                    <p class="title">N/A</p>
                </div>
            </div>
        </nav>
    </section>
    <section class="section">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    Build Management
                </p>
            </header>
            <div class="card-content">
                <form method="post" action="/bundles">
                    {{ csrf_field() }}
                    <input type="hidden" name="build_id" value="{{ $build->id }}" />
                    <release-picker />
                </form>
            </div>
            <div class="card-content is-paddingless">
                <build-table :releases='@json($build->releases)'></build-table>
            </div>
        </div>
    </section>
@endsection
