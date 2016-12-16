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
            <tr v-for="version in versions">
                <td>
                    <a :href="link( 'mod', version.mod.id )">{{ version.mod.name }}</a>
                </td>
                <td>
                    <a :href="link( 'versions', version.id )">{{ version.version }}</a>
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-danger" v-on:click="destroy( version )">Delete</a>
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
                this.$http.get('/api/builds/' + this.buildId + '/versions?include=mod')
                    .then(response => {
                        this.versions = store.sync(JSON.parse(response.data));
                    });
            },

            destroy(mod) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover " + version.version,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function() {
                    this.$http.delete('/api/versions/' + version.id)
                        .then(response => {
                            swal("Deleted!", version.version + " has been deleted.", "success");
                            this.sync();
                        })
                }.bind(this));
            }
        },
    }
</script>
