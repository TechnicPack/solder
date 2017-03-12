<template>
    <div>
        <p v-if="builds.length === 0">
            You have not created any Builds.
        </p>

        <table v-if="builds.length > 0" class="table">
            <thead>
            <tr>
                <th>Build</th>
                <th>For Minecraft</th>
                <th>Resources</th>
                <th>Created</th>
                <th>Privacy</th>
                <th>&nbsp</th>
            </tr>
            </thead>

            <tbody>
                <tr v-for="build in builds">
                    <!-- Version -->
                    <th style="vertical-align: middle;">
                        <a v-if="build.attributes.is_promoted == false" @click="promote(build)" style="color: inherit;" role="radio">
                            <i class="fa fa-fw fa-star-o"></i>
                        </a>

                        <i v-if="build.attributes.is_promoted" class="fa fa-fw fa-star" role="radio"></i>
                        {{ build.attributes.version }}
                    </th>

                    <!-- Game Version -->
                    <td style="vertical-align: middle;">
                        {{ build.attributes.game_version }}
                    </td>

                    <!-- Resource Count -->
                    <td style="vertical-align: middle;">
                        {{ build.attributes.resource_count }}
                    </td>

                    <!-- Created -->
                    <td style="vertical-align: middle;">
                        {{ build.attributes.created_at }}
                    </td>

                    <!-- Privacy -->
                    <td style="vertical-align: middle;">
                        {{ build.attributes.privacy }}
                    </td>

                    <!-- Actions -->
                    <td style="vertical-align: middle; text-align: right">
                        <a :href="'/modpacks/' + modpackId + '/builds/' + build.id" class="button is-outlined is-primary">Manage</a>
                        <a href="#" class="button is-outlined is-disabled">Duplicate</a>
                        <a href="#" class="button is-outlined is-disabled">Settings</a>
                        <a @click="destroy(build)" class="button is-outlined is-danger">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['modpackId'],

        /*
         * The component's data.
         */
        data() {
            return {
               builds: []
            }
        },

         /**
         * Prepare the component.
         */
        mounted() {
            this.getBuilds();
        },

        methods: {
            /**
             * Promote a build.
             */
            promote(build) {
                axios.post('/api/builds/'+build.id+'/promote')
                    .then(response => {
                        this.builds.map(function (item) {
                            item.attributes.is_promoted = item.id == build.id;
                        });
                    })
                    .catch(error => {
                        var firstError = error.response.data.errors[0]
                        swal(firstError.title, firstError.detail + "\n\nid: " +firstError.id, "error")
                    });
            },

            /**
             * Update a builds privacy.
             */
            updatePrivacy(build) {
                axios.patch('/api/builds/' + build.id, {
                        data: { build }
                    })
                    .then(response => {
                        this.getBuilds();
                    });
            },

            /**
             * Delete a build.
             */
            destroy(build) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this build!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#ff3860",
                    confirmButtonText: "Yes, delete it!",
                },
                function(){
                    axios.delete('/api/builds/' + build.id)
                         .then(function (response) {
                            this.builds = this.builds.filter(function (item) {
                                return build.id != item.id;
                            });
                         }.bind(this))
                         .catch(error => {
                            if (error.response) {
                                var firstError = error.response.data.errors[0];
                                swal(firstError.title, firstError.detail + "\n\nid: " +firstError.id, "error");
                            } else {
                                swal("Error", error.message, "error");
                            }

                         });
                }.bind(this));
            },

            /**
             * Load builds from API.
             */
            getBuilds() {
                axios.get('/api/modpacks/' + this.modpackId + '/builds')
                      .then(function (response) {
                        this.builds = response.data.data;
                      }.bind(this));
            }
        }
    }
</script>
