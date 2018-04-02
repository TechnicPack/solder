<create-team inline-template>
    <div class="box">
        <h1>Add Team</h1>
        <div class="box-body">
            <form @submit.prevent="createTeam()">

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" :class="{'is-danger': form.errors.has('name')}" type="text" v-model="form.name">
                                <p class="help is-danger" v-show="form.errors.has('name')">@{{ form.errors.get('name') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Slug</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" :class="{'is-danger': form.errors.has('slug')}" type="text" v-model="form.slug">
                                <p class="help is-danger" v-show="form.errors.has('slug')">@{{ form.errors.get('slug') }}</p>
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
                            <button class="button is-primary" type="submit">Add Team</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</create-team>