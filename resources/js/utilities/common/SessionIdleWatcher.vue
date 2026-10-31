<script setup>
import { onMounted, onUnmounted } from 'vue';
import api from '../api/apiClient';

const IDLE_LIMIT = 60 * 60 * 1000;
const HEARTBEAT_INTERVAL = 5 * 60 * 1000;

let idleTimer = null;
let heartbeatTimer = null;
let lastActivity = Date.now();
let lastHeartbeat = Date.now();
let sessionExpired = false;

const activityEvents = [
    'mousedown',
    'mousemove',
    'keydown',
    'scroll',
    'touchstart',
    'click',
];

const resetIdleTimer = () => {
    if (sessionExpired) return;

    lastActivity = Date.now();

    clearTimeout(idleTimer);

    idleTimer = setTimeout(() => {
        sessionExpired = true;
        window.dispatchEvent(new Event('session-expired'));
    }, IDLE_LIMIT);
};

const checkActivity = async () => {
    if (sessionExpired) return;

    const now = Date.now();

    if (now - lastActivity >= IDLE_LIMIT) {
        sessionExpired = true;
        window.dispatchEvent(new Event('session-expired'));
        return;
    }

    if (now - lastHeartbeat >= HEARTBEAT_INTERVAL) {
        lastHeartbeat = now;

        try {
            await api.get('/me');
        } catch (error) {
            // Jika token tidak valid, interceptor API akan menangani 401.
        }
    }
};

onMounted(() => {
    activityEvents.forEach(event => {
        window.addEventListener(event, resetIdleTimer, { passive: true });
    });

    resetIdleTimer();

    heartbeatTimer = setInterval(checkActivity, 60 * 1000);
});

onUnmounted(() => {
    clearTimeout(idleTimer);
    clearInterval(heartbeatTimer);

    activityEvents.forEach(event => {
        window.removeEventListener(event, resetIdleTimer);
    });
});
</script>

<template>
    <div></div>
</template>
