<template>
    <div>
        <!-- Create Token Form -->
        <div class="box">
            <h1>Create OAuth Client</h1>
            <div class="box-body">
                <form class="form-horizontal" role="form" @submit.prevent="store()">

                    <!-- Errors -->
                    <div class="field is-horizontal" v-if="createForm.errors.length > 0">
                        <div class="field-label is-normal">
                            &nbsp;
                        </div>
                        <div class="field-body">
                            <div class="notification is-danger">
                                <ul>
                                    <li v-for="error in createForm.errors">
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
                                    <input id="create-client-name" type="text" class="input" v-model="createForm.name">
                                </div>
                                <p class="help">Something your users will recognize and trust.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Redirect URL -->
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Redirect URL</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input type="text" class="input" name="redirect" v-model="createForm.redirect">
                                </div>
                                <p class="help">Your application's authorization callback URL.</p>
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
                                <button class="button is-primary" type="submit">Create Client</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Show Tokens Table -->
        <div class="box"  v-if="clients.length > 0">
            <h1>OAuth Clients</h1>
            <div class="box-body">
                <ul class="list-group">
                    <li class="list-group-item" v-for="client in clients">
                        <div class="level">
                            <div class="level-left">
                                <div class="level-item" style="width:100px;">
                                <span class="icon is-large">
                                    <i class="fa fa-exchange fa-2x"></i>
                                </span>
                                </div>
                                <div class="level-item">
                                    <div class="content">
                                        <p>
                                            <strong>{{ client.name }}</strong><br >
                                            Client ID: <code>{{ client.id }}</code><br >
                                            Client Secret: <code>{{ client.secret }}</code>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="level-right">
                                <div class="level-item">
                                    <div class="field is-grouped">
                                        <p class="control">
                                            <a class="button is-primary is-outlined" @click="edit(client)">Edit</a>
                                        </p>
                                        <p class="control">
                                            <a class="button is-danger is-outlined" @click="destroy(client)">Delete</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Edit Client Modal -->
        <div class="modal fade" id="modal-edit-client" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Edit Client
                        </h4>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                            <p><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in editForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Edit Client Form -->
                        <form class="form-horizontal" role="form">
                            <!-- Name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>

                                <div class="col-md-7">
                                    <input id="edit-client-name" type="text" class="form-control"
                                                                @keyup.enter="update" v-model="editForm.name">

                                    <span class="help-block">
                                        Something your users will recognize and trust.
                                    </span>
                                </div>
                            </div>

                            <!-- Redirect URL -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Redirect URL</label>

                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="redirect"
                                                    @keyup.enter="update" v-model="editForm.redirect">

                                    <span class="help-block">
                                        Your application's authorization callback URL.
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="update">
                            Save Changes
                        </button>
                    </div>
                </div>
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
                clients: [],

                createForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                },

                editForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                }
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
                this.getClients();
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                        .then(response => {
                            this.clients = response.data;
                        });
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient('post', '/oauth/clients', this.createForm);
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.editForm.id = client.id;
                this.editForm.name = client.name;
                this.editForm.redirect = client.redirect;

                // $('#modal-edit-client').modal('show');
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient('put', '/oauth/clients/' + this.editForm.id, this.editForm);
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getClients();

                        form.name = '';
                        form.redirect = '';
                        form.errors = [];
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                axios.delete('/oauth/clients/' + client.id)
                        .then(response => {
                            this.getClients();
                        });
            }
        }
    }
</script>
