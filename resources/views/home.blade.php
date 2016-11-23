@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="page-header">
            <h1>Solder Dashboard</h1>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h2>Recently Updated Modpacks</h2>
                <recent-modpack-builds></recent-modpack-builds>
            </div>
            <div class="col-md-6">
                <h2>Recently Added Mods</h2>
                <recent-mod-releases></recent-mod-releases>
            </div>
        </div>

    </div>
</div>
@endsection
