<div class="box">
    <h1>Collaborators</h1>
    <div class="box-body">
        <ul class="list-group">
            @foreach($modpack->collaborators as $collaborator)
                <li class="level list-group-item">
                    <div class="level-left">
                        <div class="level-item" style="width:100px;">
                            <span class="icon is-large">
                                <i class="fa fa-user-circle fa-2x"></i>
                            </span>
                        </div>
                        <div class="level-item">
                            <div class="content">
                                <p>
                                    <strong>{{ $collaborator->user->username }}</strong><br>
                                    <small>Added on {{ $collaborator->created_at }}</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="level-right">
                        <div class="level-item">
                            <div class="field is-grouped">
                                <form method="post" action="/collaborators/{{ $collaborator->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <button class="button is-danger is-outlined">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
