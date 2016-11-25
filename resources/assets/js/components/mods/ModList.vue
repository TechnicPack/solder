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
            <tr v-for="mod in mods">
                <td>
                    <a :href="link( 'mods', mod.id )">{{ mod.name }}</a>
                </td>
                <td>
                    {{ mod.author }}
                </td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" :href="link( 'mods', mod.id )">Manage</a>
                    <a class="btn btn-xs btn-danger" v-on:click="destroy( mod )">Delete</a>
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
                mods: []
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
                this.$http.get('/api/mods')
                    .then(response => {
                        this.mods = store.sync(JSON.parse(response.data));
                    });
            },

            destroy(mod) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover " + mod.name,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function() {
                    this.$http.delete('/api/mods/' + mod.id)
                        .then(response => {
                            swal("Deleted!", mod.name + " has been deleted.", "success");
                            this.sync();
                        })
                }.bind(this));
            }
        },
    }
</script>
