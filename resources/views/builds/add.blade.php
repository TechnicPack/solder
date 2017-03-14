@if( count($errors) )
    <div class="notification is-warning">
        <ul>
            @foreach($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel">
    <form method="post" action="{{ route('builds.store', $modpack->id) }}">
        <div class="control is-grouped">

            {{ csrf_field() }}

            <p class="control is-expanded">
                <input class="input" type="text" placeholder="Build Version" name="version" value="{{ old('version') }}">
            </p>

            <p class="control is-expanded">
                <input class="input" type="text" placeholder="Minecraft Version" name="game_version" value="{{ old('game_version') }}">
            </p>

            <p class="control">
                <span class="select">
                    <select name="privacy">
                        <option value="public">Public</option>
                        <option value="unlisted">Unilsted</option>
                        <option value="private">Private</option>
                    </select>
                </span>
            </p>

            <p class="control">
                <button class="button is-info">Add</button>
            </p>

        </div>
    </form>
</div>
