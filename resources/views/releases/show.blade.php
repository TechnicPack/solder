@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1>{{ $release->mod->name }} {{ $release->version }}</h1>
        </div>

        <h2>Part of</h2>
        <release-builds release-id="{{ $release->id }}"></release-builds>

    </div>
</div>
@endsection
