<template>
    <div>
        <!-- Create Token Form -->
        <div class="box">
            <h1>Create Personal Access Token</h1>
            <div class="box-body">

                <form role="form" @submit.prevent="store">

                    <!-- Errors -->
                    <div class="field is-horizontal" v-if="form.errors.length > 0">
                        <div class="field-label is-normal">
                            &nbsp;
                        </div>
                        <div class="field-body">
                            <div class="notification is-danger">
                                <ul>
                                    <li v-for="error in form.errors">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Name</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input id="create-token-name" type="text" class="input" name="name" v-model="form.name">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scopes -->
                    <div class="field is-horizontal" v-if="scopes.length > 0">
                        <div class="field-label is-normal">
                            <label class="label">Scopes</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control" v-for="scope in scopes" >
                                    <label>
                                        <input type="checkbox"
                                               @click="toggleScope(scope.id)"
                                               :checked="scopeIsAssigned(scope.id)">

                                        {{ scope.id }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="field is-horizontal">
                        <div class="field-label">
                            &nbsp;
                        </div>
                        <div class="field-body">
                            <div class="control">
                                <button class="button is-primary" type="submit">Create Token</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notification Content -->
        <div class="is-hidden">
            <div ref="accessToken" class="content">
                <p>
                    Here is your new personal access token.
                    This is the only time it will be shown so don't lose it!
                    You may now use this token to make API requests.
                </p>

                <p class="code is-wrapped has-text-left">{{ accessToken }}</p>
            </div>
        </div>

        <!-- Show Tokens Table -->
        <div class="box"  v-if="tokens.length > 0">
            <h1>Personal Access Tokens</h1>
            <div class="box-body">
                <ul class="list-group">
                    <li class="list-group-item" v-for="token in tokens">
                        <div class="level">
                            <div class="level-left">
                                <div class="level-item" style="width:100px;">
                                <span class="icon is-large">
                                    <i class="fa fa-user-circle-o fa-2x"></i>
                                </span>
                                </div>
                                <div class="level-item">
                                    <div class="content">
                                        <p>
                                            <strong>{{ token.name }}</strong>
                                        </p>
                                        <div class="tags" v-if="token.scopes.length > 0">
                                            <span class="tag" v-for="scope in token.scopes">
                                                {{ scope }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="level-right">
                                <div class="level-item">
                                    <button class="button is-danger is-outlined" @click="revoke(token)">Delete</button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                accessToken: null,

                tokens: [],
                scopes: [],

                form: {
                    name: '',
                    scopes: [],
                    errors: []
                },
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getTokens();
                this.getScopes();
            },

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/personal-access-tokens')
                        .then(response => {
                            this.tokens = response.data;
                        });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                axios.get('/oauth/scopes')
                        .then(response => {
                            this.scopes = response.data;
                        });
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.accessToken = null;

                this.form.errors = [];

                axios.post('/oauth/personal-access-tokens', this.form)
                        .then(response => {
                            this.form.name = '';
                            this.form.scopes = [];
                            this.form.errors = [];

                            this.tokens.push(response.data.token);

                            this.showAccessToken(response.data.accessToken);
                        })
                        .catch(error => {
                            if (typeof error.response.data === 'object') {
                                this.form.errors = _.flatten(_.toArray(error.response.data));
                            } else {
                                this.form.errors = ['Something went wrong. Please try again.'];
                            }
                        });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if (this.scopeIsAssigned(scope)) {
                    this.form.scopes = _.reject(this.form.scopes, s => s == scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return _.indexOf(this.form.scopes, scope) >= 0;
            },

            /**
             * Show the given access token to the user.
             */
            showAccessToken(accessToken) {
                this.accessToken = accessToken;

                swal({
                    title: "Personal Access Token",
                    content: this.$refs.accessToken,
                    icon: "success"
                });
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/personal-access-tokens/' + token.id)
                        .then(response => {
                            this.getTokens();
                        });
            }
        }
    }
</script>
