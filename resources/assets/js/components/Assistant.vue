<template>
    <div :class="['notification', 'is-radiusless', 'animated', 'is-info']" v-if="show[pagekey]">
        <button class="delete touchable" @click="close()"></button>
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
                show: {}
            };
        },
        props: {
            pagekey: String,
        },

        mounted() {
            if (typeof (Storage) !== "undefined" && this.getObj("assistantShow") !== null) {
		this.show = this.getObj("assistantShow");
            }
        },

        methods: {
            setObj (key, obj) {
              if (typeof (Storage) !== "undefined" /* function to detect if localstorage is supported*/) {
                  return localStorage.setItem(key, JSON.stringify(obj));
              }
            },
            getObj (key) {
              if (typeof (Storage) !== "undefined" /* function to detect if localstorage is supported*/) {
                  return JSON.parse(localStorage.getItem(key));
              }
            },

            close () {
		this.show[this.pagekey] = false;
                this.setObj('assistantShow', this.show)
            },
            open () {
                this.show[this.pagekey] = true;
                this.setObj('assistantShow', this.show)
            },
        }
    }
</script>
