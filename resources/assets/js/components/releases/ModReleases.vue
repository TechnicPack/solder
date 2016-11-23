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
            <tr v-for="release in releases">
                <td>
                    <a :href="link( 'releases', release.id )">{{ release.version }}</a>
                </td>
                <td>
                    <timeago :since="release.created_at"></timeago>
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" :href="link( 'releases', release.id )">Manage</a>
                    <a class="btn btn-xs btn-danger" v-on:click="destroy( release.id )">Delete</a>
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
                releases: []
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
                this.$http.get('/api/mods/' + this.modId + '/releases')
                    .then(response => {
                        this.releases = store.sync(JSON.parse(response.data));
                    });
            },

            destroy(id) {
                this.$http.delete('/api/releases/' + id)
                    .then(response => {
                        this.sync();
                    });
            }
        },
    }
</script>
