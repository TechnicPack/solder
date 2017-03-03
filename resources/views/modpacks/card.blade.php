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
            <div class="content modpack-card__details">
                <div class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <i class="fa fa-fw fa-star-o"></i> v1.3.12
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item">
                            <i class="fa fa-fw fa-{{ $modpack->privacy }}"></i> {{ $modpack->privacy }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="card-footer">
            <a href="{{ route('modpacks.edit', $modpack->id) }}"
               class="card-footer-item modpack-card__action">
                <i class="fa fa-fw fa-pencil"></i> Edit
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
                <i class="fa fa-fw fa-trash"></i> Delete
            </a>
        </footer>

    </div>
</div>
