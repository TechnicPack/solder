@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="page-header">
            <h1>{{ $modpack->name }}</h1>
        </div>

        <h2>Builds</h2>
        <modpack-builds modpack-id="{{ $modpack->id }}"></modpack-builds>

    </div>
</div>
@endsection
