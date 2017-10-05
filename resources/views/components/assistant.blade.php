@assistant
<section class="hero is-info">
    <div class="notification is-info is-radiusless">
        <a href="/settings/general" class="delete"></a>
        <nav class="columns">
            <div class="column is-narrow">
                <figure class="image is-64x64 is-pulled-left">
                    <img src="/img/book.png" />
                </figure>
            </div>
            <div class="column">
                {{ $slot }}
            </div>
        </nav>
    </div>
</section>
@endassistant
