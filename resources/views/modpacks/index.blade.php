@component('layouts.app')

<section class="hero is-primary">
@include('layouts.nav')

<!-- Hero content: will be in the middle -->
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title">
                Modpacks
            </h1>
            <h2 class="subtitle">
                See all available modpacks here
            </h2>
        </div>
    </div>

    <!-- Hero footer: will stick at the bottom -->
    <div class="hero-foot">
        <nav class="tabs">
            <div class="container">
                <ul>
                    <li class="is-active"><a>Overview</a></li>
                    <li><a href="{{ route('modpacks.create') }}">Create new</a></li>
                </ul>
            </div>
        </nav>
    </div>
</section>

<section class="section">
    <div class="container">
        @if (session('status'))
            <div class="notification is-info">
                {{ session('status') }}
            </div>
        @endif
        <div class="columns is-mobile is-multiline">
            @foreach($modpacks as $modpack)
                <div class="column is-3">
                    <div class="card">
                        <a href="{{ route('modpacks.show', $modpack->id) }}" class="card-image">
                            <figure class="image is-3by2">
                                <img src="{{ $modpack->background }}">
                                <div class="is-overlay modpack-card__overlay">
                                    <img class="modpack-card__logo" src="{{ $modpack->logo }}">
                                </div>
                            </figure>
                        </a>
                        <div class="card-content">

                            <div class="content modpack-card__title">
                                {{ $modpack->name }}
                            </div>
                            <div class="content modpack-card__privacy">
                                <div>
                                    @if($modpack->privacy == 'public')
                                        <span class="icon"><i class="fa fa-globe"></i></span> <span>Public</span>
                                    @elseif($modpack->privacy == 'unlisted')
                                        <span class="icon"><i class="fa fa-eye-slash"></i></span> <span>Unlisted</span>
                                    @else
                                        <span class="icon"><i class="fa fa-lock"></i></span> <span>Private</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <footer class="card-footer">
                            <a href="{{ route('modpacks.edit', $modpack->id) }}"
                               class="card-footer-item modpack-card__action">
                                <span class="icon">
                                  <i class="fa fa-pencil"></i>
                                </span>
                                <span>Edit</span>
                            </a>
                            {{--This is up here to prvent ugly double borders--}}
                            <form id="modpack-{{ $modpack->slug }}"
                                  action="{{ route('modpacks.destroy', $modpack->id) }}" method="POST"
                                  style="display: none;">
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                            </form>
                            <a class="card-footer-item modpack-card__action is-danger"
                               onclick="event.preventDefault();document.getElementById('modpack-{{ $modpack->slug }}').submit();">
                                <span class="icon">
                                  <i class="fa fa-trash"></i>
                                </span>
                                <span>Delete</span>
                            </a>
                        </footer>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endcomponent
