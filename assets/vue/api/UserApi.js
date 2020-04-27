import axiosService from '../plugins/axiosService'

export default {
    async login(param){
        return axiosService.post('/api/user/login',param)
    },
    async logout(param){
        return axiosService.post('/api/user/logout',param)
    },
    async userUpdate(param){
        return axiosService.post('/api/back/user/update',param)
    },

}
