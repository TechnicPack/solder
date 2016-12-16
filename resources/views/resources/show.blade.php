@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="page-header">
            <h1>Resource</h1>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <h2>{{ $resource->name }}</h2>

                <ul class="list-inline">
                    <li><i class="fa fa-fw fa-home" aria-hidden="true"></i> <a href="{{ $resource->link }}">{{ $resource->link }}</a></li>
                    <li><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i> Created {{ $resource->created_at }}</li>
                    <li><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i> Updated {{ $resource->updated_at }}</li>
                    <li><i class="fa fa-fw fa-male" aria-hidden="true"></i> {{ $resource->author }}</li>
                </ul>

                <div class="description">
                    {!! Markdown::convertToHtml($resource->description) !!}
                </div>
            </div>
        </div>

        <h2>Releases</h2>
        <resource-versions resource-id="{{ $resource->id }}"></resource-versions>

    </div>
</div>
@endsection
