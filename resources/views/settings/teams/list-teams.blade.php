<list-teams :teams="teams" inline-template>
    <div class="box" v-if="teams.length">
        <h1>Active Teams</h1>
        <div class="box-body">
            <ul class="list-group">

                <li class="level list-group-item" v-for="team in teams">
                    <div class="level-left">
                        <div class="level-item" style="width:100px;">
                        <span class="icon is-large">
                            <i class="fa fa-users fa-2x"></i>
                        </span>
                        </div>
                        <div class="level-item">
                            <div class="content">
                                <p>
                                    <strong>@{{ team.name }}</strong><br>
                                    <strong>slug:</strong> @{{ team.slug }}<br>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="level-right">
                        <div class="level-item">
                            <div class="field is-grouped">
                                <p class="control">
                                    <button class="button is-info is-outlined" @click="updateTeamName(team)">Edit</button>
                                </p>
                                <p class="control">
                                    <button class="button is-danger is-outlined" @click="approveTeamDelete(team)">Delete</button>
                                </p>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</list-teams>