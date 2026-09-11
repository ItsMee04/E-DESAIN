<template>
    <div class="space-y-6">
        <!-- Tabel Jenis Media -->
        <JenisMediaTable v-model:searchQuery="searchQuery" :paginated-items="paginatedItems"
            :filtered-items="filteredItems" :is-loading="isLoading" :current-page="currentPage"
            :total-pages="totalPages" :items-per-page="itemsPerPage" @open-add="openAddModal" @open-edit="openEditModal"
            @open-delete="openDeleteModal" @change-page="setPage" @refresh="refreshData" />

        <!-- Modal Form (Tambah / Edit) -->
        <JenisMediaModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" :errors="errors" :is-submitting="isSubmitting" @close="closeModal"
            @save="saveForm" @validate-jenismedia="validateJenisMedia" />

        <!-- Reusable Confirm Delete Modal -->
        <ConfirmDeleteModal :is-open="isDeleteModalOpen" title="Hapus Data Jenis Media?"
            :item-name="selectedDeleteItem?.jenismedia" :is-deleting="isDeleting" @close="closeDeleteModal"
            @confirm="confirmDelete" />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useJenisMedia } from '../composables/useJenisMedia';
import JenisMediaTable from '../components/JenisMediaTable.vue';
import JenisMediaModal from '../components/JenisMediaModal.vue';
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
    validateJenisMedia,
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
} = useJenisMedia();
</script>
