/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('login', require('./components/LoginComponent.vue').default);
Vue.component('error-stack', require('./components/ErrorStackComponent.vue').default);
Vue.component('filebrowser', require('./components/FileBrowserCompontent.vue').default);
Vue.component('navigation-path', require('./components/NavigationPathComponent.vue').default);
Vue.component('file', require('./components/FileComponent.vue').default);
Vue.component('directory', require('./components/DirectoryComponent.vue').default);
Vue.component('uploader', require('./components/UploaderComponent.vue').default);
Vue.component('file-creator', require('./components/FileCreator.vue').default);
Vue.component('editor', require('./components/EditorComponent.vue').default);
Vue.component('dashboard-navigator', require('./components/dashboard/DashboardNavigatorComponent.vue').default);
Vue.component('servers', require('./components/dashboard/ServersComponent.vue').default);
Vue.component('usermanager', require('./components/dashboard/UserManagerComponent.vue').default);
Vue.component('serversmanager', require('./components/dashboard/ServersManagerComponent.vue').default);
Vue.component('dashboard', require('./components/dashboard/DashboardComponent.vue').default);
Vue.component('userform', require('./components/users/UserCreateFormComponent.vue').default);
Vue.component('userpermissions', require('./components/users/UserPermissionsComponent.vue').default);
Vue.component('userpower', require('./components/users/UserPowerComponent.vue').default);
Vue.component('serverform', require('./components/servers/ServerCreateFormComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
