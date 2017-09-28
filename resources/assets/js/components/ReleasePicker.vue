<template>
    <div class="field has-addons">
        <div class="control is-expanded">
            <div class="select is-fullwidth" :class="{ 'is-loading': loadingPackage }">
                <select name="package_id" v-model="selectedPackage">
                    <option v-for="package in packages" :value="package.id">
                        {{ package.name }}
                    </option>
                </select>
            </div>
        </div>
        <div class="control">
            <div class="select" :class="{ 'is-loading': loadingRelease }">
                <select name="release_id" v-model="selectedRelease">
                    <option v-for="release in releases" :value="release.id">
                        {{ release.version }}
                    </option>
                </select>
            </div>
        </div>
        <div class="control">
            <button type="submit" class="button is-primary">Add Package</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                packages: [],
                releases: [],
                loadingPackage: true,
                loadingRelease: true,
                selectedPackage: '',
                selectedRelease: ''
            }
        },

        mounted() {
            axios.get('/api/packages')
                .then((response) => {
                    this.packages = response.data.data;
                    this.selectedPackage = this.packages[0].id;
                    this.loadingPackage = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        watch: {
            selectedPackage(packageId) {
                this.releases = [];
                this.loadingRelease = true;
                axios.get('/api/packages/' + packageId + '?include=releases')
                    .then((response) => {
                        this.releases = response.data.data.releases.data;
                        this.selectedRelease = this.releases[0].id;
                        this.loadingRelease = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        }
    }
</script>
