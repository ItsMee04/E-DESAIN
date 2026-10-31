
import axios from "axios";

const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
    },
});

// Mencegah event sesi berakhir dikirim berkali-kali
let isSessionExpired = false;

// INTERCEPTOR REQUEST
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("token");

        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        if (config.data instanceof FormData) {
            delete config.headers["Content-Type"];
        }

        return config;
    },
    (error) => Promise.reject(error)
);

// INTERCEPTOR RESPONSE
apiClient.interceptors.response.use(
    (response) => response,

    (error) => {
        if (error.response?.status === 401) {
            const requestUrl = error.config?.url?.split("?")[0] ?? "";

            // Jangan anggap kesalahan login sebagai token expired
            const isLoginRequest = /(^|\/)login\/?$/.test(requestUrl);

            if (!isLoginRequest && !isSessionExpired) {
                isSessionExpired = true;

                console.warn("Sesi login telah berakhir.");

                // Kirim event untuk menampilkan modal di App.vue
                window.dispatchEvent(
                    new Event("session-expired")
                );
            }
        }

        return Promise.reject(error);
    }
);

export default apiClient;
