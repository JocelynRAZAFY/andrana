import Vue from 'vue'
import App from './App'
import store from './store'
import router from './router'
import Cookies from 'js-cookie'

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

// VeeValidate
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate, {
    fieldsBagName: 'veeFields'
});

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/en'
Vue.use(ElementUI, { locale })
Vue.use(ElementUI, {
    size: Cookies.get('size') || 'medium' // set element-ui default size
})

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import '../js/app.js'
import './permission'
import GlobalComponent from './components/globalComponents'
Vue.use(GlobalComponent)

import VueLazyload from "vue-lazyload";
Vue.use(VueLazyload)

new Vue({
    template: '<App/>',
    components: { App },
    router,
    store
}).$mount('#app')

