<template>
    <div class="panel panel-default">
        <table class="table">
            <thead>
            <tr>
                <th>Mod</th>
                <th>Version</th>
                <th>Created</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="release in releases">
                <td>
                    <a :href="link( 'mods', release.mod.id )">{{ release.mod.name }}</a>
                </td>
                <td>
                    <a :href="link( 'releases', release.id )">{{ release.version }}</a>
                </td>
                <td>
                    <timeago :since="release.created_at"></timeago>
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" :href="link( 'releases', release.id )">Manage</a>
                </td>
            </tr>
            <tbody>

        </table>
    </div>
</template>

<script>
    export default {
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
                this.$http.get('/api/releases?include=mod&sort=-created_at')
                    .then(response => {
                        this.releases = store.sync(JSON.parse(response.data));
                    });
            }
        },
    }
</script>
