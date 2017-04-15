<template>
    <div>
        <p v-if="modpacks.length === 0">
            You have not created any Builds.
        </p>

        <table v-if="modpacks.length > 0" class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th>&nbsp</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="modpack in modpacks">
                <!-- Name -->
                <th style="vertical-align: middle;">
                    {{ modpack.name }}
                </th>

                <!-- Slug -->
                <td style="vertical-align: middle;">
                    {{ modpack.slug }}
                </td>

                <!-- Status -->
                <td style="vertical-align: middle;">
                    {{ modpack.status }}
                </td>

                <!-- Actions -->
                <td style="vertical-align: middle; text-align: right">
                    <!--<a :href="'/modpacks/' + modpackId + '/builds/' + build.id" class="button is-outlined is-primary">Manage</a>-->
                    <!--<a href="#" class="button is-outlined is-disabled">Duplicate</a>-->
                    <!--<a href="#" class="button is-outlined is-disabled">Settings</a>-->
                    <a @click="destroy(modpack)" class="button is-outlined is-danger">Delete</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default{
        /*
         * The component's data.
         */
        data(){
            return{
                modpacks: []
            }
        },

        /**
         * Prepare the component.
         */
        mounted() {
            solderApi.loadAll("modpacks", { include: "builds" }).subscribe((modpacks) => {
                this.modpacks = modpacks;
            });
        },

        methods: {
            destroy(modpack) {
                solderApi.destroy("modpacks", modpack.id).subscribe(() => {
                    console.log("Destroyed!");
                });
            }
        }
    }
</script>
