export default {
    namespaced: true,
    state:{
        docs: true
    },
    mutations:{
        toggleDocs(state, value) {
            state.docs = value;
        }
    },
    actions: {

    }
}
