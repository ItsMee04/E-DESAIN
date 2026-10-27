<template>
    <BaseModal :is-open="isOpen" title="Hak Akses Pengguna"
        :description="user ? (user?.pegawai?.nama || user?.pegawai?.nip) : 'Atur hak akses pengguna'"
        :is-submitting="isSubmitting" @close="closeModal">
        <!-- Modal Body -->
        <div class="p-6">
            <!-- Loading -->
            <div v-if="isModuleLoading" class="flex items-center justify-center py-10">
                <svg class="animate-spin h-6 w-6 text-[#B20600]" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                </svg>
            </div>

            <!-- Daftar Hak Akses -->
            <div v-else class="space-y-1">
                <!-- DASHBOARD -->
                <div v-if="dashboardModule" class="flex items-center gap-3 py-2">
                    <input v-model="permissions.dashboard" type="checkbox"
                        class="w-4 h-4 rounded border-gray-300 text-[#B20600] accent-[#B20600] focus:ring-[#B20600] cursor-pointer" />

                    <span class="text-sm text-gray-700">
                        {{ dashboardModule.name }}
                    </span>
                </div>

                <!-- MASTER DATA -->
                <div v-if="masterModule" class="mt-2">
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center gap-2">
                            <button type="button" @click="toggleModule('master')"
                                class="p-0.5 text-gray-500 hover:text-blue-950 transition cursor-pointer">
                                <ChevronDown v-if="expandedModules.master" :size="17" />
                                <ChevronRight v-else :size="17" />
                            </button>

                            <input ref="masterCheckbox" type="checkbox" :checked="isMasterChecked"
                                @change="toggleMaster"
                                class="w-4 h-4 rounded border-gray-300 text-[#B20600] accent-[#B20600] focus:ring-[#B20600] cursor-pointer" />

                            <span class="text-sm font-medium text-gray-700">
                                {{ masterModule.name }}
                            </span>
                        </div>
                    </div>

                    <div v-if="expandedModules.master" class="ml-6 border-l border-gray-100 pl-4">
                        <label v-for="item in masterModule.children" :key="item.id"
                            class="flex items-center gap-3 py-1.5 cursor-pointer">
                            <input v-model="permissions.masterChildren" type="checkbox" :value="item.id"
                                class="w-4 h-4 rounded border-gray-300 text-[#B20600] accent-[#B20600] focus:ring-[#B20600] cursor-pointer" />

                            <span class="text-sm text-gray-600">
                                {{ item.name }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- MANAGEMENT USER -->
                <div v-if="managementModule" class="mt-2">
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center gap-2">
                            <button type="button" @click="toggleModule('management')"
                                class="p-0.5 text-gray-500 hover:text-blue-950 transition cursor-pointer">
                                <ChevronDown v-if="expandedModules.management" :size="17" />
                                <ChevronRight v-else :size="17" />
                            </button>

                            <input ref="managementCheckbox" type="checkbox" :checked="isManagementChecked"
                                @change="toggleManagement"
                                class="w-4 h-4 rounded border-gray-300 text-[#B20600] accent-[#B20600] focus:ring-[#B20600] cursor-pointer" />

                            <span class="text-sm font-medium text-gray-700">
                                {{ managementModule.name }}
                            </span>
                        </div>
                    </div>

                    <div v-if="expandedModules.management" class="ml-6 border-l border-gray-100 pl-4">
                        <label v-for="item in managementModule.children" :key="item.id"
                            class="flex items-center gap-3 py-1.5 cursor-pointer">
                            <input v-model="permissions.managementChildren" type="checkbox" :value="item.id"
                                class="w-4 h-4 rounded border-gray-300 text-[#B20600] accent-[#B20600] focus:ring-[#B20600] cursor-pointer" />

                            <span class="text-sm text-gray-600">
                                {{ item.name }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- PENGAJUAN -->
                <div v-if="pengajuanModule" class="mt-2">
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center gap-2">
                            <button type="button" @click="toggleModule('pengajuan')"
                                class="p-0.5 text-gray-500 hover:text-blue-950 transition cursor-pointer">
                                <ChevronDown v-if="expandedModules.pengajuan" :size="17" />
                                <ChevronRight v-else :size="17" />
                            </button>

                            <input ref="pengajuanCheckbox" type="checkbox" :checked="isPengajuanChecked"
                                @change="togglePengajuan"
                                class="w-4 h-4 rounded border-gray-300 text-[#B20600] accent-[#B20600] focus:ring-[#B20600] cursor-pointer" />

                            <span class="text-sm font-medium text-gray-700">
                                {{ pengajuanModule.name }}
                            </span>
                        </div>
                    </div>

                    <div v-if="expandedModules.pengajuan" class="ml-6 border-l border-gray-100 pl-4">
                        <label v-for="item in pengajuanModule.children" :key="item.id"
                            class="flex items-center gap-3 py-1.5 cursor-pointer">
                            <input v-model="permissions.pengajuanChildren" type="checkbox" :value="item.id"
                                class="w-4 h-4 rounded border-gray-300 text-[#B20600] accent-[#B20600] focus:ring-[#B20600] cursor-pointer" />

                            <span class="text-sm text-gray-600">
                                {{ item.name }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- MODUL LAIN -->
                <div v-for="module in otherModules" :key="module.id" class="flex items-center gap-3 py-2">
                    <input v-model="permissions.other" type="checkbox" :value="module.id"
                        class="w-4 h-4 rounded border-gray-300 text-[#B20600] accent-[#B20600] focus:ring-[#B20600] cursor-pointer" />

                    <span class="text-sm text-gray-700">
                        {{ module.name }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <template #footer>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="closeModal" :disabled="isSubmitting"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal
                </button>

                <button type="button" @click="savePermission" :disabled="isSubmitting || isModuleLoading"
                    class="px-4 py-2.5 rounded-xl bg-[#B20600] hover:bg-[#8E0500] text-white text-xs font-semibold transition shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
                    <span class="flex items-center gap-2">
                        <svg v-if="isSubmitting" class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>

                        <span>
                            {{ isSubmitting ? "Menyimpan..." : "Simpan" }}
                        </span>
                    </span>
                </button>
            </div>
        </template>
    </BaseModal>
</template>

<script setup>
import BaseModal from "../../../utilities/common/BaseModal.vue";
import { reactive, ref, computed, watch, nextTick } from "vue";
import { ChevronRight, ChevronDown } from "lucide-vue-next";
import { useModule } from "../composables/useModule.js";

// =====================================================
// PROPS
// =====================================================

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },

    user: {
        type: Object,
        default: null,
    },

    permissions: {
        type: Array,
        default: () => []
    },

    isSubmitting: {
        type: Boolean,
        default: false,
    },
});

// =====================================================
// EMITS
// =====================================================

const emit = defineEmits(["close", "save"]);

// =====================================================
// MODULE
// =====================================================

const { modules, isLoading: isModuleLoading, getModules } = useModule();

// =====================================================
// EXPANDED MODULE
// =====================================================

const expandedModules = reactive({
    master: false,
    management: false,
    pengajuan: false,
});

// =====================================================
// PERMISSIONS
// =====================================================

const permissions = reactive({
    dashboard: false,
    masterChildren: [],
    managementChildren: [],
    pengajuanChildren: [],
    other: [],
});

const setPermissions = (moduleIds) => {

    // RESET
    permissions.dashboard = false;
    permissions.masterChildren = [];
    permissions.managementChildren = [];
    permissions.pengajuanChildren = [];
    permissions.other = [];

    // RESET EXPAND
    expandedModules.master = false;
    expandedModules.management = false;
    expandedModules.pengajuan = false;

    if (!moduleIds || !moduleIds.length) {
        return;
    }

    // =====================================================
    // DASHBOARD
    // =====================================================

    if (
        dashboardModule.value &&
        moduleIds.includes(dashboardModule.value.id)
    ) {
        permissions.dashboard = true;
    }

    // =====================================================
    // MASTER DATA
    // =====================================================

    if (masterModule.value) {

        permissions.masterChildren =
            (masterModule.value.children || [])
                .filter(child => moduleIds.includes(child.id))
                .map(child => child.id);

        // AUTO EXPAND
        if (permissions.masterChildren.length > 0) {
            expandedModules.master = true;
        }
    }

    // =====================================================
    // MANAGEMENT USER
    // =====================================================

    if (managementModule.value) {

        permissions.managementChildren =
            (managementModule.value.children || [])
                .filter(child => moduleIds.includes(child.id))
                .map(child => child.id);

        // AUTO EXPAND
        if (permissions.managementChildren.length > 0) {
            expandedModules.management = true;
        }
    }

    // =====================================================
    // PENGAJUAN
    // =====================================================

    if (pengajuanModule.value) {

        permissions.pengajuanChildren =
            (pengajuanModule.value.children || [])
                .filter(child => moduleIds.includes(child.id))
                .map(child => child.id);

        // AUTO EXPAND
        if (permissions.pengajuanChildren.length > 0) {
            expandedModules.pengajuan = true;
        }
    }

    // =====================================================
    // MODULE LAIN
    // =====================================================

    permissions.other =
        otherModules.value
            .filter(module => moduleIds.includes(module.id))
            .map(module => module.id);
};

// =====================================================
// CHECKBOX REFS
// =====================================================

const masterCheckbox = ref(null);
const managementCheckbox = ref(null);
const pengajuanCheckbox = ref(null);

// =====================================================
// FIND MODULE
// =====================================================

const dashboardModule = computed(() => {
    return modules.value.find((module) => module.key === "dashboard");
});

const masterModule = computed(() => {
    return modules.value.find((module) => module.key === "master");
});

const managementModule = computed(() => {
    return modules.value.find((module) => module.key === "management_user");
});

const pengajuanModule = computed(() => {
    return modules.value.find((module) => module.key === "pengajuan");
});

// =====================================================
// MODULE LAIN
// =====================================================

const otherModules = computed(() => {
    return modules.value.filter((module) => {
        return (
            module.key !== "dashboard" &&
            module.key !== "master" &&
            module.key !== "management_user" &&
            module.key !== "pengajuan"
        );
    });
});

// =====================================================
// MASTER CHECKED
// =====================================================

const isMasterChecked = computed(() => {
    if (!masterModule.value) {
        return false;
    }

    const children = masterModule.value.children || [];

    return (
        children.length > 0 &&
        permissions.masterChildren.length === children.length
    );
});

// =====================================================
// MANAGEMENT CHECKED
// =====================================================

const isManagementChecked = computed(() => {
    if (!managementModule.value) {
        return false;
    }

    const children = managementModule.value.children || [];

    return (
        children.length > 0 &&
        permissions.managementChildren.length === children.length
    );
});

// =====================================================
// PENGAJUAN CHECKED
// =====================================================

const isPengajuanChecked = computed(() => {
    if (!pengajuanModule.value) {
        return false;
    }

    const children = pengajuanModule.value.children || [];

    return (
        children.length > 0 &&
        permissions.pengajuanChildren.length === children.length
    );
});

// =====================================================
// TOGGLE MASTER
// =====================================================

const toggleMaster = () => {
    if (!masterModule.value) {
        return;
    }

    const children = masterModule.value.children || [];

    const allChecked = permissions.masterChildren.length === children.length;

    if (allChecked) {
        permissions.masterChildren = [];
    } else {
        permissions.masterChildren = children.map((item) => item.id);
    }
};

// =====================================================
// TOGGLE MANAGEMENT
// =====================================================

const toggleManagement = () => {
    if (!managementModule.value) {
        return;
    }

    const children = managementModule.value.children || [];

    const allChecked =
        permissions.managementChildren.length === children.length;

    if (allChecked) {
        permissions.managementChildren = [];
    } else {
        permissions.managementChildren = children.map((item) => item.id);
    }
};

// =====================================================
// TOGGLE PENGAJUAN
// =====================================================
const togglePengajuan = () => {
    if (!pengajuanModule.value) {
        return;
    }

    const children = pengajuanModule.value.children || [];

    const allChecked = permissions.pengajuanChildren.length === children.length;

    if (allChecked) {
        permissions.pengajuanChildren = [];
    } else {
        permissions.pengajuanChildren = children.map((item) => item.id);
    }
};

// =====================================================
// INDTERMINATE MASTER
// =====================================================

watch(
    () => permissions.masterChildren,
    (value) => {
        nextTick(() => {
            if (!masterCheckbox.value || !masterModule.value) {
                return;
            }

            const total = masterModule.value.children?.length || 0;

            masterCheckbox.value.indeterminate =
                value.length > 0 && value.length < total;
        });
    },
    {
        deep: true,
        immediate: true,
    },
);

// =====================================================
// INDETERMINATE MANAGEMENT
// =====================================================

watch(
    () => permissions.managementChildren,
    (value) => {
        nextTick(() => {
            if (!managementCheckbox.value || !managementModule.value) {
                return;
            }

            const total = managementModule.value.children?.length || 0;

            managementCheckbox.value.indeterminate =
                value.length > 0 && value.length < total;
        });
    },
    {
        deep: true,
        immediate: true,
    },
);

watch(
    [() => props.permissions, modules],
    ([newPermissions, newModules]) => {

        if (!newModules.length) {
            return;
        }

        setPermissions(newPermissions);
    },
    {
        immediate: true,
        deep: true
    }
);

// =====================================================
// INDETERMINATE PENGAJUAN
// =====================================================

watch(
    () => permissions.pengajuanChildren,
    (value) => {
        nextTick(() => {
            if (!pengajuanCheckbox.value || !pengajuanModule.value) {
                return;
            }

            const total = pengajuanModule.value.children?.length || 0;

            pengajuanCheckbox.value.indeterminate =
                value.length > 0 && value.length < total;
        });
    },
    {
        deep: true,
        immediate: true,
    },
);

// =====================================================
// TOGGLE EXPAND MODULE
// =====================================================

const toggleModule = (module) => {
    expandedModules[module] = !expandedModules[module];
};

// =====================================================
// CLOSE
// =====================================================

const closeModal = () => {
    if (props.isSubmitting) {
        return;
    }

    emit("close");
};

// =====================================================
// SAVE
// =====================================================

const savePermission = () => {

    const moduleIds = [
        ...(permissions.dashboard && dashboardModule.value
            ? [dashboardModule.value.id]
            : []),

        ...permissions.masterChildren,

        ...permissions.managementChildren,

        ...permissions.pengajuanChildren,

        ...permissions.other
    ];

    const payload = {
        module_ids: [...new Set(moduleIds)]
    };

    emit('save', payload);
};

// =====================================================
// LOAD MODULE
// =====================================================

watch(
    () => props.isOpen,
    async (value) => {

        if (!value) {
            return;
        }

        await getModules();

        setPermissions(props.permissions);
    }
);
</script>
