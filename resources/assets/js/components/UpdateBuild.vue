<template>
    <div class="box">
        <h1>Build Settings</h1>
        <div class="box-body">

            <form role="form" @submit.prevent="update">

                <!-- Errors -->
                <div class="field is-horizontal" v-if="messages.length > 0">
                    <div class="field-label is-normal">
                        &nbsp;
                    </div>
                    <div class="field-body">
                        <div class="notification is-success">
                            <ul>
                                <li v-for="message in messages">
                                    {{ message }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Minecraft Version</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <select class="input" name="minecraft_version" v-model="build.minecraft_version" v-on:change="fetchForgeVersons">
                                    <option v-for="mcversion in mcversions" :selected="mcversion == build.minecraft_version">{{ mcversion }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Forge Version</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <select class="input" name="minecraft_version" v-model="build.forge_version" v-on:change="fetchForgeVersons">
                                    <option v-for="forgeversion in forgeversions" :selected="forgeversion == build.forge_version">{{ forgeversion }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Java Version</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" name="java_version" v-model="build.java_version" />

                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Required Memory</label>
                    </div>
                    <div class="field-body">
                        <div class="field has-addons">
                            <div class="control is-expanded">
                                <input class="input" name="required_memory" v-model="build.required_memory" />

                            </div>
                            <div class="control">
                                <p class="button is-static">MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label">
                        &nbsp;
                    </div>
                    <div class="field-body">
                        <div class="control">
                            <button class="button" type="submit">Update</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>


</template>

<script>
    export default{
        props: ['build'],
        data(){
            return{
                mcversions: [],
                forgeversions: [],
                messages : []
            }
        },
        mounted() {
            this.build = this.build;
            this.fetchMCVersions();
        },
        methods: {
            fetchMCVersions: function(){
                axios.get('/forge')
                .then((response) => {

                    this.mcversions = response.data;
                    this.fetchForgeVersons();
                });

            },
            fetchForgeVersons: function(){
                axios.get('/forge/' + this.build.minecraft_version)
                .then((response) => {
                    this.forgeversions = response.data;

                });
            },
            update: function() {

                var bodyFormData = new FormData();
                bodyFormData.set('required_memory', this.build.required_memory);
                bodyFormData.set('minecraft_version', this.build.minecraft_version);
                bodyFormData.set('forge_version', this.build.forge_version);
                bodyFormData.set('java_version', this.build.java_version);

                axios.post('/modpacks/' + this.build.modpack.slug + '/' + this.build.version, bodyFormData, {
                        config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
                .then((response) => {
                    this.messages = ['The build information has been updated!'];
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
