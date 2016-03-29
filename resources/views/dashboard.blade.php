@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Solder Dashboard <br /><small>Welcome to Technic Solder!</small></h1>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-refresh"></i> Recently Updated Modpacks
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                <th>Slug</th>
                <th>Build Number</th>
                <th>MC Version</th>
                <th>Mod Count</th>
                <th>Updated</th>
                <th style="width: 85px">Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- TODO: Implement builds --}}
                {{-- @foreach ($builds as $build) --}}
                <tr>
                <td>{{-- $build->slug --}}</td>
                <td>{{-- $build->number --}}</td>
                <td>{{-- $build->minecraft --}}</td>
                <td>{{-- $build->modcount --}}</td>
                <td>{{-- $build->updated_at --}}</td>
                <td class="text-nowrap">
                    <a class="btn btn-warning btn-xs" href="{{-- route('modpack.build.edit', $build->slug) --}}" role="button">Manage</a>
                </td>
                </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-refresh"></i> Recently Added Mod Versions
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                <th>#</th>
                <th>Version #</th>
                <th>Mod Name</th>
                <th>Author</th>
                <th>Website</th>
                <th>Created On</th>
                <th style="width: 90px">Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- TODO: Implement Modversions --}}
                {{-- @foreach ($modversions as $modversion) --}}
                <tr>
                <td>{{-- $modversion->id --}}</td>
                <td>{{-- $modversion->version --}}</td>
                <td>{{-- $modversion->name --}}</td>
                <td>{{-- $modversion->author --}}</td>
                <td>{{-- $modversion->link --}}</td>
                <td>{{-- $modversion->created_at --}}</td>
                <td class="text-nowrap">
                    <a class="btn btn-warning btn-xs" href="{{-- route('mod.version.edit', $modversion->id) --}}" role="button">Manage</a>
                </td>
                </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>
    </div>
</div>

<p class="text-muted">TechnicSolder is an open source project. It is under the MIT license. Source Code: <a class="text-muted" href="https://github.com/TechnicPack/TechnicSolder" target="_blank">https://github.com/TechnicPack/TechnicSolder</a></p>
@stop
