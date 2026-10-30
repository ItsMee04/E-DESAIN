import { ref } from 'vue';
import { notificationService } from '../services/notificationService';

export function useNotification() {
    const notifications = ref([]);
    const unreadCount = ref(0);

    const isLoading = ref(false);
    const isProcessing = ref(false);

    // Mengambil daftar notifikasi
    const fetchNotifications = async (params = {}) => {
        isLoading.value = true;

        try {
            const response = await notificationService.getNotifications(params);

            notifications.value = response.data?.data ?? [];

            return response;
        } catch (error) {
            console.error('Gagal mengambil notifikasi:', error);
            throw error;
        } finally {
            isLoading.value = false;
        }
    };

    // Mengambil jumlah notifikasi belum dibaca
    const fetchUnreadCount = async () => {
        try {
            const response = await notificationService.getUnreadCount();

            unreadCount.value = response.data?.unread_count ?? 0;

            return response;
        } catch (error) {
            console.error('Gagal mengambil jumlah notifikasi:', error);
            throw error;
        }
    };

    // Memuat daftar dan jumlah notifikasi
    const fetchNotificationData = async () => {
        await Promise.all([
            fetchNotifications(),
            fetchUnreadCount(),
        ]);
    };

    // Menandai satu notifikasi sudah dibaca
    const markAsRead = async (id) => {
        const notification = notifications.value.find(
            (item) => item.id === id
        );

        if (!notification || notification.is_read) return;

        isProcessing.value = true;

        try {
            await notificationService.markAsRead(id);

            notification.is_read = true;

            if (unreadCount.value > 0) {
                unreadCount.value--;
            }
        } catch (error) {
            console.error('Gagal menandai notifikasi:', error);
            throw error;
        } finally {
            isProcessing.value = false;
        }
    };

    // Menandai semua notifikasi sudah dibaca
    const markAllAsRead = async () => {
        if (unreadCount.value === 0) return;

        isProcessing.value = true;

        try {
            await notificationService.markAllAsRead();

            notifications.value.forEach((item) => {
                item.is_read = true;
            });

            unreadCount.value = 0;
        } catch (error) {
            console.error('Gagal menandai semua notifikasi:', error);
            throw error;
        } finally {
            isProcessing.value = false;
        }
    };

    return {
        notifications,
        unreadCount,
        isLoading,
        isProcessing,
        fetchNotifications,
        fetchUnreadCount,
        fetchNotificationData,
        markAsRead,
        markAllAsRead,
    };
}
