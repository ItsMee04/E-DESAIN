import api from '../../../utilities/api/apiClient';

export const statuspengajuanService = {
    async getStatusPengajuan() {
        try {
            const response = await api.get('/pengajuan/statuspengajuan');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async storeStatusPengajuan(payload) {
        try {
            const response = await api.post('/pengajuan/statuspengajuan/store', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updateStatusPengajuan(payload) {
        try {
            const response = await api.post('/pengajuan/statuspengajuan/update', payload);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deleteStatusPengajuan(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete('/pengajuan/statuspengajuan/delete', { data: payload });
            return response.data;
        } catch (error) {
            throw error;
        }
    }
};
