@extends('layouts.app')

@section('content')
    <section class="section">
        <h1 class="title">New Release</h1>

        <form action="/releases" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Package</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="package_id">
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Release</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <p class="control is-expanded has-icons-left">
                            <input class="input" type="text" placeholder="Version" name="version">
                            <span class="icon is-small is-left">
                              <i class="fa fa-code-fork"></i>
                            </span>
                        </p>
                    </div>
                    <div class="file has-name">
                        <label class="file-label">
                            <input class="file-input" type="file" name="archive">
                            <span class="file-cta">
                              <span class="file-icon">
                                <i class="fa fa-upload"></i>
                              </span>
                              <span class="file-label">
                                Choose a file…
                              </span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label">
                    <!-- Left empty for spacing -->
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <button class="button is-primary">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
