@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1>{{ $version->mod->name }} {{ $version->version }}</h1>
        </div>

        <h2>Part of</h2>
        <version-builds version-id="{{ $version->id }}"></version-builds>

    </div>
</div>
@endsection
