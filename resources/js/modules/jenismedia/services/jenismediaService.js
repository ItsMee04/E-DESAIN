import api from '../../../utilities/api/apiClient';

export const jenismediaService = {
    async getJenisMedia() {
        try {
            const response = await api.get('/master/jenismedia');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async storeJenisMedia(payload) {
        try {
            const response = await api.post('/master/jenismedia/store', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updateJenisMedia(payload) {
        try {
            const response = await api.post('/master/jenismedia/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deleteJenisMedia(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/master/jenismedia/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    }
};
