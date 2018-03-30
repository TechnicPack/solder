<launcher-api-create-client inline-template>
    <div class="box">
        <h1>Add Technic Client</h1>
        <div class="box-body">
            <form method="post" @submit.prevent="createClient()">
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Title</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" :class="{'is-danger': form.errors.has('title')}" type="text" placeholder="My Computer" v-model="form.title">
                                <p class="help is-danger" v-show="form.errors.has('title')">@{{ form.errors.get('title') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Client ID</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" :class="{'is-danger': form.errors.has('token')}" type="text" placeholder="89085904-f97e-4f44-b340-2f42532d5179" v-model="form.token">
                                <p class="help is-danger" v-show="form.errors.has('token')">@{{ form.errors.get('token') }}</p>
                                <p class="help">You can find the Client ID by going to 'Launcher Options' in Technic Launcher.</p>
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
                            <button class="button is-primary" type="submit">Add Launcher Client</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</launcher-api-create-client>