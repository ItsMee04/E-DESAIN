
import { ref, watch, onMounted, nextTick } from 'vue'
import { statuspengajuanService } from '../../statuspengajuan/services/statuspengajuanService.js'
import { useToast } from '../../../utilities/toast/useToast.js'

export const useLaporanFilter = (emit, tanggalMulaiRef, tanggalAkhirRef) => {
    const { toast } = useToast()

    // Data status pengajuan
    const statuses = ref([])
    const isLoadingStatus = ref(false)

    // State filter
    const tanggalMulai = ref('')
    const tanggalAkhir = ref('')
    const statusFilter = ref('')

    // State error validasi
    const errors = ref({
        tanggalMulai: '',
        tanggalAkhir: '',
    })

    // Ambil status dari API
    const getStatusPengajuan = async () => {
        isLoadingStatus.value = true

        try {
            const response =
                await statuspengajuanService.getStatusPengajuan()

            statuses.value = response.data || response
        } catch (error) {
            console.error(
                'Gagal mengambil data status pengajuan:',
                error
            )

            statuses.value = []
            toast.error('Gagal mengambil data status pengajuan')
        } finally {
            isLoadingStatus.value = false
        }
    }

    // Fokus ke field yang error
    const focusField = async (field) => {
        await nextTick()

        const target = field === 'tanggalMulai'
            ? tanggalMulaiRef.value
            : tanggalAkhirRef.value

        target?.focus?.()
    }

    // Validasi tanggal mulai
    const validateTanggalMulai = () => {
        if (!tanggalMulai.value) {
            errors.value.tanggalMulai = 'Tanggal mulai wajib diisi.'
            return false
        }

        errors.value.tanggalMulai = ''
        return true
    }

    // Validasi tanggal akhir
    const validateTanggalAkhir = () => {
        if (!tanggalAkhir.value) {
            errors.value.tanggalAkhir = 'Tanggal akhir wajib diisi.'
            return false
        }

        if (
            tanggalMulai.value &&
            tanggalAkhir.value < tanggalMulai.value
        ) {
            errors.value.tanggalAkhir =
                'Tanggal akhir tidak boleh sebelum tanggal mulai.'
            return false
        }

        errors.value.tanggalAkhir = ''
        return true
    }

    // Validasi otomatis saat tanggal berubah
    watch(tanggalMulai, () => {
        if (tanggalMulai.value) {
            errors.value.tanggalMulai = ''
        }

        if (
            tanggalMulai.value &&
            tanggalAkhir.value &&
            tanggalMulai.value > tanggalAkhir.value
        ) {
            errors.value.tanggalAkhir =
                'Tanggal akhir tidak boleh sebelum tanggal mulai.'
        } else if (tanggalAkhir.value) {
            errors.value.tanggalAkhir = ''
        }
    })

    watch(tanggalAkhir, () => {
        if (tanggalAkhir.value) {
            errors.value.tanggalAkhir = ''
        }

        if (
            tanggalMulai.value &&
            tanggalAkhir.value &&
            tanggalAkhir.value < tanggalMulai.value
        ) {
            errors.value.tanggalAkhir =
                'Tanggal akhir tidak boleh sebelum tanggal mulai.'
        }
    })

    // Terapkan filter
    const terapkanFilter = async () => {
        const tanggalMulaiValid = validateTanggalMulai()
        const tanggalAkhirValid = validateTanggalAkhir()

        if (!tanggalMulaiValid) {
            await focusField('tanggalMulai')
        } else if (!tanggalAkhirValid) {
            await focusField('tanggalAkhir')
        }

        if (!tanggalMulaiValid || !tanggalAkhirValid) {
            return
        }

        emit('apply', {
            tanggal_mulai: tanggalMulai.value,
            tanggal_akhir: tanggalAkhir.value,
            status_id: statusFilter.value || null,
        })
    }


    // Reset filter
    const resetFilter = () => {
        tanggalMulai.value = ''
        tanggalAkhir.value = ''
        statusFilter.value = ''

        errors.value = {
            tanggalMulai: '',
            tanggalAkhir: '',
        }

        emit('reset')
    }

    onMounted(() => {
        getStatusPengajuan()
    })

    return {
        statuses,
        isLoadingStatus,
        tanggalMulai,
        tanggalAkhir,
        statusFilter,
        errors,
        getStatusPengajuan,
        validateTanggalMulai,
        validateTanggalAkhir,
        terapkanFilter,
        resetFilter,
    }
}
