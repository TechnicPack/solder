<template>
    <div :class="['notification', 'is-radiusless', 'animated', type ? `is-${type}` : '']" v-if="show">
        <button class="delete touchable" @click="close()"></button>
        <div class="title is-5" v-if="title">{{ title }}</div>
        <nav class="columns">
            <div class="column is-narrow">
                <figure class="image is-64x64 is-pulled-left">
                    <img src="/img/book.png" />
                </figure>
            </div>
            <div class="column">
                <slot><!-- the image tag and caption will be inserted here by Vue--></slot>
            </div>
        </nav>
    </div>
    <div class="level-right" v-else>
        <a class="button is-medium is-primary is-outlined" @click="open()"><span class="icon"><i class="fa fa-question-circle"></i></span></a>
    </div>
</template>

<script>
    export default{        
        data() {
            return {
                html: [],
                show: true
            };
        },
        props: {
            type: String,
            title: String,
        },

        mounted() {
            if (typeof (Storage) !== "undefined" && localStorage.getItem("assistantShow") !== null) {
                if (localStorage.getItem("assistantShow") === "false") {
                    this.show = false;
                }
            }
        },

        methods: {
            close () {
                this.show = false;
                if (typeof (Storage) !== "undefined" /* function to detect if localstorage is supported*/) {
                    localStorage.setItem('assistantShow', false)
                }
            },
            open () {
                this.show = true;
                if (typeof (Storage) !== "undefined" /* function to detect if localstorage is supported*/) {
                    localStorage.setItem('assistantShow', true)
                }
            },
        }
    }
</script>