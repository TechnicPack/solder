
/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Load in some support files
 */
require('./forms/bootstrap');
require('./filters/bootstrap');
require('./components/settings/bootstrap');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./vendor/launcher-api/bootstrap');

Vue.component('release-picker', require('./components/ReleasePicker.vue'));
Vue.component('release-table', require('./components/ReleaseTable.vue'));
Vue.component('build-table', require('./components/BuildTable.vue'));
Vue.component('passport-personal-access-tokens', require('./components/PersonalAccessTokens.vue'));
Vue.component('assistant', require('./components/Assistant'));

const app = new Vue({
    el: '#app'
});
