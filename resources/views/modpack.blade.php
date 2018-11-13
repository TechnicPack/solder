@extends('layouts.app')

@section('content')
    <solder-modpack modpack-id="{{ request('modpack') }}"></solder-modpack>
@endsection