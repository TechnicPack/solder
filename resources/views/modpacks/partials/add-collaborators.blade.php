<div class="box">
    <h1>Add Collaborator</h1>
    <div class="box-body">
        <form action="/modpacks/{{ $modpack->slug }}/collaborators" method="post">
            {{ csrf_field() }}

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">User</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control">
                            <div class="select">
                                <select name="user_id">
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label">
                    &nbsp;
                </div>
                <div class="field-body">
                    <div class="control">
                        <button class="button is-primary" type="submit">Add Collaborator</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>