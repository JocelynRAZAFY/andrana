export default {
    namespaced: true,
    state:{
        loading: false,
        isValidForm: null,
        lineUpdated: false,
        maxPage: 7,
        maxPagination: 2,
        paramId: 0,
        steps: []
    },
    mutations:{
        setSteps(state,{step,isNull}){
            const res = state.steps.find(item => item == step)
            if(res == undefined){
                state.steps.push(step)
            }
            if(isNull){
                state.steps = []
            }
        },
        setParamId(state,paramId){
            state.paramId = paramId
        },
        setLoading(state,loading){
            state.loading = loading
        },
        setIsValidForm(state,isValidForm){
            state.isValidForm = isValidForm
        },
        setLineUpdated(state,lineUpdated){
            state.lineUpdated = lineUpdated
        },
        setMaxPage(state,maxPage){
            state.maxPage = maxPage
        },
        setMaxPagination(state,maxPagination){
            state.maxPagination = maxPagination
        }
    },
    actions: {

    }
}
