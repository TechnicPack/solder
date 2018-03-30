<launcher-api-create-key inline-template>
    <div class="box">
        <h1>Add Key</h1>
        <div class="box-body">
            <form @submit.prevent="createKey()">
                {{ csrf_field() }}
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        &nbsp;
                    </div>
                    <div class="field-body">
                        <div class="notification">
                            Keys provide the bearer with access to <strong>all private modpacks and builds</strong> but they only operate on the legacy api endpoints. Grant this sparingly.
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" :class="{'is-danger': form.errors.has('name')}" type="text" placeholder="Technicpack.net" v-model="form.name">
                                <p class="help is-danger" v-show="form.errors.has('name')">@{{ form.errors.get('name') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Token</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" :class="{'is-danger': form.errors.has('token')}" type="text" v-model="form.token">
                                <p class="help is-danger" v-show="form.errors.has('token')">@{{ form.errors.get('token') }}</p>
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
                            <button class="button is-primary" type="submit">Add Key</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</launcher-api-create-key>