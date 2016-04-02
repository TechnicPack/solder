@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Build Management - {{ $modpack->name }}</h1>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        Build Management
        <div class="pull-right">
            <a class="btn btn-info btn-xs" href="{{ route('modpack.build.show', [$modpack->slug, $build->id]) }}" role="button">Refresh</a>
            <a class="btn btn-info btn-xs" href="{{ route('modpack.edit', $modpack->slug) }}" role="button">Back to Modpack</a>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                <th>Mod Name</th>
                <th>Version</th>
                <th style="width: 109px">Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- TODO: Implement build_modversion --}}
                {{-- @foreach ($build->mods as $mod) --}}
                <tr>
                <td>{{-- $mod->name --}}</td>
                <td>{{-- $mod->version --}}</td>
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

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#dataTables').dataTable();
    });
</script>
@stop
