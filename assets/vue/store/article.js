import ArticleApi from "../api/ArticleApi";

export default {
    namespaced: true,
    state:{
        articles: [],
        article: {},
        maxPagination: 0,
        total: 0,
        authors: [],
        isAdd: false
    },
    mutations:{
        setIsAdd(state,isAdd){
          state.isAdd = isAdd
        },
        setArticles(state, {articles,total,maxPagination,authors}) {
            state.articles = articles;
            state.total = total;
            state.maxPagination = maxPagination;
            state.authors = authors
        },
        setArticle(state,article){
          state.article = article
        },
        addArticle(state, {article,action}) {
            if(action == 'add'){
                state.articles.push(article);
                state.total ++
            }else {
                const res = state.articles.find(item => item.id == article.id)
                res.title = article.title
                res.author = article.author
                res.description = article.description
            }
        },
        setMaxPagination(state,maxPagination){
            state.maxPagination = maxPagination
        },
        removeArticle(state,id){
            let index = state.articles.findIndex(item => item.id == id )
            state.articles.splice(index,1)
            state.total --
        },
    },
    actions: {
        async allArticleAction({commit},param){
            const res = await ArticleApi.allArticle(param)
            const payload = {
                articles: res.data.data.articles,
                total: res.data.data.total,
                maxPagination: res.data.data.maxPagination,
                authors: res.data.data.authors
            }
            commit('setArticles', payload)
        },
        async updateArticleAction({commit},param){
            const res = await ArticleApi.updateArticle(param)
            commit('addArticle', {article: res.data.data.article, action: res.data.data.action})
        },
        async removeArticleAction({commit},param){
            const res = await ArticleApi.removeArticle(param)
            commit('removeArticle', res.data.data.id)
        },
    }
}
