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
        redirect: '/acceuil',
        children: [
            {
                path: '/acceuil',
                name: 'Acceuil',
                component: () => import('../views/front'),
            },
            {
                path: '/author',
                name: 'Author',
                component: () => import('../views/author'),
            },
            {
                path: '/article',
                name: 'Article',
                component: () => import('../views/article'),
            },
            {
                path: '/article/:id',
                name: 'Article',
                component: () => import('../views/front'),
            },
        ]
    },
    {
        path: '/css',
        name: 'Css',
        component: () => import('../layout/front'),
        redirect: '/css',
        children: [
            {
                path: '/css',
                name: 'Css',
                component: () => import('../docs/categories/CSSPage')
            },
            {
                path: '/css/background',
                name: 'BackgroundImagePage',
                component: () => import('../docs/CSS/BackgroundImagePage')
            },
            {
                path: '/css/animations',
                name: 'AnimationsPage',
                component: () => import('../docs/CSS/AnimationsPage')
            },
            {
                path: '/css/gradient',
                name: 'GradientPage',
                component: () => import('../docs/CSS/GradientPage')
            },
            {
                path: '/css/hover',
                name: 'HoverPagePage',
                component: () => import('../docs/CSS/HoverPage')
            },
            {
                path: '/css/icons',
                name: 'FaPage',
                component: () => import('../docs/CSS/FaPage')
            },
            {
                path: '/css/masks',
                name: 'MasksPage',
                component: () => import('../docs/CSS/MasksPage')
            },
            {
                path: '/css/masonry',
                name: 'MasonryPage',
                component: () => import('../docs/CSS/MasonryPage')
            },
            {
                path: '/css/scrollbar',
                name: 'CustomColorsScrollbarPage',
                component: () => import('../docs/CSS/CustomColorsScrollbarPage')
            },
            {
                path: '/css/table',
                name: 'TablePage',
                component: () => import('../docs/CSS/TablePage')
            },
            {
                path: '/css/table-additional',
                name: 'TableAdditionalPage',
                component: () => import('../docs/CSS/TableAdditionalPage')
            },
            {
                path: '/css/table-responsive',
                name: 'TableResponsviePage',
                component: () => import('../docs/CSS/TableResponsivePage')
            }
        ]
    },
    { path: '*', redirect: '/404', hidden: true }
]

let router = new Router({
    mode: 'history',
    routes: constantRoutes
})

export default router
