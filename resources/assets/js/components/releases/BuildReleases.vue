<template>
    <div class="panel panel-default">
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Version</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="release in releases">
                <td>
                    <a :href="link( 'mod', release.mod.id )">{{ release.mod.name }}</a>
                </td>
                <td>
                    <a :href="link( 'releases', release.id )">{{ release.version }}</a>
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-danger" v-on:click="destroy( release )">Delete</a>
                </td>
            </tr>
            <tbody>

        </table>
    </div>
</template>

<script>
    export default {
        props: ['buildId'],

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
                this.$http.get('/api/builds/' + this.buildId + '/releases?include=mod')
                    .then(response => {
                        this.releases = store.sync(JSON.parse(response.data));
                    });
            },

            destroy(mod) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover " + release.version,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function() {
                    this.$http.delete('/api/releases/' + release.id)
                        .then(response => {
                            swal("Deleted!", release.version + " has been deleted.", "success");
                            this.sync();
                        })
                }.bind(this));
            }
        },
    }
</script>
