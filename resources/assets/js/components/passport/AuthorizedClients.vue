<template>
    <div>

        <div class="box"  v-if="tokens.length > 0">
            <h1>Authorized Applications</h1>
            <div class="box-body">
                <ul class="list-group">
                    <li class="list-group-item" v-for="token in tokens">
                        <div class="level">
                            <div class="level-left">
                                <div class="level-item" style="width:100px;">
                                <span class="icon is-large">
                                    <i class="fa fa-id-badge fa-2x"></i>
                                </span>
                                </div>
                                <div class="level-item">
                                    <div class="content">
                                        <p>
                                            <strong>{{ token.client.name }}</strong>
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
                                    <button class="button is-danger is-outlined" @click="revoke(token)">Revoke</button>
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
                tokens: []
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
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {
                this.getTokens();
            },

            /**
             * Get all of the authorized tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/tokens')
                        .then(response => {
                            this.tokens = response.data;
                        });
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/tokens/' + token.id)
                        .then(response => {
                            this.getTokens();
                        });
            }
        }
    }
</script>
