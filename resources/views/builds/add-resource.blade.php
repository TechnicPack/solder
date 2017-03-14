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
    <form method="post" action="#">
        <div class="control is-grouped">

            {{ csrf_field() }}

            <p class="control is-expanded">
                <input class="input" type="text" placeholder="Resource" name="resource">
            </p>

            <p class="control">
                <span class="select">
                    <select name="target" class="is-disabled">
                        <option value="0">version</option>
                    </select>
                </span>
            </p>

            <p class="control">
                <span class="select">
                    <select name="target" class="is-disabled">
                        <option value="universal">Universal</option>
                        <option value="client">Client</option>
                        <option value="server">Server</option>
                    </select>
                </span>
            </p>

            <p class="control">
                <button class="button is-info">Add</button>
            </p>

        </div>
    </form>
</div>
