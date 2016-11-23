<template>
    <div class="panel panel-default">
        <table class="table">
            <thead>
            <tr>
                <th>Modpack</th>
                <th>Version</th>
                <th>Updated</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="build in builds">
                <td>
                    <a :href="link( 'modpacks', build.modpack.id )">{{ build.modpack.name }}</a>
                </td>
                <td>
                    <a :href="link( 'builds', build.id )">{{ build.version }}</a>
                </td>
                <td>
                    <timeago :since="build.updated_at"></timeago>
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" :href="link( 'builds', build.id )">Manage</a>
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
                builds: []
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
                this.$http.get('/api/builds?include=modpack&sort=-updated_on')
                    .then(response => {
                        this.builds = store.sync(JSON.parse(response.data));
                    });
            }
        },
    }
</script>
