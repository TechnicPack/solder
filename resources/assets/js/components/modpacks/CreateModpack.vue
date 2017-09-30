<template>
    <div class="box">
        <h1>Create Modpack</h1>
        <div class="box-body">
            <form action="/modpacks" method="post">
                <input type="hidden" name="_token" :value="csrf" />

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" v-model="name" name="name" placeholder="Attack of the B-Team" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Slug</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" v-model="slug" name="slug" placeholder="attack-of-the-bteam" />
                                <p class="help">The slug will become your modpacks key, it needs to be URL safe (no spaces or special characters).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Status</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="status">
                                        <option value="public" selected>Public</option>
                                        <option value="private">Private</option>
                                        <option value="draft">Draft</option>
                                    </select>
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
                            <button class="button is-primary" type="submit">Add Modpack</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            slug: '',
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },
    watch: {
        name: function (name) {
            this.slug = this.slugify(name);
        }
    },
    methods: {
        slugify: function(text) {
            return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
        }
    }
}
</script>