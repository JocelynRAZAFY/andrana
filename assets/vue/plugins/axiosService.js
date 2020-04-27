// var getToken = function () {
//     return localStorage.getItem('userToken')
// }

import axios from "axios";
import { getToken } from './auth'

export default {
    post(url,params){
        var config;
        var token = getToken()

        if(token){
            config = {
                headers: {
                    'Content-Type': 'application/ld+json',
                    'Authorization': 'Bearer ' + token
                }
            }

        }else {
            config = {
                headers: {
                    'Content-Type': 'application/ld+json'
                }
            }
        }
       // this.getIntercept()
        return axios.post(url, params, config)
    },
    get(url){
        var token = getToken()
        const config = {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        }
        //this.getIntercept()
        return axios.get(url, config)
    }
}
