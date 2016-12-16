<template>
    <div class="panel panel-default">
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Author</th>
                <th>&nbsp;</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="resource in resources">
                <td>
                    <a :href="link( 'resources', resource.id )">{{ resource.name }}</a>
                </td>
                <td>
                    {{ resource.author }}
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" :href="link( 'resources', resource.id )">Manage</a>
                    <a class="btn btn-xs btn-danger" v-on:click="destroy( resource )">Delete</a>
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
                resources: []
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
                this.$http.get('/api/resources')
                    .then(response => {
                        this.resources = store.sync(JSON.parse(response.data));
                    });
            },

            destroy(resource) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover " + resource.name,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function() {
                    this.$http.delete('/api/resources/' + resource.id)
                        .then(response => {
                            swal("Deleted!", resource.name + " has been deleted.", "success");
                            this.sync();
                        })
                }.bind(this));
            }
        },
    }
</script>
