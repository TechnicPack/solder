<aside class="directory">
    <div class="directory-brand">
        <figure class="directory-item is-64x64">
            <a href="/">
                <svg class="brand-image" height="64" width="64" viewBox="0 0 100 100">
                    <path d="M50.3,1.2C23.6,1.2,1.9,22.9,1.9,49.7c0,26.7,21.7,48.4,48.4,48.4c26.7,0,48.4-21.7,48.4-48.4
                  C98.7,22.9,77.1,1.2,50.3,1.2z M50.3,88.2c-21.3,0-38.5-17.3-38.5-38.5c0-21.3,17.3-38.5,38.5-38.5c21.3,0,38.5,17.3,38.5,38.5
                  C88.9,70.9,71.6,88.2,50.3,88.2z"></path>
                    <path d="M62.9,90.7l0.5-45.2l1.3-0.5l0.5-1.7l-14.8-3.7l-0.7,2.4c-0.8,0-2,0-3.4,0c0-6.6,0-12.3,0-12.3l0.6-0.7
                  h18.8v-3.6c0,0-6.4-8.5-18-8.5c-12,0-11.8,8.8-11.8,9.7c0,0,0,6.7,0,15.3c-1.5,0-4.4,1.1-4.4,5.2c0,3.8,2,4.8,1.7,8.4
                  c-0.4,4.2,0.8,4.9,2.7,5.4c0,0.9,0,1.6,0,2.5h-2.2v7.1h2.2c0,4.9,0,6.7,0,7.2c0,3.4,1.4,5.4,5,5.4c3.7,0,4.9-1.8,4.9-5.5
                  c0-0.3,0,0.2,0-0.8c1.4,0,4,1.6,4,3.9l0,1.8c0,3.5,0,4.6,0.1,8.2H62.9z M49.8,61.1c0,3.6,0,7.5,0.1,11.6h-4c0-0.7,0-1.4,0-2.2h2
                  v-7.1h-2c0-0.8,0-1.5,0-2.3C47.1,61.1,48.8,61.1,49.8,61.1z"></path>
                </svg>
            </a>
        </figure>
        <p class="is-size-7">{{ config('app.version') }}</p>
    </div>
    <div class="directory-menu">
        @foreach($directory as $modpackItem)
        <a class="directory-item {{ isset($modpack) && $modpack->id == $modpackItem->id ? 'is-active' : '' }}" href="/modpacks/{{ $modpackItem->slug }}">
            @if($modpackItem->icon_path)
            <figure class="image is-64x64">
                <img src="{{ $modpackItem->icon_url }}" />
            </figure>
            @else
                <abbr title="{{ $modpackItem->name }}">{{ $modpackItem->monogram }}</abbr>
            @endif
            <div class="popover">
                {{ $modpackItem->name }}
                <span class="icon has-text-{{ $modpackItem->status }}">
              <i class="fa fa-circle"></i>
            </span>
            </div>
        </a>
        @endforeach
    </div>
</aside>
