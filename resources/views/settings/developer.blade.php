@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <passport-clients class="mb-4"></passport-clients>
        <passport-authorized-clients class="mb-4"></passport-authorized-clients>
        <passport-personal-access-tokens></passport-personal-access-tokens>
    </div>
@endsection
