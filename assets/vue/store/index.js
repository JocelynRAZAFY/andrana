import Vue from 'vue'
import Vuex from 'vuex'
import UserModule from './user'
import OtherModule from './other'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        user: UserModule,
        other: OtherModule
    }
})
