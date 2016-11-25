<template>
    <div class="panel panel-default">
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Promoted</th>
                <th>Latest</th>
                <th>Status</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="modpack in modpacks">
                <td>
                    <a :href="link( 'modpacks', modpack.id )">{{ modpack.name }}</a>
                </td>
                <td>
                    <a v-if="modpack.latest != null" class="badge" :href="link( 'releases', modpack.latest.id )">{{ modpack.promoted.version }}</a>
                </td>
                <td>
                    <a v-if="modpack.latest != null" class="badge" :href="link( 'releases', modpack.latest.id )">{{ modpack.latest.version }}</a>
                </td>
                <td>
                    <span v-if="modpack.published" class="label label-primary">Published</span>
                    <span v-else class="label label-default">Private</span>
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" :href="link( 'modpacks', modpack.id )">Manage</a>
                    <a class="btn btn-xs btn-danger" v-on:click="destroy( modpack )">Delete</a>
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
                modpacks: []
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
                this.$http.get('/api/modpacks?include=latest,promoted')
                    .then(response => {
                        this.modpacks = store.sync(JSON.parse(response.data));
                    });
            },

            destroy(modpack) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover " + modpack.name,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function() {
                    this.$http.delete('/api/modpacks/' + modpack.id)
                        .then(response => {
                            swal("Deleted!", modpack.name + " has been deleted.", "success");
                            this.sync();
                        })
                }.bind(this));
            }
        },
    }
</script>
