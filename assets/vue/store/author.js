import AuthorApi from "../api/AuthorApi";
import {setToken} from "../plugins/auth";

export default {
    namespaced: true,
    state:{
        authors: [],
        author: {},
        maxPagination: 0,
        total: 0
    },
    mutations:{
        setAuthors(state, {authors,total,maxPagination}) {
            state.authors = authors;
            state.total = total;
            state.maxPagination = maxPagination;
        },
        setAuthor(state,author){
          state.author = author
        },
        addAuthor(state, {author,action}) {
            if(action == 'add'){
                state.authors.push(author);
                state.total ++
            }else {
                const res = state.authors.find(item => item.id == author.id)
                res.name = author.name
            }
        },
        setMaxPagination(state,maxPagination){
            state.maxPagination = maxPagination
        },
        removeAuthor(state,id){
            let index = state.authors.findIndex(item => item.id == id )
            state.authors.splice(index,1)
            state.total --
        },
    },
    actions: {
        async allAuthorAction({commit},param){
            const res = await AuthorApi.allAuthor(param)
            commit('setAuthors', {authors: res.data.data.authors, total: res.data.data.total, maxPagination: res.data.data.maxPagination})
        },
        async updateAuthorAction({commit},param){
            const res = await AuthorApi.updateAuthor(param)
            commit('addAuthor', {author: res.data.data.author, action: res.data.data.action})
        },
        async removeAuthorAction({commit},param){
            const res = await AuthorApi.removeAuthor(param)
            commit('removeAuthor', res.data.data.id)
        },
    }
}
