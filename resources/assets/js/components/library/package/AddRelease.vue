<template>
    <div class="box">
        <h1>Add Release</h1>
        <div class="box-body">
            <form role="form" @submit.prevent="create">
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Version</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left is-expanded">
                                <input class="input" type="text" placeholder="1.2.3" name="version" v-model="release">
                                <span class="icon is-small is-left">
                                    <i class="fa fa-code-fork"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Type</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left is-expanded">
                                <select name="type" class="input" v-model="type">
                                    <option value="mods" selected>Mod</option>
                                    <option value="config">Config</option>
                                </select>
                                <span class="icon is-small is-left">
                                    <i class="fa fa-code-fork"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">File</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <div class="columns">
                                    <div class="column is-narrow">
                                        <div class="file">
                                            <label class="file-label">
                                                <input class="file-input" type="file" name="file" @change="fileUpload">
                                                <span class="file-cta">
                                                            <span class="file-icon">
                                                                <i class="fa fa-upload"></i>
                                                            </span>
                                                            <span class="file-label">
                                                                Choose a file…
                                                            </span>
                                                        </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
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
                            <button class="button is-primary" type="submit">Add Release</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default{
        props: ['slug'],
        data(){
            return{
                release: null,
                type: null,
                file: null,
                messages : []
            }
        },
        mounted() {
            this.slug = this.slug;
        },
        methods: {
            fileUpload: function(){
                this.file = event.target.files[0];
            },
            create: function() {
                var bodyFormData = new FormData();
                bodyFormData.set('version', this.release);
                bodyFormData.set('file', this.file);
                bodyFormData.set('type', this.type)

                axios.post('/library/' + this.slug + '/releases', bodyFormData, {
                    config: { headers: {'Content-Tyoe': 'multipart/form-data'}}
                })
                .then((response) => {
                    this.$root.$emit('addReleaseEvent');
                    this.release = null;
                    this.type = null;
                    this.file = null;
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
