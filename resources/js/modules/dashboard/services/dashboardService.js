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
};
