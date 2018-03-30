<launcher-api-list-clients :clients="clients" inline-template>
    <div class="box" v-if="clients.length">
        <h1>Technic Launcher Clients</h1>
        <div class="box-body">
            <ul class="list-group">

                <li class="level list-group-item" v-for="client in clients">
                    <div class="level-left">
                        <div class="level-item" style="width:100px;">
                            <span class="icon is-large">
                                <i class="fa fa-2x fa-window-maximize"></i>
                            </span>
                        </div>
                        <div class="level-item">
                            <div class="content">
                                <p>
                                    <strong>@{{ client.title }}</strong><br>
                                    <strong>Client ID:</strong> <code>@{{ client.token }}</code><br>
                                    <small>Added on @{{ client.created_at | datetime }}</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="level-right">
                        <div class="level-item">
                            <button class="button is-danger is-outlined" @click="approveClientDelete(client)">Delete</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</launcher-api-list-clients>