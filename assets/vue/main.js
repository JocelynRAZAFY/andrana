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

import '../js/app.js'
import './permission'
import GlobalComponent from './components/globalComponents'
Vue.use(GlobalComponent)

import VueLazyload from "vue-lazyload";
Vue.use(VueLazyload)

import 'bootstrap-css-only/css/bootstrap.min.css'
import 'mdbvue/lib/css/mdb.min.css'

new Vue({
    template: '<App/>',
    components: { App },
    router,
    store
}).$mount('#app')

