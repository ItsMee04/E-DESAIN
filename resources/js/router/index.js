import { createRouter, createWebHistory } from "vue-router";

import LoginView from "../modules/authentication/views/LoginPage.vue";
import MainLayout from "../layouts/MainLayout.vue";
import DashboardView from "../modules/dashboard/views/DashboardPage.vue";
import ProfesiView from "../modules/profesi/views/ProfesiPage.vue";
import AgamaView from "../modules/agama/views/AgamaPage.vue";
import JenisKelaminView from "../modules/jeniskelamin/views/JenisKelaminPage.vue";
import JenisMediaView from "../modules/jenismedia/views/JenisMediaPage.vue";
import UnitView from "../modules/unit/views/UnitPage.vue";
import PegawaiView from "../modules/pegawai/views/PegawaiPage.vue";
import PenggunaView from "../modules/pengguna/views/PenggunaPage.vue";
import StatusPengajuanView from "../modules/statuspengajuan/views/StatusPengajuanPage.vue"
import PengajuanView from "../modules/pengajuan/views/PengajuanPage.vue"
import ValidasiPengajuan from "../modules/validasipengajuan/views/ValidasiPage.vue"

const routes = [
    {
        path: "/",
        redirect: "/login",
    },

    {
        path: "/login",
        name: "login",
        component: LoginView,
        meta: {
            guestOnly: true,
        },
    },

    {
        path: "/dashboard",
        component: MainLayout,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "",
                name: "dashboard",
                component: DashboardView,
            },

            {
                path: "/master/profesi",
                name: "profesi",
                component: ProfesiView,
            },

            {
                path: "/master/agama",
                name: "agama",
                component: AgamaView,
            },

            {
                path: "/master/jeniskelamin",
                name: "jeniskelamin",
                component: JenisKelaminView,
            },

            {
                path: "/master/jenismedia",
                name: "jenismedia",
                component: JenisMediaView,
            },

            {
                path: "/master/unit",
                name: "unit",
                component: UnitView,
            },

            {
                path: "/management-user/pegawai",
                name: "pegawai",
                component: PegawaiView,
            },

            {
                path: "/management-user/pengguna",
                name: "pengguna",
                component: PenggunaView,
            },

            {
                path: "/pengajuan/statuspengajuan",
                name: "statuspengajuan",
                component: StatusPengajuanView,
            },

            {
                path: "/pengajuan/pengaujandesain",
                name: "pengajuan",
                component: PengajuanView,
            },

            {
                path: "/pengajuan/validasi",
                name: "validasi",
                component: ValidasiPengajuan,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");

    if (to.meta.requiresAuth && !token) {
        return next("/login");
    }

    if (to.meta.guestOnly && token) {
        return next("/dashboard");
    }

    next();
});

export default router;
