@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Modpack Management</h1>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        Modpack List
        <a class="btn btn-success btn-xs pull-right" href="{{ route('modpack.create' )}}" role="button">Create Modpack</a>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover" id="dataTables">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Rec</th>
                    <th>Latest</th>
                    <th>Hidden</th>
                    <th>Private</th>
                    <th style="width: 210px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($modpacks as $modpack)
                <tr>
                    <td>{{ $modpack->name }}</td>
                    <td>{{ $modpack->slug }}</td>
                    <td>{{ $modpack->recommended }}</td>
                    <td>{{ $modpack->latest }}</td>
                    <td>{{ $modpack->hidden ? 'Yes' : 'No' }}</td>
                    <td>{{ $modpack->private ? 'Yes' : 'No' }}</td>
                    <td class="text-nowrap">
                        <a class="btn btn-warning btn-xs" href="{{ route('modpack.show', $modpack->slug )}}" role="button">Manage Builds</a>
                        <a class="btn btn-info btn-xs" href="{{ route('modpack.edit', $modpack->slug )}}" role="button">Edit</a>
                        <a class="btn btn-danger btn-xs" href="{{ route('modpack.destroy', $modpack->slug) }}" role="button">Delete</a>
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
