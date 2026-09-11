import api from '../../../utilities/api/apiClient';

export const pegawaiService = {
    async getPegawai() {
        try {
            const response = await api.get('/master/pegawai');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async storePegawai(payload) {
        try {
            const response = await api.post('/master/pegawai/store', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updatePegawai(payload) {
        try {
            const response = await api.post('/master/pegawai/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deletePegawai(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/master/pegawai/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    }
};
