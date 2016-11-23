@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1>{{ $build->modpack->name }} {{ $build->version }}</h1>
        </div>

        <h2>Included Mods</h2>
        <build-releases build-id="{{ $build->id }}"></build-releases>

    </div>
</div>
@endsection
