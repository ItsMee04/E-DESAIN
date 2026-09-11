import api from '../../../utilities/api/apiClient';

export const jeniskelaminService = {
    async getJenisKelamin() {
        try {
            const response = await api.get('/master/jeniskelamin');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async storeJenisKelamin(payload) {
        try {
            const response = await api.post('/master/jeniskelamin/store', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updateJenisKelamin(payload) {
        try {
            const response = await api.post('/master/jeniskelamin/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deleteJenisKelamin(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/master/jeniskelamin/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    }
};
