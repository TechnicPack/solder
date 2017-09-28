<template>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Version</th>
            <th>MD5</th>
            <th>Download</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tfoot v-if="rows.length == 0">
        <tr>
            <td colspan="4" class="has-text-centered">There are no releases, get started by uploading one.</td>
        </tr>
        </tfoot>
        <tbody>
        <tr v-for="release in rows">
            <td>{{ release.version }}</td>
            <td><small>{{ release.md5 }}</small></td>
            <td>
                <a :href="release.url">
                    {{ release.filename }}
                </a>
            </td>
            <td class="has-text-right">
                <a @click="destroy(release)" class="button is-small is-outlined is-danger">Remove</a>
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
            destroy: function(release) {
                axios.delete('/releases/' + release.id)
                .then((response) => {
                    this.rows.splice(this.rows.indexOf(release), 1)
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
