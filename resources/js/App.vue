<template>
    <router-view />
    <ToastContainer />
    <SessionExpiredModal />
    <SessionIdleWatcher />
</template>

<script setup>
import ToastContainer from './utilities/toast/ToastContainer.vue';
import SessionExpiredModal from './utilities/common/SessionExpiredModal.vue';
import SessionIdleWatcher from './utilities/common/SessionIdleWatcher.vue';
</script>
