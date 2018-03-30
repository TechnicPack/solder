<launcher-api-list-keys :keys="keys" inline-template>
    <div class="box" v-if="keys.length">
        <h1>Active Keys</h1>
        <div class="box-body">
            <ul class="list-group">

                <li class="level list-group-item" v-for="key in keys">
                    <div class="level-left">
                        <div class="level-item" style="width:100px;">
                        <span class="icon is-large">
                            <i class="fa fa-key fa-2x"></i>
                        </span>
                        </div>
                        <div class="level-item">
                            <div class="content">
                                <p>
                                    <strong>@{{ key.name }}</strong><br>
                                    <strong>Token:</strong> @{{ key.token }}<br>
                                    Added on @{{ key.created_at | datetime }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="level-right">
                        <div class="level-item">
                            <button class="button is-danger is-outlined" @click="approveKeyDelete(key)">Delete</button>
                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</launcher-api-list-keys>