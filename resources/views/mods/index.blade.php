@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Mod Management</h1>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        Mod List
        <a class="btn btn-success btn-xs pull-right" href="{{ route('mod.create' )}}" role="button">Create Mod</a>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover" id="dataTables">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Author</th>
                    <th>Link</th>
                    <th style="width: 108px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mods as $mod)
                <tr>
                    <td>{{ $mod->name }}</td>
                    <td>{{ $mod->slug }}</td>
                    <td>{{ $mod->author }}</td>
                    <td>{{ $mod->link }}</td>
                    <td class="text-nowrap">
                        <a class="btn btn-info btn-xs" href="{{ route('mod.edit', $mod->slug )}}" role="button">Edit</a>
                        <a class="btn btn-danger btn-xs" href="{{ route('mod.destroy', $mod->slug) }}" role="button">Delete</a>
                    </td>
                </tr>
                @endforeach
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
