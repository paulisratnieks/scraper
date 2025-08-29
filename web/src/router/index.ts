import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth.ts'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            redirect: () => {
                return useAuthStore().isAuthenticated ? 'articles' : 'login'
            },
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/LoginView.vue'),
            beforeEnter: (_, from) => {
                if (useAuthStore().isAuthenticated) {
                    return from
                }
                return true
            },
        },
        {
            path: '/articles',
            name: 'articles',
            component: () => import('../views/ArticlesView.vue'),
        },
        {
            path: '/:pathMatch(.*)*',
            redirect: '/',
        },
    ],
})

export default router
