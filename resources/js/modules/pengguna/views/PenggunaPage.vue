<template>
    <div class="space-y-6">
        <!-- Tabel Pengguna -->
        <PenggunaTable v-model:searchQuery="searchQuery" :paginated-items="paginatedItems"
            :filtered-items="filteredItems" :is-loading="isLoading" :current-page="currentPage"
            :total-pages="totalPages" :items-per-page="itemsPerPage" @open-add="openAddModal" @open-edit="openEditModal"
            @open-delete="openDeleteModal" @change-page="setPage" @refresh="refreshData" />

        <!-- Modal Form (Tambah / Edit) -->
        <PenggunaModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" :errors="errors" :is-submitting="isSubmitting" @close="closeModal"
            @save="saveForm" @validate-pengguna="validatePengguna" />

        <!-- Reusable Confirm Delete Modal -->
        <ConfirmDeleteModal :is-open="isDeleteModalOpen" title="Hapus Data Pengguna?"
            :item-name="selectedDeleteItem?.nama" :is-deleting="isDeleting" @close="closeDeleteModal"
            @confirm="confirmDelete" />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { usePengguna } from '../composables/usePengguna';
import PenggunaTable from '../components/PenggunaTable.vue';
import PenggunaModal from '../components/PenggunaModal.vue';
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
    validatePengguna,
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
} = usePengguna();
</script>
