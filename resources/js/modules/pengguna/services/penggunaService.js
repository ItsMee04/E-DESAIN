import api from '../../../utilities/api/apiClient';

export const penggunaService = {
    async getPengguna() {
        try {
            const response = await api.get('/management-user/pengguna');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updatePengguna(payload) {
        try {
            const response = await api.post('/management-user/pengguna/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deletePengguna(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/management-user/pengguna/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    }
};
