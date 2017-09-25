@extends('layouts.app')

@section('menu')
    @include('partials.modpack-menu', ['modpack' => $modpack])
@endsection

@section('content')
    <section class="section">
        <nav class="level">
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Select a Build</p>
                </div>
            </div>
        </nav>
    </section>
@endsection
