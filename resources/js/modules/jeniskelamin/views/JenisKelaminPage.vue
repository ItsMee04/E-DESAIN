<template>
    <div class="space-y-6">
        <!-- Tabel Jenis Kelamin -->
        <JenisKelaminTable v-model:searchQuery="searchQuery" :paginated-items="paginatedItems"
            :filtered-items="filteredItems" :is-loading="isLoading" :current-page="currentPage"
            :total-pages="totalPages" :items-per-page="itemsPerPage" @open-add="openAddModal" @open-edit="openEditModal"
            @open-delete="openDeleteModal" @change-page="setPage" @refresh="refreshData" />

        <!-- Modal Form (Tambah / Edit) -->
        <JenisKelaminModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" :errors="errors" :is-submitting="isSubmitting" @close="closeModal"
            @save="saveForm" @validate-jeniskelamin="validateJenisKelamin" />

        <!-- Reusable Confirm Delete Modal -->
        <ConfirmDeleteModal :is-open="isDeleteModalOpen" title="Hapus Data Jenis Kelamin?"
            :item-name="selectedDeleteItem?.jeniskelamin" :is-deleting="isDeleting" @close="closeDeleteModal"
            @confirm="confirmDelete" />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useJenisKelamin } from '../composables/useJenisKelamin';
import JenisKelaminTable from '../components/JenisKelaminTable.vue';
import JenisKelaminModal from '../components/JenisKelaminModal.vue';
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
    validateJenisKelamin,
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
} = useJenisKelamin();
</script>
