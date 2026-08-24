import api from '../../../utilities/api/apiClient';

export const profesiService = {
    async getProfesi() {
        try {
            const response = await api.get('/master/profesi');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async storeProfesi(payload) {
        try {
            const response = await api.post('/master/profesi/store', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updateProfesi(payload) {
        try {
            const response = await api.post('/master/profesi/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deleteProfesi(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/master/profesi/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    }
};
