<template>
    <div class="space-y-6">
        <!-- Tabel Validasi Pengajuan -->
        <PengajuanValidasiTable v-model:searchQuery="searchQuery" v-model:statusFilter="statusFilter"
            :paginated-items="paginatedItems" :filtered-items="filteredItems" :is-loading="isLoading"
            :current-page="currentPage" :total-pages="totalPages" :items-per-page="itemsPerPage"
            @open-detail="openDetailModal" @change-page="setPage" @refresh="refreshData" />

        <!-- Modal Detail & Validasi -->
        <PengajuanValidasiModal :is-open="isModalOpen" :selected-item="selectedItem" :form="form" :errors="errors"
            :is-submitting="isSubmitting" @close="closeModal" @save="validasiPengajuan" />
    </div>
</template>

<script setup>
import { useValidasi } from "../composables/useValidasi";
import PengajuanValidasiTable from "../components/ValidasiTable.vue";
import PengajuanValidasiModal from "../components/ValidasiModal.vue";

const {
    isLoading,
    isSubmitting,
    searchQuery,
    statusFilter,
    currentPage,
    itemsPerPage,
    totalPages,
    paginatedItems,
    filteredItems,
    setPage,
    isModalOpen,
    selectedItem,
    openDetailModal,
    closeModal,
    form,
    errors,
    validasiPengajuan,
    refreshData,
} = useValidasi();
</script>
