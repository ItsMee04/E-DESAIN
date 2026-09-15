import { ref } from 'vue';
import { authenticationService } from '../services/authenticationService';

export function useAuthentication() {

    const user = ref(null);
    const isLoading = ref(false);
    const isAuthenticated = ref(false);

    const login = async (payload) => {
        isLoading.value = true;

        try {
            const response = await authenticationService.login(payload);

            if (response?.success && response?.data?.token) {

                localStorage.setItem(
                    'token',
                    response.data.token
                );

                localStorage.setItem(
                    'user',
                    JSON.stringify(response.data.user)
                );

                user.value = response.data.user;
                isAuthenticated.value = true;
            }

            return response;

        } catch (error) {
            isAuthenticated.value = false;
            user.value = null;

            throw error;

        } finally {
            isLoading.value = false;
        }
    };

    const logout = async () => {
        isLoading.value = true;

        try {
            const response = await authenticationService.logout();

            localStorage.removeItem('token');
            localStorage.removeItem('user');

            user.value = null;
            isAuthenticated.value = false;

            return response;

        } catch (error) {

            // Tetap hapus data lokal jika token
            // sudah tidak valid di backend
            localStorage.removeItem('token');
            localStorage.removeItem('user');

            user.value = null;
            isAuthenticated.value = false;

            throw error;

        } finally {
            isLoading.value = false;
        }
    };

    const getMe = async () => {
        try {
            const response = await authenticationService.me();

            if (response?.success) {
                user.value = response.data;
                isAuthenticated.value = true;

                localStorage.setItem(
                    'user',
                    JSON.stringify(response.data)
                );
            }

            return response;

        } catch (error) {
            user.value = null;
            isAuthenticated.value = false;

            localStorage.removeItem('token');
            localStorage.removeItem('user');

            throw error;
        }
    };

    const initializeAuth = () => {
        const token = localStorage.getItem('token');
        const storedUser = localStorage.getItem('user');

        if (!token) {
            user.value = null;
            isAuthenticated.value = false;
            return;
        }

        if (storedUser) {
            try {
                user.value = JSON.parse(storedUser);
                isAuthenticated.value = true;
            } catch {
                localStorage.removeItem('user');
                user.value = null;
                isAuthenticated.value = false;
            }
        } else {
            isAuthenticated.value = true;
        }
    };

    return {
        user,
        isLoading,
        isAuthenticated,
        login,
        logout,
        getMe,
        initializeAuth,
    };
}
