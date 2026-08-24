import { ref } from 'vue';

// State dibuat di luar fungsi agar bersifat Global Singleton
const toasts = ref([]);

export function useToast() {
    /**
     * Menambahkan Toast baru
     * @param {Object} options
     * @param {string} options.message - Pesan notifikasi
     * @param {'success'|'error'|'info'|'warning'} [options.type='success'] - Tipe notifikasi
     * @param {number} [options.duration=3000] - Durasi tampil (ms)
     */
    const addToast = ({ message, type = 'success', duration = 3000 }) => {
        const id = Date.now() + Math.random();

        toasts.value.push({
            id,
            message,
            type,
            duration
        });

        if (duration > 0) {
            setTimeout(() => {
                removeToast(id);
            }, duration);
        }
    };

    // Menghapus toast berdasarkan ID
    const removeToast = (id) => {
        toasts.value = toasts.value.filter((toast) => toast.id !== id);
    };

    // Helper utilities untuk pemanggilan cepat
    const toast = {
        success: (message, duration) => addToast({ message, type: 'success', duration }),
        error: (message, duration) => addToast({ message, type: 'error', duration }),
        info: (message, duration) => addToast({ message, type: 'info', duration }),
        warning: (message, duration) => addToast({ message, type: 'warning', duration }),
    };

    return {
        toasts,
        toast,
        removeToast
    };
}
