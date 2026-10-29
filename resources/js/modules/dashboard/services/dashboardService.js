import api from '../../../utilities/api/apiClient';

export const dashboardService = {
    async getDataDashboard() {
        try {
            const response = await api.get('/dashboard/datadashboard');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async getPengajuanTerbaru() {
        try {
            const response = await api.get('/dashboard/pengajuanterbaru');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async getStatistikBulanan() {
        try {
            const response = await api.get('/dashboard/statistikbulanan');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async getStatistikStatus() {
        try {
            const response = await api.get('/dashboard/statistikstatus');
            return response.data;
        } catch (error) {
            throw error;
        }
    },
};
