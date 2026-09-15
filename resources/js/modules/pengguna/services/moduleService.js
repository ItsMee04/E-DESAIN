import api from '../../../utilities/api/apiClient';

export const moduleService = {
    async getModules() {
        try {
            const response = await api.get('/master/modules');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updateModule(payload) {
        try {
            const response = await api.post('/master/modules/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deleteModule(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/master/modules/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    },
};
