import api from '../../../utilities/api/apiClient';

export const unitService = {
    async getUnit() {
        try {
            const response = await api.get('/master/unit');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async storeUnit(payload) {
        try {
            const response = await api.post('/master/unit/store', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updateUnit(payload) {
        try {
            const response = await api.post('/master/unit/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deleteUnit(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/master/unit/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    }
};
