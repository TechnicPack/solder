
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('mod-list', require('./components/mods/ModList.vue'));
Vue.component('mod-releases', require('./components/releases/ModReleases.vue'));
Vue.component('modpack-list', require('./components/modpacks/ModpackList.vue'));
Vue.component('modpack-builds', require('./components/builds/ModpackBuilds.vue'));
Vue.component('build-releases', require('./components/releases/BuildReleases.vue'));
Vue.component('release-builds', require('./components/builds/ReleaseBuilds.vue'));
Vue.component('recent-mod-releases', require('./components/releases/RecentModReleases.vue'));
Vue.component('recent-modpack-builds', require('./components/builds/RecentModpackBuilds.vue'));
Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

const app = new Vue({
    el: '#app'
});
