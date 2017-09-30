<template>
    <div class="box">
        <h1>Add Package</h1>
        <div class="box-body">
            <form action="/library/" method="post">
                <input type="hidden" name="_token" :value="csrf" />

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">Name</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control is-expanded">
                                <input class="input" type="text" name="name" placeholder="Iron Tanks" v-model="name" />
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
                            <div class="control is-expanded">
                                <input class="input" type="text" name="slug" placeholder="iron-tanks" v-model="slug" />
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
                            <button class="button is-primary" type="submit">Add Package</button>
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