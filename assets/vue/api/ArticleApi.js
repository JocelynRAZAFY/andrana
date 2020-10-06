import axiosService from '../plugins/axiosService'

export default {
    async allArticle(param){
        return axiosService.post('/api/article/all',param)
    },
    async updateArticle(param){
        return axiosService.post('/api/article/update',param)
    },
    async removeArticle(param){
        return axiosService.post('/api/article/remove',param)
    },

}
