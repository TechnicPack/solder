<template>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Name</th>
            <th>Version</th>
            <th>Filesize</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row in rows">
            <td>{{ row.package.name }}</td>
            <td>{{ row.version }}</td>
            <td>{{ row.filesize | prettyBytes }}</td>
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
