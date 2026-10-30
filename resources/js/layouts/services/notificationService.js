import api from '../../utilities/api/apiClient';

export const notificationService = {
    async getNotifications(params = {}) {
        try {
            const response = await api.get('/notifications', { params });
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async getUnreadCount() {
        try {
            const response = await api.get('/notifications/unread-count');
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async markAsRead(id) {
        try {
            const response = await api.post(`/notifications/${id}/read`);
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async markAllAsRead() {
        try {
            const response = await api.post('/notifications/read-all');
            return response.data;
        } catch (error) {
            throw error;
        }
    },
};
