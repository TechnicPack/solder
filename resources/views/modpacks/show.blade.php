@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Build Management - {{ $modpack->name }}</h1>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        Build Management: {{ $modpack->name }}
        <div class="pull-right">
            <a class="btn btn-success btn-xs" href="{{ route('modpack.build.create', $modpack->slug) }}" role="button">Create New Build</a>
            <a class="btn btn-warning btn-xs" href="{{ route('modpack.edit', $modpack->slug) }}" role="button">Edit Modpack</a>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                <th>Id</th>
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
                @foreach ($modpack->builds as $build)
                <tr>
                <td>{{ $build->id }}</td>
                <td>{{ $build->version }}</td>
                <td>{{ $build->minecraft }}</td>
                <td>{{ $build->modcount }}</td>
                <td>{{ $build->recommended }}</td>
                <td>{{ $build->latest }}</td>
                <td>{{ $build->published ? 'Yes' : 'No' }}</td>
                <td>{{ $build->private ? 'Yes' : 'No' }}</td>
                <td class="text-nowrap">
                    <a class="btn btn-warning btn-xs" href="{{ route('modpack.build.show', [$modpack->slug, $build->id]) }}" role="button">Manage Mods</a>
                    <a class="btn btn-info btn-xs" href="{{ route('modpack.build.edit', [$modpack->slug, $build->id]) }}" role="button">Edit</a>
                    <a class="btn btn-danger btn-xs" href="{{  route('modpack.build.destroy', [$modpack->slug, $build->id]) }}" role="button">Delete</a>
                </td>
                </tr>
               @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('end')
<script type="text/javascript">
    $(document).ready(function() {
        $('#dataTables').dataTable();
    });
</script>
@stop
