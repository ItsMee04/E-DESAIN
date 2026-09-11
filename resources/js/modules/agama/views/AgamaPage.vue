<template>
    <div class="space-y-6">
        <!-- Tabel Agama -->
        <AgamaTable v-model:searchQuery="searchQuery" :paginated-items="paginatedItems"
            :filtered-items="filteredItems" :is-loading="isLoading" :current-page="currentPage"
            :total-pages="totalPages" :items-per-page="itemsPerPage" @open-add="openAddModal" @open-edit="openEditModal"
            @open-delete="openDeleteModal" @change-page="setPage" @refresh="refreshData" />

        <!-- Modal Form (Tambah / Edit) -->
        <AgamaModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" :errors="errors" :is-submitting="isSubmitting" @close="closeModal"
            @save="saveForm" @validate-agama="validateAgama" />

        <!-- Reusable Confirm Delete Modal -->
        <ConfirmDeleteModal :is-open="isDeleteModalOpen" title="Hapus Data Agama?"
            :item-name="selectedDeleteItem?.agama" :is-deleting="isDeleting" @close="closeDeleteModal"
            @confirm="confirmDelete" />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAgama } from '../composables/useAgama';
import AgamaTable from '../components/AgamaTable.vue';
import AgamaModal from '../components/AgamaModal.vue';
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
    validateAgama,
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
} = useAgama();
</script>
