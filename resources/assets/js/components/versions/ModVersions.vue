<template>
    <div class="panel panel-default">
        <table class="table">
            <thead>
            <tr>
                <th>Version</th>
                <th>Created</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="version in versions">
                <td>
                    <a :href="link( 'versions', version.id )">{{ version.version }}</a>
                </td>
                <td>
                    <timeago :since="version.created_at"></timeago>
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" :href="link( 'versions', version.id )">Manage</a>
                    <a class="btn btn-xs btn-danger" v-on:click="destroy( version.id )">Delete</a>
                </td>
            </tr>
            <tbody>

        </table>
    </div>
</template>

<script>
    export default {
        props: ['modId'],

        data() {
            return {
                versions: []
            };
        },

        mounted() {
            this.sync();
        },

        methods: {
            link(endpoint, id) {
                return "/" + endpoint + "/" + id;
            },

            sync() {
                this.$http.get('/api/mods/' + this.modId + '/versions')
                    .then(response => {
                        this.versions = store.sync(JSON.parse(response.data));
                    });
            },

            destroy(id) {
                this.$http.delete('/api/versions/' + id)
                    .then(response => {
                        this.sync();
                    });
            }
        },
    }
</script>
