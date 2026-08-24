import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../modules/authentication/views/LoginPage.vue'
import MainLayout from '../layouts/MainLayout.vue'
import DashboardView from '../modules/dashboard/views/DashboardPage.vue' // Ganti sesuai lokasi file view dashboard kamu
import ProfesiView from '../modules/profesi/views/ProfesiPage.vue'

const routes = [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView
    },
    {
        path: '/dashboard',
        component: MainLayout,
        children: [
            {
                path: '',
                name: 'dashboard',
                component: DashboardView
            },
            {
                path: '/master/profesi',
                name: 'profesi',
                component: ProfesiView
            },
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
