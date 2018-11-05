<template>
    <div class="mb-4">
        <div class="card card-default">
            <div class="card-header card-header-light d-flex align-items-center justify-content-between">
                <h3 class="mb-0">Modpacks</h3>
                <button type="button" class="btn btn-primary">Create Modpack</button>
            </div>

            <table class="table table-hover mb-0">
                <thead class="thead-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="modpack in modpacks" :key="modpack.id">
                    <th scope="row" class="align-middle">
                        <img :src="modpack.icon" class="img-thumbnail" width="50px" height="50px"/>
                        {{ modpack.name }}
                    </th>
                    <td class="align-middle">
                        {{ modpack.slug }}
                    </td>
                    <td class="text-right align-middle">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-link">View</button>
                            <button type="button" class="btn btn-link">Edit</button>
                            <button type="button" class="btn btn-link">Delete</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                modpacks: []
            }
        },


        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },


        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getModpacks();
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getModpacks() {
                axios.get(`${this.$hostname}/modpacks`)
                    .then(response => {
                        this.modpacks = response.data;
                    });
            },
        }
    }
</script>
