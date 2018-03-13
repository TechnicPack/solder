@extends('layouts.app')

@section('content')
    <assistant id="dashboard" v-cloak>
        Welcome to your Solder your Dashboard. From here you can create modpacks
        and check on recent activity. If you're just getting started, use the
        form below to create your first modpack.
    </assistant>

    <section class="section">
        @can('create', App\Modpack::class)
            @include('dashboard.partials.create-modpack')
        @endcan

        @includeWhen(count($builds), 'dashboard.partials.recent-builds')
        @includeWhen(count($releases), 'dashboard.partials.recent-releases')
    </section>
@endsection
