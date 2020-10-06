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
import '@fortawesome/fontawesome-free/css/all.min.css'

import Vuex from 'vuex'
import Vueditor from 'vueditor'

import 'vueditor/dist/style/vueditor.min.css'

// your config here
let config = {
    toolbar: [
        'removeFormat', 'undo', '|', 'elements','foreColor', 'backColor', 'divider',
        'bold', 'italic', 'underline', 'strikeThrough', 'links', 'divider',
        'divider', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull', '|'
    ],
    fontName: [
        {val: 'arial black'},
        {val: 'times new roman'},
        {val: 'Courier New'}
    ],
    fontSize: [
        '12px', '14px', '16px', '18px', '20px', '24px', '28px', '32px', '36px'
    ],
    uploadUrl: '',
    id: '',
    classList: []
};

Vue.use(Vuex);
Vue.use(Vueditor, config);

new Vue({
    template: '<App/>',
    components: { App },
    router,
    store
}).$mount('#app')

