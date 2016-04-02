@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Mod Management</h1>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Create Mod</div>
    <div class="panel-body">
        <form method="post" action="{{ route('mod.store') }}">
            <div class="row">

                <div class="col-md-6">
                    <h3>Mod Information</h3>
                    <p>Uploading a mod for distribution is simple. Fill in information about the mod here, once you have the mod with some resources you can start building your modpacks.</p>
                    <hr />

                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        <label for="name">Mod Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}">
                        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('slug')) has-error @endif">
                        <label for="slug">Mod Slug</label>
                        <input type="text" name="slug" class="form-control" id="slug" value="{{ old('slug') }}">
                        @if ($errors->has('slug')) <p class="help-block">{{ $errors->first('slug') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                        <label for="description">Mod Description</label>
                        <textarea name="description" class="form-control" id="description">{{ old('descriptino') }}</textarea>
                        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('descriptino') }}</p> @endif
                    </div>
                    <hr />

                    <div class="form-group @if ($errors->has('author')) has-error @endif">
                        <label for="author">Mod Author(s)</label>
                        <input type="text" name="author" class="form-control" id="author" value="{{ old('author') }}">
                        @if ($errors->has('author')) <p class="help-block">{{ $errors->first('author') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('link')) has-error @endif">
                        <label for="link">Mod Link</label>
                        <input type="text" name="link" class="form-control" id="link" value="{{ old('link') }}">
                        @if ($errors->has('link')) <p class="help-block">{{ $errors->first('link') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('donatelink')) has-error @endif">
                        <label for="donatelink">Mod Donations Link</label>
                        <input type="text" name="donatelink" class="form-control" id="donatelink" value="{{ old('donatelink') }}">
                        @if ($errors->has('donatelink')) <p class="help-block">{{ $errors->first('donatelink') }}</p> @endif
                    </div>

                </div>
                <div class="col-md-6">
                    <h3>Resource Management</h3>
                    <p>Upload your mod resources here. These files are what will be served to the launcher. If you don't have resources ready yet, no problem you can always upload them later.</p>
                    <hr />

                    <div class="form-group">
                        <label for="exampleInputFile">Mod Package</label>
                        <input type="file" id="modFile">
                        <p class="help-block">Must be a .zip, see *link* for more information</p>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr />
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-success">Create Mod</button>
                    <a href="{{ route('mod.index') }}" class="btn btn-primary">Go Back</a>
                </div>
            </div>
        </form>

    </div>
</div>

@stop

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#slug').slugify('#name');
    });
</script>
@stop
