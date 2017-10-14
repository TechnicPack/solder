@extends('layouts.app')

@section('content')

    @component('components.assistant')
        This is the place for uploading new releases of a package, and managing
        the files for your library. The file structure is very important so before
        you upload anything take a minute to read about the
        <a href="http://docs.solder.io/docs/zip-file-structure">zip file structure</a>
        in the docs.
    @endcomponent

    <section class="section">

        <div class="level">
            <div class="level-left"></div>
            <div class="level-right">
                <div class="level-item">{{ $package->name }}</div>
            </div>
        </div>

        @include('packages.partials.create-release')

        @if(count($package->releases))
            <release-table :releases='{{ json_encode($package->releases) }}'></release-table>
        @endif

        @include('packages.partials.update-package')
        @include('packages.partials.danger-zone')
    </section>
@endsection
