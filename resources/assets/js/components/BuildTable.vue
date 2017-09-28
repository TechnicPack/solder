<template>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Name</th>
            <th>Version</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tfoot v-if="rows.length == 0">
        <tr>
            <td colspan="4" class="has-text-centered">There are no included packages, get started by bundling one.</td>
        </tr>
        </tfoot>
        <tbody>
        <tr v-for="row in rows">
            <td>{{ row.package.name }}</td>
            <td>{{ row.version }}</td>
            <td class="has-text-right">
                <a @click="destroy(row)" class="button is-small is-outlined is-danger">Remove</a>
            </td>
        </tr>
        </tbody>

    </table>
</template>

<script>
    export default{
        props: ['releases'],
        data(){
            return{
                rows: []
            }
        },
        mounted() {
            this.rows = this.releases;
        },
        methods: {
            destroy: function(row) {
                axios.delete('/bundles', {
                    params: {
                        build_id: row.pivot.build_id,
                        release_id: row.pivot.release_id
                    }
                })
                .then((response) => {
                    this.rows.splice(this.rows.indexOf(row), 1)
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
