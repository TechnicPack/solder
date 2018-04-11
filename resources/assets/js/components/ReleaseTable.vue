<template>
    <div class="box">
        <h1>Releases</h1>
        <table class="table is-fullwidth">
            <thead>
            <tr>
                <th>Version</th>
                <th>MD5</th>
                <th>Filesize</th>
                <th>Download</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tfoot v-if="releases.length == 0">
            <tr>
                <td colspan="4" class="has-text-centered">There are no releases, get started by uploading one.</td>
            </tr>
            </tfoot>
            <tbody>
            <tr v-for="release in releases">

                <td>{{ release.version }}</td>
                <td><code>{{ release.md5 }}</code>
                </td>
                <td>{{ release.filesize | prettyBytes }}</td>
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
    </div>
</template>

<script>
    export default{
        props: ['slug'],
        data(){
            return{
                releases: []
            }
        },
        mounted() {
            var vm = this;
            this.$root.$on('addReleaseEvent', function() {
                vm.get();
            });
            vm.get();
        },
        methods: {
            get: function() {
                axios.get('/releases/' + this.slug)
                .then((response) => {
                    this.releases = response.data;
                });
            },
            destroy: function(release) {
                axios.delete('/releases/' + release.id)
                .then((response) => {
                    this.releases.splice(this.releases.indexOf(release), 1)
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
