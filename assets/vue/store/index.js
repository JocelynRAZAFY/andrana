import Vue from 'vue'
import Vuex from 'vuex'
import UserModule from './user'
import OtherModule from './other'
import DocModule from './doc'
import AuthorModule from './author'
import ArticleModule from './article'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        user: UserModule,
        other: OtherModule,
        doc: DocModule,
        author: AuthorModule,
        article: ArticleModule
    }
})
