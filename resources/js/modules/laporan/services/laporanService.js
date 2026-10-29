import api from '../../../utilities/api/apiClient'

export const laporanService = {
    async getFilteredData(filters = {}) {
        const response = await api.get('/laporan', {
            params: filters,
        });

        return response.data;
    },

    async exportExcel(filters = {}) {
        return api.get('/laporan/export-excel', {
            params: filters,
            responseType: 'blob',
        })
    },

    async exportPdf(filters = {}) {
        return api.get('/laporan/export-pdf', {
            params: filters,
            responseType: 'blob',
        });
    }

}
