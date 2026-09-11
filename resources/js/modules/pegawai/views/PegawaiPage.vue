<template>
    <div class="space-y-6">
        <!-- Tabel Pegawai -->
        <PegawaiTable v-model:searchQuery="searchQuery" :paginated-items="paginatedItems"
            :filtered-items="filteredItems" :is-loading="isLoading" :current-page="currentPage"
            :total-pages="totalPages" :items-per-page="itemsPerPage" @open-add="openAddModal" @open-edit="openEditModal"
            @open-delete="openDeleteModal" @change-page="setPage" @refresh="refreshData" />

        <!-- Modal Form (Tambah / Edit) -->
        <PegawaiModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" :errors="errors"
            :is-submitting="isSubmitting" :agama-options="agamaOptions" :profesi-options="profesiOptions"
            :jeniskelamin-options="jeniskelaminOptions" :image-preview="imagePreview" @close="closeModal"
            @save="saveForm" @validate-pegawai="validatePegawai" @image-change="handleImageChange" />

        <!-- Reusable Confirm Delete Modal -->
        <ConfirmDeleteModal :is-open="isDeleteModalOpen" title="Hapus Data Pegawai?"
            :item-name="selectedDeleteItem?.nama" :is-deleting="isDeleting" @close="closeDeleteModal"
            @confirm="confirmDelete" />
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { usePegawai } from '../composables/usePegawai';
import PegawaiTable from '../components/PegawaiTable.vue';
import PegawaiModal from '../components/PegawaiModal.vue';
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
    validatePegawai,
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
    confirmDelete,

    agamaOptions,
    profesiOptions,
    jeniskelaminOptions,
    imagePreview,
    handleImageChange
} = usePegawai();
</script>
