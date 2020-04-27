import UserApi from "../api/UserApi";
import {setToken} from "../plugins/auth";

export default {
    namespaced: true,
    state:{
        token: null,
        users: [],
        user: {}
    },
    mutations:{
        setIsLogout(state,isLogout){
          state.isLogout = isLogout
        },
        setToken(state,token){
            state.token = token
        },
        setUsers(state,users){
            state.users = users
        },
        userUpdate(state,{user,status}){
            if(status == 'add'){
                state.users.push(user)
            }else {
                state.users.find(item => {
                    if(item.id == user.id){
                        item.avatar = user.avatar
                        item.lastName = user.lastName
                        item.firstName = user.firstName
                        item.tel = user.tel
                        item.address = user.address
                        item.enabled = user.enabled
                    }
                })
            }
        },
        setUser(state,user){
            state.user = user
        }
    },
    actions:{
        async loginAction({commit},param){
            const res = await UserApi.login(param)
            if(res.data.token != null){
                commit('setToken', res.data.token)
                setToken(res.data.token)
            }
        },
        async logoutAction({commit}){
            const res = await UserApi.logout()
            commit('setIsLogout',true)
        },
        async userUpdateAction({commit},param){
            try {
                const res = await UserApi.userUpdate(param)
                commit('setUserLineUpdate',res.data.data.res)
            }catch (e) {

            }
        }
    }
}
