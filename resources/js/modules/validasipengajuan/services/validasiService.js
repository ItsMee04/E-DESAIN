import api from '../../../utilities/api/apiClient';

export const pengajuanValidasiService = {

    async getPengajuanValidasi() {
        try {
            const response = await api.get('/pengajuan/validasi');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async getDetailPengajuanValidasi(id) {
        try {
            const response = await api.get(
                `/pengajuan/validasi/detail/${id}`
            );
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async validasiPengajuan(id, payload) {
        try {
            const response = await api.post(
                `/pengajuan/validasi/validasi/${id}`,
                payload
            );
            return response.data;
        } catch (error) {
            throw error;
        }
    }

};
