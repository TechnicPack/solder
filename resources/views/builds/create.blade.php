@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Build Management</h1>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Create New Build ({{ $modpack->name }})</div>
        <div class="panel-body">
            <form method="post" action="{{ route('modpack.build.store', $modpack->slug) }}">
                {{ csrf_field() }}
                @include('builds.form');
                <div class="row">
                    <div class="col-md-12">
                        <hr/>
                        <button type="submit" class="btn btn-success">Add Build</button>
                        <a href="{{ route('modpack.show', $modpack->slug) }}" class="btn btn-primary">Go Back</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
@stop
