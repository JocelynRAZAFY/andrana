import axiosService from '../plugins/axiosService'

export default {
    async allAuthor(param){
        return axiosService.post('/api/author/all',param)
    },
    async updateAuthor(param){
        return axiosService.post('/api/author/update',param)
    },
    async removeAuthor(param){
        return axiosService.post('/api/author/remove',param)
    },

}
