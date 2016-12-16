@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="page-header">
            <h1>Modpack</h1>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <h2>{{ $mod->name }}</h2>

                <ul class="list-inline">
                    <li><i class="fa fa-fw fa-home" aria-hidden="true"></i> <a href="{{ $mod->link }}">{{ $mod->link }}</a></li>
                    <li><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i> Created {{ $mod->created_at }}</li>
                    <li><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i> Updated {{ $mod->updated_at }}</li>
                    <li><i class="fa fa-fw fa-male" aria-hidden="true"></i> {{ $mod->author }}</li>
                </ul>

                <div class="description">
                    {!! Markdown::convertToHtml($mod->description) !!}
                </div>
            </div>
        </div>

        <h2>Releases</h2>
        <mod-versions mod-id="{{ $mod->id }}"></mod-versions>

    </div>
</div>
@endsection
