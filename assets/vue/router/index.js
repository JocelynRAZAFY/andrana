import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export const constantRoutes = [
    {
        path: '/back',
        name: 'back',
        component: () => import('../layout/back'),
        redirect: '/back/dashboard',
        children: [
            {
                path: '/back/dashboard',
                component: () => import('../views/back'),
                name: 'Dashboard',
                meta: { requiresAuth: true }
            }
        ]
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../views/login')
    },
    {
        path: '/',
        name: 'home',
        component: () => import('../layout/front'),
        redirect: '/front',
        children: [
            {
                path: 'front',
                name: 'Front',
                component: () => import('../views/front'),
                meta: {
                    pageTitle: 'Shop'
                }
            },
        ]
    },
    { path: '*', redirect: '/404', hidden: true }
]

let router = new Router({
    mode: 'history',
    routes: constantRoutes
})

export default router
