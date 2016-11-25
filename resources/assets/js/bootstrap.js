
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = require('vue');
require('vue-resource');

/**
 * Time Ago VUE component, for easily turning dates into "5 minutes ago"
 * strings.
 *
 * TODO: I'm not a java guy ... there must be a cleaner way to do this
 */
var VueTimeago = require('vue-timeago');
Vue.use(VueTimeago, {
    locale: 'en-US',
    maxTime: 86400 * 5,
    locales: {
        'en-US': [
            "just now",
            ["%s second ago", "%s seconds ago"],
            ["%s minute ago", "%s minutes ago"],
            ["%s hour ago", "%s hours ago"],
            ["%s day ago", "%s days ago"],
            ["%s week ago", "%s weeks ago"],
            ["%s month ago", "%s months ago"],
            ["%s year ago", "%s years ago"]
        ]
    }
});

/**
 * JsonApiDataStore is a client side JSON API parser, used in the vue models
 * to consume the api for web presentation.
 *
 * TODO: I'm not a java guy ... there must be a cleaner way to do this
 */
var jsonApiDatastore = require('jsonapi-datastore');
window.store = new jsonApiDatastore.JsonApiDataStore();

/**
 * An awesome replacement for JavaScript's alert.
 */
require('sweetalert');


/**
 * We'll register a HTTP interceptor to attach the "CSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 */

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);

    next();
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
