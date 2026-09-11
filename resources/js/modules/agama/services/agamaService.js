import api from '../../../utilities/api/apiClient';

export const agamaService = {
    async getAgama() {
        try {
            const response = await api.get('/master/agama');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async storeAgama(payload) {
        try {
            const response = await api.post('/master/agama/store', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updateAgama(payload) {
        try {
            const response = await api.post('/master/agama/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deleteAgama(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/master/agama/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    }
};
