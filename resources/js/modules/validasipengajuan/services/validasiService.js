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
    },

    async getPengajuanValidasiIT() {
        try {
            const response = await api.get('/pengajuan/validasi/it');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async validasiPengajuanIT(id, payload) {
        try {
            const response = await api.post(
                `/pengajuan/validasi/validasi/it/${id}`,
                payload
            );
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async konfirmasiSiapDiambil(id, payload) {
        try {
            const response = await api.patch(
                `/pengajuan/validasi/siap-diambil/${id}`,
                payload
            );
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async konfirmasiSelesaiDiambil(id) {
        try {
            const response = await api.patch(
                `/pengajuan/validasi/selesai-diambil/${id}`
            );
            return response.data;
        } catch (error) {
            throw error;
        }
    },

};
