import router from './router'
import store from './store'
//import NProgress from 'nprogress' // progress bar
//import 'nprogress/nprogress.css' // progress bar style
import { getToken,removeToken } from './plugins/auth'
import axios from "axios";

//NProgress.configure({ showSpinner: false }) // NProgress Configuration
const whiteList = ['/login', '/auth-redirect']

router.beforeEach(async(to, from, next) => {
  //  NProgress.start()
     const hasToken = getToken()

    if (hasToken) {
        try {
          // await store.dispatch('user/userInfoAction')
        }catch (e) {
            console.log(e)
           // window.location.href = '/';
            removeToken()
        }

        if (to.path === '/login') {
            // if is logged in, redirect to the home page
            next({ path: '/back' })
           // NProgress.done()
        } else {
            try {
                axios.interceptors.response.use(undefined, (err) => {
                    return new Promise(() => {
                        console.log(err.response.status)
                        if (err.response.status === 401) {
                            window.location.href = '/';
                            // this.$router.push({path: "/login"})
                        }
                        throw err;
                    });
                });
                next()
            } catch (error) {
                console.log(error)
                removeToken()
                next(`/login?redirect=${to.path}`)
                //NProgress.done()
            }
        }
    }
    else {
        /* has no token*/
        if (whiteList.indexOf(to.path) !== -1) {
            // in the free login whitelist, go directly
            next()
        } else {
            if (to.matched.some(record => record.meta.requiresAuth)) {
                // other pages that do not have permission to access are redirected to the login page.
                next(`/login?redirect=${to.path}`)

                //NProgress.done()
            } else {
                if(from.path.match('/back/')){
                    window.location.href = '/home';
                }else {
                    next();
                }
            }
        }
    }
})

router.afterEach(() => {
    // finish progress bar
   // NProgress.done()
})

