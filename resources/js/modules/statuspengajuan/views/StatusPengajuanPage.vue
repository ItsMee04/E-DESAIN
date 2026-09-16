<template>
    <div class="space-y-6">
        <!-- Tabel Status Pengajuan -->
        <StatusPengajuanTable v-model:searchQuery="searchQuery" :paginated-items="paginatedItems"
            :filtered-items="filteredItems" :is-loading="isLoading" :current-page="currentPage"
            :total-pages="totalPages" :items-per-page="itemsPerPage" @open-add="openAddModal" @open-edit="openEditModal"
            @open-delete="openDeleteModal" @change-page="setPage" @refresh="refreshData" />

        <!-- Modal Form (Tambah / Edit) -->
        <StatusPengajuanModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" :errors="errors" :is-submitting="isSubmitting" @close="closeModal"
            @save="saveForm" @validate-statuspengajuan="validateStatusPengajuan" />

        <!-- Reusable Confirm Delete Modal -->
        <ConfirmDeleteModal :is-open="isDeleteModalOpen" title="Hapus Data Status Pengajuan?"
            :item-name="selectedDeleteItem?.name" :is-deleting="isDeleting" @close="closeDeleteModal"
            @confirm="confirmDelete" />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useStatusPengajuan } from '../composables/useStatusPengajuan.js';
import StatusPengajuanTable from '../components/StatusPengajuanTable.vue';
import StatusPengajuanModal from '../components/StatusPengajuanModal.vue';
import ConfirmDeleteModal from '../../../utilities/common/ConfirmModalDelete.vue';

const {
    isLoading,
    isDeleting,
    searchQuery,
    currentPage,
    itemsPerPage,
    totalPages,
    paginatedItems,
    filteredItems,
    setPage,
    isModalOpen,
    isEditing,
    isSubmitting,
    form,
    errors,
    validateStatusPengajuan,
    openAddModal,
    openEditModal,
    closeModal,
    saveForm,
    fetchData,
    refreshData,
    isDeleteModalOpen,
    selectedDeleteItem,
    openDeleteModal,
    closeDeleteModal,
    confirmDelete
} = useStatusPengajuan();
</script>
