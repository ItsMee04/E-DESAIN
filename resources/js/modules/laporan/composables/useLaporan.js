
import { ref, watch } from 'vue'

export const useLaporanFilter = (emit) => {
    // State filter
    const tanggalMulai = ref('')
    const tanggalAkhir = ref('')
    const statusFilter = ref('')

    // Validasi rentang tanggal
    watch(tanggalMulai, (value) => {
        if (value && tanggalAkhir.value && value > tanggalAkhir.value) {
            tanggalAkhir.value = ''
        }
    })

    watch(tanggalAkhir, (value) => {
        if (value && tanggalMulai.value && value < tanggalMulai.value) {
            tanggalMulai.value = ''
        }
    })

    // Terapkan filter
    const terapkanFilter = () => {
        emit('apply', {
            tanggal_mulai: tanggalMulai.value || null,
            tanggal_akhir: tanggalAkhir.value || null,
            status_id: statusFilter.value || null,
        })
    }

    // Reset filter
    const resetFilter = () => {
        tanggalMulai.value = ''
        tanggalAkhir.value = ''
        statusFilter.value = ''

        emit('reset')
    }

    return {
        tanggalMulai,
        tanggalAkhir,
        statusFilter,
        terapkanFilter,
        resetFilter,
    }
}
