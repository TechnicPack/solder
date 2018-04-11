@extends('layouts.app')

@section('content')
    <assistant id="releases" v-cloak>
        This is the place for uploading new releases of a package, and managing
        the files for your library. The file structure is very important so before
        you upload anything take a minute to read about the
        <a href="http://docs.solder.io/docs/zip-file-structure">zip file structure</a>
        in the docs.
    </assistant>

    <section class="section">

        <div class="level">
            <div class="level-left"></div>
            <div class="level-right">
                <div class="level-item">{{ $package->name }}</div>
            </div>
        </div>

        @can('create', App\Release::class)
            <add-release :slug='{{ json_encode($package->slug) }}'></add-release>
        @endcan

        <release-table :slug='{{ json_encode($package->slug) }}' :releases='{{ json_encode($package->releases) }}'></release-table>
        
        @can('update', $package)
            @include('packages.partials.update-package')
            @include('packages.partials.danger-zone')
        @endcan
    </section>
@endsection
