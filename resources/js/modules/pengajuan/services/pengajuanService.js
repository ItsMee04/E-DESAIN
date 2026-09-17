import api from "../../../utilities/api/apiClient";

export const pengajuanService = {
    async getPengajuan() {
        try {
            const response = await api.get("/pengajuan/pengajuan");
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async getPengajuanById(payload) {
        try {
            const response = await api.get("/pengajuan/pengajuan/detail", {
                params: payload,
            });
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async storePengajuan(payload) {
        try {
            const response = await api.post(
                "/pengajuan/pengajuan/store",
                payload,
            );
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async updatePengajuan(payload) {
        try {
            const response = await api.post(
                "/pengajuan/pengajuan/update",
                payload,
            );
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async deletePengajuan(payload) {
        try {
            // Axios delete butuh opsi { data: ... } untuk mengirim request body
            const response = await api.delete("/pengajuan/pengajuan/delete", {
                data: payload,
            });
            return response.data;
        } catch (error) {
            throw error;
        }
    },
};
