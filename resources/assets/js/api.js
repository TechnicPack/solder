
import Store from 'json-api-store';

window.solderApi = new Store(new JsonApi.AjaxAdapter({ base: "/api" }));

solderApi.define([ "modpacks", "modpack" ], {
    name: Store.attr(),
    slug: Store.attr(),
    status: Store.attr(),
    builds: Store.hasMany()
});
