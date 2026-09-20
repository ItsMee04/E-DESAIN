<template>
    <div class="space-y-6">
        <!-- Tabel Unit -->
        <UnitTable v-model:searchQuery="searchQuery" :paginated-items="paginatedItems" :filtered-items="filteredItems"
            :is-loading="isLoading" :current-page="currentPage" :total-pages="totalPages" :items-per-page="itemsPerPage"
            @open-add="openAddModal" @open-edit="openEditModal" @open-delete="openDeleteModal" @change-page="setPage"
            @refresh="refreshData" />

        <!-- Modal Form (Tambah / Edit) -->
        <UnitModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" :errors="errors"
            :is-submitting="isSubmitting" @close="closeModal" @save="saveForm" @validate-unit="validateUnit" />

        <!-- Reusable Confirm Delete Modal -->
        <ConfirmDeleteModal :is-open="isDeleteModalOpen" title="Hapus Data Unit?" :item-name="selectedDeleteItem?.unit"
            :is-deleting="isDeleting" @close="closeDeleteModal" @confirm="confirmDelete" />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useUnit } from '../composables/useUnit';
import UnitTable from '../components/UnitTable.vue';
import UnitModal from '../components/UnitModal.vue';
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
    validateUnit,
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
} = useUnit();
</script>
