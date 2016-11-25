<template>
    <div class="panel panel-default">
        <table class="table">
            <thead>
            <tr>
                <th>Version</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="build in builds">
                <td>
                    <a :href="link( 'builds', build.id )">{{ build.version }}</a>
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" :href="link( 'builds', build.id )">Manage</a>
                    <a class="btn btn-xs btn-danger" v-on:click="destroy( build )">Delete</a>
                </td>
            </tr>
            <tbody>

        </table>
    </div>
</template>

<script>
    export default {
        props: ['modpackId'],

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
                this.$http.get('/api/modpacks/' + this.modpackId + '/builds')
                    .then(response => {
                        this.builds = store.sync(JSON.parse(response.data));
                    });
            },

            destroy(build) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover " + build.version,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function() {
                    this.$http.delete('/api/builds/' + build.id)
                        .then(response => {
                            swal("Deleted!", build.version + " has been deleted.", "success");
                            this.sync();
                        })
                }.bind(this));
            }
        },
    }
</script>
