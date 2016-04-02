@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Build Management</h1>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Edit Build ({{ $modpack->name }} - {{ $build->version }})</div>
        <div class="panel-body">
            <form method="post" action="{{ route('modpack.build.update', [$modpack->slug, $build->id]) }}">
                {{ method_field('patch') }}
                {{ csrf_field() }}
                <div class="row">
                    @include('builds.form');
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        <button type="submit" class="btn btn-success">Update Build</button>
                        <a href="{{ route('modpack.build.destroy', [$modpack->slug, $build->id]) }}" class="btn btn-danger">Delete Build</a>
                        <a href="{{ route('modpack.show', $modpack->slug) }}" class="btn btn-primary">Go Back</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
@stop
