@extends('layouts.app')

@section('content')
    <solder-modpack-build modpack-id="{{ request('modpack') }}" build-id="{{ request('build') }}"></solder-modpack-build>
@endsection