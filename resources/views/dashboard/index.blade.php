@component('layouts.app')

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">Dashboard</h1>
                <h2 class="subtitle">Welcome to Solder</h2>
            </div>
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

                <!-- Summary -->
                <div class="column is-full-desktop is-full-mobile">
                    <div class="box">
                        @include('dashboard.summary')
                    </div>
                </div>

                <hr />

                <!-- Modpacks -->
                <div class="column is-half-desktop is-full-mobile">
                    @include('dashboard.modpacks')
                </div>

                <!-- Resources -->
                <div class="column is-half-desktop is-full-mobile">
                    @include('dashboard.resources')
                </div>

            </div>
        </div>
    </section>

@endcomponent
