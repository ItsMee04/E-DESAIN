import api from '../../../utilities/api/apiClient';

export const pegawaiService = {
    async getPegawai() {
        try {
            const response = await api.get('/management-user/pegawai');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async storePegawai(payload) {
        try {
            const response = await api.post('/management-user/pegawai/store', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updatePegawai(payload) {
        try {
            const response = await api.post('/management-user/pegawai/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deletePegawai(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/management-user/pegawai/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    }
};
