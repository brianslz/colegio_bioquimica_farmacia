
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('afiliado', require('./components/Afiliado.vue'));
//Vue.component('pagos', require('./components/Pagos.vue'));
Vue.component('aportes', require('./components/Aportes.vue'));
Vue.component('reporte', require('./components/Reporte.vue'));
Vue.component('rol', require('./components/Rol.vue'));
Vue.component('usuarios', require('./components/Usuarios.vue'));
Vue.component('inicio', require('./components/Inicio.vue'));
Vue.component('perfil2', require('./components/Perfil2.vue'));
Vue.component('aportes2', require('./components/Aportes2.vue'));

const app = new Vue({
    el: '#app',
    data:{
        menu:0
    }
});
