<div class="box">
    <h1>Packages</h1>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Name</th>
            <th>Author</th>
            <th>Links</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($packages as $package)
            <tr>
                <td>
                    <a href="/library/{{ $package->slug }}">
                        <strong>{{ $package->name }}</strong>
                    </a>
                </td>
                <td>{{ $package->author }}</td>
                <td>
                    <div class="field has-addons">
                        <p class="control">
                            <a href="{{ $package->website_url }}" class="button is-small" {{ $package->website_url == null ? 'disabled' : '' }}>
                              <span class="icon is-small">
                                <i class="fa fa-external-link"></i>
                              </span>
                            </a>
                        </p>
                        <p class="control">
                            <a href="{{ $package->donation_url }}" class="button is-small" {{ $package->donation_url == null ? 'disabled' : '' }}>
                              <span class="icon is-small">
                                <i class="fa fa-usd"></i>
                              </span>
                            </a>
                        </p>
                    </div>
                </td>
                <td class="has-text-right">
                    <form method="post" action="/library/{{ $package->slug }}">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="button is-danger is-small is-outlined">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
