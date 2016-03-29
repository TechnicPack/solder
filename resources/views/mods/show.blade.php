@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Mod Management - {{ $mod->name }}</h1>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        Mod Management: {{ $mod->name }}
        <div class="pull-right">
            <a class="btn btn-success btn-xs" href="{{-- route('mod.version.create') --}}" role="button">Create New Version</a>
            <a class="btn btn-warning btn-xs" href="{{ route('mod.edit', $mod->slug) }}" role="button">Edit Mod</a>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                <th>Slug</th>
                <th>Build Number</th>
                <th>MC Version</th>
                <th>Mod Count</th>
                <th>Rec</th>
                <th>Latest</th>
                <th>Published</th>
                <th>Private</th>
                <th style="width: 109px">Actions</th>
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
                <td>{{-- $build->recommended --}}</td>
                <td>{{-- $build->latest --}}</td>
                <td>{{-- $build->published --}}</td>
                <td>{{-- $build->private --}}</td>
                <td class="text-nowrap">
                    <a class="btn btn-info btn-xs" href="{{-- route('modpack.build.edit', $build->slug) --}}" role="button">Edit</a>
                    <a class="btn btn-danger btn-xs" href="{{-- route('modpack.build.destroy', $build->slug) --}}" role="button">Delete</a>
                </td>
                </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>
    </div>
</div>
@stop
