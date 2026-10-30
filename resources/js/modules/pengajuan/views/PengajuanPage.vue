<template>
    <div class="space-y-6">

        <!-- Header Page -->
        <div>
            <h1 class="text-2xl font-bold text-blue-950">
                Pengajuan Desain
            </h1>

            <p class="text-sm text-blue-950/60 mt-1">
                Kelola pengajuan desain yang telah dibuat
            </p>
        </div>

        <!-- Table -->
        <PengajuanTable v-model:searchQuery="searchQuery" v-model:statusFilter="statusFilter"
            :paginated-items="paginatedItems" :filtered-items="filteredItems" :is-loading="isLoading"
            :current-page="currentPage" :total-pages="totalPages" :items-per-page="itemsPerPage" :statuses="statuses"
            @open-add="openAddModal" @open-detail="openDetailModal" @open-edit="openEditModal"
            @open-delete="openDeleteModal" @open-ready="(item) => openStatusModal(item, 'ready')"
            @open-complete="(item) => openStatusModal(item, 'complete')" @change-page="setPage" @refresh="refreshData"
            :role="user?.role?.key" />

        <!-- Modal Pengajuan -->
        <PengajuanModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" :errors="errors"
            :is-submitting="isSubmitting" :units="units" :jenis-media="jenisMedia" @close="closeModal" @save="saveForm"
            @validate-pengajuan="validatePengajuan" />

        <!-- Detail Modal -->
        <PengajuanDetailModal :is-open="isDetailModalOpen" :item="selectedDetailItem" @close="closeDetailModal" />

        <!-- Confirm Delete -->
        <ConfirmDeleteModal :is-open="isDeleteModalOpen" :item-name="selectedDeleteItem?.nomor ?? ''"
            :is-deleting="isDeleting" @close="closeDeleteModal" @confirm="deleteData" />

        <!-- Confirm Status Pengajuan -->
        <ConfirmModalStatus :is-open="isStatusModalOpen" :type="statusActionType"
            :item-name="selectedStatusItem?.nomor ?? ''" :is-processing="isProcessingStatus" @close="closeStatusModal"
            @confirm="confirmStatus" />

    </div>
</template>

<script setup>
import PengajuanTable from '../components/PengajuanTable.vue';
import PengajuanModal from '../components/PengajuanModal.vue';
import PengajuanDetailModal from '../components/PengajuanModalDetail.vue';
import ConfirmDeleteModal from '../../../utilities/common/ConfirmModalDelete.vue';
import ConfirmModalStatus from "../../../utilities/common/ConfirmModalStatus.vue";
import { ref, onMounted } from 'vue';


import { usePengajuan } from '../composables/usePengajuan';

const user = ref(null);

const getUserData = () => {
    try {
        const storedUser = localStorage.getItem('user');

        if (storedUser) {
            user.value = JSON.parse(storedUser);
        }
    } catch (error) {
        console.error('Gagal mengambil data user:', error);
        user.value = null;
    }
};

onMounted(() => {
    getUserData();
});

const {
    // Data
    statuses,
    units,
    jenisMedia,

    // Table
    paginatedItems,
    filteredItems,

    // Search & Filter
    searchQuery,
    statusFilter,

    // Pagination
    currentPage,
    totalPages,
    itemsPerPage,

    // Loading
    isLoading,
    isSubmitting,
    isDeleting,

    // Form Modal
    isModalOpen,
    isEditing,
    form,
    errors,

    // Detail Modal
    isDetailModalOpen,
    selectedDetailItem,

    // Delete Modal
    isDeleteModalOpen,
    selectedDeleteItem,

    // Modal Actions
    openAddModal,
    openDetailModal,
    openEditModal,
    openDeleteModal,

    closeModal,
    closeDetailModal,
    closeDeleteModal,

    // Submit / Delete
    saveForm,
    deleteData,

    // Pagination / Refresh
    setPage,
    refreshData,

    // Validation
    validatePengajuan,
    // Modal Status
    isStatusModalOpen,
    statusActionType,
    selectedStatusItem,
    isProcessingStatus,

    // Aksi Modal Status
    openStatusModal,
    closeStatusModal,
    confirmStatus,
} = usePengajuan();
</script>
