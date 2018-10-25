<template>
    <transition name="slide-fade" mode="out-in">
        <div v-if="show" class="notification is-info" key="show">
            <button class="delete" @click="dismiss()" v-if="hasLocalStorage"></button>
            <nav class="columns">
                <div class="column is-narrow">
                    <div class="image is-64x64 is-pulled-left">
                        <img src="/img/book.png" />
                    </div>
                </div>
                <div class="column is-size-5">
                    <slot></slot>
                </div>
            </nav>
        </div>
        <div v-else key="hide">
            <button class="recall" @click="recall()" v-if="hasLocalStorage"></button>
        </div>
    </transition>
</template>

<script>
    export default {
        name: "assistant",
        props: ['id'],

        data() {
            return {
                show: null,
            };
        },

        mounted() {
            if (this.storageAvailable('localStorage')) {
                this.show = localStorage.getItem(`dismissable.${this.id}`) === "true";
            } else {
                this.show = true;
            }
        },

        computed: {
            hasLocalStorage: function () {
                return this.storageAvailable('localStorage');
            }
        },

        watch: {
            show: function (val) {
                if(this.storageAvailable('localStorage')) {
                    localStorage.setItem(`dismissable.${this.id}`, this.show.toString());
                }
            }
        },

        methods: {
            dismiss () {
                this.show = false;
            },

            recall () {
                this.show = true;
            },

            storageAvailable(type) {
                try {
                    var storage = window[type],
                        x = '__storage_test__';
                    storage.setItem(x, x);
                    storage.removeItem(x);
                    return true;
                }
                catch(e) {
                    return e instanceof DOMException && (
                            // everything except Firefox
                        e.code === 22 ||
                        // Firefox
                        e.code === 1014 ||
                        // test name field too, because code might not be present
                        // everything except Firefox
                        e.name === 'QuotaExceededError' ||
                        // Firefox
                        e.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
                        // acknowledge QuotaExceededError only if there's something already stored
                        storage.length !== 0;
                }
            }
        }
    }
</script>