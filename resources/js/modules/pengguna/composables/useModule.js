import { ref } from 'vue';
import { moduleService } from '../services/moduleService';

export function useModule() {

    const modules = ref([]);
    const isLoading = ref(false);

    const getModules = async () => {

        isLoading.value = true;

        try {
            const response = await moduleService.getModules();
            modules.value = response?.data || [];

        } catch (error) {
            console.error(
                'Gagal mengambil data module:',
                error
            );
            modules.value = [];
        } finally {
            isLoading.value = false;
        }
    };

    return {
        modules,
        isLoading,
        getModules
    };
}
