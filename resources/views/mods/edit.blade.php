@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Mod Management - {{ $mod->name }}</h1>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Edit Mod: {{ $mod-> name }}</div>
    <div class="panel-body">
        <form method="post" action="{{ route('mod.store') }}">
            <div class="row">

                <div class="col-md-12">
                    <h3>Mod Information</h3>
                    <p>Uploading a mod for distribution is simple. Fill in information about the mod here, once you have the mod with some resources you can start building your modpacks.</p>
                    <hr />

                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        <label for="name">Mod Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $mod->name) }}">
                        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('slug')) has-error @endif">
                        <label for="slug">Mod Slug</label>
                        <input type="text" name="slug" class="form-control" id="slug" value="{{ old('slug', $mod->slug) }}">
                        @if ($errors->has('slug')) <p class="help-block">{{ $errors->first('slug') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                        <label for="description">Mod Description</label>
                        <input type="text" name="description" class="form-control" id="description" value="{{ old('description', $mod->description) }}">
                        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('descriptino') }}</p> @endif
                    </div>
                    <hr />

                    <div class="form-group @if ($errors->has('author')) has-error @endif">
                        <label for="author">Mod Author(s)</label>
                        <input type="text" name="author" class="form-control" id="author" value="{{ old('author', $mod->author) }}">
                        @if ($errors->has('author')) <p class="help-block">{{ $errors->first('author') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('link')) has-error @endif">
                        <label for="link">Mod Link</label>
                        <input type="text" name="link" class="form-control" id="link" value="{{ old('link', $mod->link) }}">
                        @if ($errors->has('link')) <p class="help-block">{{ $errors->first('link') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('donatelink')) has-error @endif">
                        <label for="donatelink">Mod Donations Link</label>
                        <input type="text" name="donatelink" class="form-control" id="donatelink" value="{{ old('donatelink', $mod->donatelink) }}">
                        @if ($errors->has('donatelink')) <p class="help-block">{{ $errors->first('donatelink') }}</p> @endif
                    </div>
                </div>


            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr />
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-success">Save Mod</button>
                    <a href="{{ route('mod.destroy', $mod->slug) }}" class="btn btn-danger js-ajax-delete" data-toggle="modal" data-target="#deleteModalDialog">Delete Mod</a>
                    <a href="{{ route('mod.index') }}" class="btn btn-primary">Go Back</a>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalDialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header alert-danger">
                <h4>Delete {{ $mod->name }}?</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure that you want to permanently delete the selected element?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-sm btn-danger" id="deleteModalConfirm">Delete</a>
            </div>
        </div>
    </div>
</div>

@stop

@section('end')
<script type="text/javascript">
    var $modal = $('#deleteModal');
    $('.js-ajax-delete').on('click', function(event)
    {
        var deleteUrl = $(this).attr('href');
        $modal.data('delete-url', deleteUrl);
        $modal.modal('show');
    });

    $('#deleteModalConfirm').on('click', function()
    {
        var $modal = $('#deleteModal');
        var deleteUrl = $modal.data('delete-url');
        $.ajax({
            method: "DELETE",
            url: deleteUrl,
        });
        $modal.modal('hide');
    });
</script>
@stop
