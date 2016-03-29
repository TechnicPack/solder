@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Modpack Management - {{ $modpack->name }}</h1>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Editing Modpack: {{ $modpack->name }}</div>
    <div class="panel-body">
        <form method="post" action="{{ route('modpack.update', $modpack->slug) }}">
            <div class="row">

                <div class="col-md-6">
                    <h3>Modpack Management</h3>
                    <p>Edit your modpack settings here. You are required to delete and re-import your pack on the Technic Platform when changing the Modpack slug</p>
                    <hr />

                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        <label for="name">Modpack Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $modpack->name) }}">
                        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('slug')) has-error @endif">
                        <label for="slug">Modpack Slug</label>
                        <input type="text" name="slug" class="form-control" id="slug" value="{{ old('slug', $modpack->slug) }}">
                        @if ($errors->has('slug')) <p class="help-block">{{ $errors->first('slug') }}</p> @endif
                    </div>
                    <hr />

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="hidden" {{ $modpack->hidden ? 'checked="checked"' : '' }}> Hide Modpack
                        </label>
                    </div>

                    <p>Hidden modpacks will not show up in the API response for the modpack list regardless of whether or not a client has access to the modpack.</p>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="private" {{ $modpack->private ? 'checked="checked"' : '' }}> Private Modpack
                        </label>
                    </div>

                    <p>Private modpacks will only be available to clients that are linked to this modpack. You can link clients below. You can also individually mark builds as private.</p>

                </div>
                <div class="col-md-6">
                    <h3>Image Management</h3>
                    <p>Upload your modpacks resources here. These images are what will be served to the launcher. If your modpack already has images on your mirror, they will remain working until the first time you upload them here.</p>
                    <hr />

                    <div class="form-group">
                        <label for="exampleInputFile">Modpack Background</label>
                        @if (!empty($modpack->background_url))
                        <img src="{{ $modpack->background_url }}" class="img-responsive" alt="Modpack Background">
                        @endif
                        <input type="file" id="exampleInputFile">
                        <p class="help-block">Required Size: 900x600</p>
                    </div>
                    <hr />

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="exampleInputFile">Modpack Icon</label>
                            @if (!empty($modpack->icon_url))
                            <img src="{{ $modpack->icon_url }}" class="img-responsive" alt="Modpack Icon">
                            @endif
                            <input type="file" id="exampleInputFile">
                            <p class="help-block">Recommended Size: 50x50</p>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputFile">Modpack Logo</label>
                            @if (!empty($modpack->background_url))
                            <img src="{{ $modpack->background_url }}" class="img-responsive" alt="Modpack Logo">
                            @endif
                            <input type="file" id="exampleInputFile">
                            <p class="help-block">Required Size: 370x220</p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr />
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-success">Save Modpack</button>
                    <button type="button" class="btn btn-danger">Delete Modpack</button>
                    <a href="{{ route('modpack.index') }}" class="btn btn-primary">Go Back</a>
                </div>
            </div>
        </form>

    </div>
</div>
@stop
