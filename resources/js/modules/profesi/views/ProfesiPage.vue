<template>
    <div class="space-y-6">
        <!-- Tabel Profesi -->
        <ProfesiTable v-model:searchQuery="searchQuery" :paginated-items="paginatedItems"
            :filtered-items="filteredItems" :is-loading="isLoading" :current-page="currentPage"
            :total-pages="totalPages" :items-per-page="itemsPerPage" @open-add="openAddModal" @open-edit="openEditModal"
            @open-delete="openDeleteModal" @change-page="setPage" @refresh="refreshData" />

        <!-- Modal Form (Tambah / Edit) -->
        <ProfesiModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" @close="closeModal"
            @save="saveForm" />

        <!-- Reusable Confirm Delete Modal -->
        <ConfirmDeleteModal :is-open="isDeleteModalOpen" title="Hapus Data Profesi?"
            :item-name="selectedDeleteItem?.nama" :is-deleting="isDeleting" @close="closeDeleteModal"
            @confirm="confirmDelete" />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useProfesi } from '../composables/useProfesi';
import ProfesiTable from '../components/ProfesiTable.vue';
import ProfesiModal from '../components/ProfesiModal.vue';
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
    form,
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
} = useProfesi();

onMounted(() => {
    fetchData();
});
</script>
