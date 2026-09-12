import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../modules/authentication/views/LoginPage.vue'
import MainLayout from '../layouts/MainLayout.vue'
import DashboardView from '../modules/dashboard/views/DashboardPage.vue'
import ProfesiView from '../modules/profesi/views/ProfesiPage.vue'
import AgamaView from '../modules/agama/views/AgamaPage.vue'
import JenisKelaminView from '../modules/jeniskelamin/views/JenisKelaminPage.vue'
import JenisMediaView from '../modules/jenismedia/views/JenisMediaPage.vue'
import PegawaiView from '../modules/pegawai/views/PegawaiPage.vue'
import PenggunaView from '../modules/pengguna/views/PenggunaPage.vue'

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
            {
                path: '/master/agama',
                name: 'agama',
                component: AgamaView
            },
            {
                path: '/master/jeniskelamin',
                name: 'jeniskelamin',
                component: JenisKelaminView
            },
            {
                path: '/master/jenismedia',
                name: 'jenismedia',
                component: JenisMediaView
            },
            {
                path: '/master/pegawai',
                name: 'pegawai',
                component: PegawaiView
            },
            {
                path: '/master/pengguna',
                name: 'pengguna',
                component: PenggunaView
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
