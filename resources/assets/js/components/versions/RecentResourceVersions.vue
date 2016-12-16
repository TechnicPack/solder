<template>
    <div class="panel panel-default">
        <table class="table">
            <thead>
            <tr>
                <th>Resource</th>
                <th>Version</th>
                <th>Created</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="version in versions">
                <td>
                    <a :href="link( 'mods', version.mod.id )">{{ version.mod.name }}</a>
                </td>
                <td>
                    <a :href="link( 'versions', version.id )">{{ version.version }}</a>
                </td>
                <td>
                    <timeago :since="version.created_at"></timeago>
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" :href="link( 'versions', version.id )">Manage</a>
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
                this.$http.get('/api/versions?include=mod&sort=-created_at')
                    .then(response => {
                        this.versions = store.sync(JSON.parse(response.data));
                    });
            }
        },
    }
</script>
