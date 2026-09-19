<template>
    <div ref="pickerRef" class="relative w-full">
        <!-- Label -->
        <label v-if="label" class="block text-xs font-semibold text-blue-950 mb-2">
            {{ label }}

            <span v-if="required" class="text-rose-600">
                *
            </span>
        </label>

        <!-- Input -->
        <div class="relative">
            <input ref="inputRef" :value="displayValue" type="text" :placeholder="placeholder" readonly
                class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-gray-200 bg-gray-50 text-xs text-blue-950 placeholder:text-blue-950/40 outline-none transition cursor-pointer focus:border-[#B20600] focus:bg-white focus:ring-2 focus:ring-[#B20600]/10"
                :class="{
                    'border-rose-500 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/5':
                        error,
                }" @click="togglePicker" />

            <!-- Calendar Icon -->
            <CalendarDays
                class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-950/40 pointer-events-none" />
        </div>

        <!-- Calendar -->
        <div v-if="isOpen"
            class="absolute z-[60] left-0 mt-1.5 w-[270px] bg-white border border-gray-100 rounded-xl shadow-lg shadow-blue-950/10 overflow-hidden">
            <!-- Calendar Header -->
            <div class="px-3 pt-3 pb-2">
                <div class="flex items-center justify-between">
                    <!-- Previous -->
                    <button type="button"
                        class="w-8 h-8 flex items-center justify-center rounded-full text-blue-950/60 hover:bg-[#B20600]/5 hover:text-[#B20600] transition cursor-pointer"
                        @click="previousMonth">
                        <ChevronLeft class="w-4 h-4" />
                    </button>

                    <!-- Month / Year -->
                    <div class="flex items-center gap-1">
                        <!-- MONTH DROPDOWN -->
                        <div class="relative">
                            <button type="button"
                                class="flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold text-blue-950 hover:bg-blue-950/[0.03] transition cursor-pointer"
                                @click.stop="toggleMonthDropdown">
                                {{ months[currentMonth] }}

                                <ChevronDown class="w-3 h-3 text-blue-950/40 transition-transform duration-200" :class="{
                                    'rotate-180': isMonthOpen,
                                }" />
                            </button>

                            <div v-if="isMonthOpen" ref="monthDropdownRef"
                                class="absolute z-[70] top-full left-0 mt-1 w-32 bg-white border border-gray-100 rounded-xl shadow-lg shadow-blue-950/10 overflow-hidden">
                                <div class="max-h-48 overflow-y-auto py-1">
                                    <button v-for="(
month, index
                                        ) in months" :key="month" type="button" :data-month="index"
                                        class="w-full flex items-center justify-between px-3 py-2 text-left text-[11px] transition"
                                        :class="currentMonth === index
                                                ? 'bg-[#B20600]/5 text-[#B20600] font-semibold'
                                                : 'text-blue-950/80 hover:bg-blue-950/[0.03]'
                                            " @click="selectMonth(index)">
                                        <span>{{ month }}</span>

                                        <span v-if="
                                            currentMonth === index
                                        "
                                            class="w-4 h-4 flex items-center justify-center rounded-full bg-[#B20600] text-white text-[9px]">
                                            ✓
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <span class="text-blue-950/20 text-xs">
                            /
                        </span>

                        <!-- YEAR DROPDOWN -->
                        <div class="relative">
                            <button type="button"
                                class="flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold text-blue-950 hover:bg-blue-950/[0.03] transition cursor-pointer"
                                @click.stop="toggleYearDropdown">
                                {{ currentYear }}

                                <ChevronDown class="w-3 h-3 text-blue-950/40 transition-transform duration-200" :class="{
                                    'rotate-180': isYearOpen,
                                }" />
                            </button>

                            <div v-if="isYearOpen" ref="yearDropdownRef"
                                class="absolute z-[70] top-full right-0 mt-1 w-24 bg-white border border-gray-100 rounded-xl shadow-lg shadow-blue-950/10 overflow-hidden">
                                <div class="max-h-48 overflow-y-auto py-1">
                                    <button v-for="year in years" :key="year" type="button" :data-year="year"
                                        class="w-full flex items-center justify-between px-3 py-2 text-left text-[11px] transition"
                                        :class="currentYear === year
                                                ? 'bg-[#B20600]/5 text-[#B20600] font-semibold'
                                                : 'text-blue-950/80 hover:bg-blue-950/[0.03]'
                                            " @click="selectYear(year)">
                                        <span>{{ year }}</span>

                                        <span v-if="
                                            currentYear === year
                                        "
                                            class="w-4 h-4 flex items-center justify-center rounded-full bg-[#B20600] text-white text-[9px]">
                                            ✓
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Next -->
                    <button type="button"
                        class="w-8 h-8 flex items-center justify-center rounded-full text-blue-950/60 hover:bg-[#B20600]/5 hover:text-[#B20600] transition cursor-pointer"
                        @click="nextMonth">
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-100"></div>

            <!-- Week -->
            <div class="px-3 pt-3">
                <div class="grid grid-cols-7">
                    <span v-for="day in weekDays" :key="day"
                        class="h-8 flex items-center justify-center text-[10px] font-semibold text-blue-950/40 uppercase">
                        {{ day }}
                    </span>
                </div>

                <!-- Days -->
                <div class="grid grid-cols-7 pb-3">
                    <button v-for="day in calendarDays" :key="day.key" type="button" :disabled="day.disabled"
                        class="relative h-9 w-9 mx-auto flex items-center justify-center rounded-full text-xs transition"
                        :class="getDayClass(day)" @click="selectDate(day)">
                        {{ day.date }}

                        <!-- Today dot -->
                        <span v-if="day.isToday && !day.isSelected"
                            class="absolute bottom-1 w-1 h-1 rounded-full bg-[#B20600]"></span>
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-100 px-4 py-2.5 flex justify-between items-center">
                <button type="button"
                    class="text-[11px] font-medium text-blue-950/50 hover:text-[#B20600] transition cursor-pointer"
                    @click="selectToday">
                    Hari ini
                </button>

                <button type="button"
                    class="text-[11px] font-medium text-blue-950/50 hover:text-[#B20600] transition cursor-pointer"
                    @click="clearDate">
                    Hapus
                </button>
            </div>
        </div>

        <!-- Error -->
        <p v-if="error" class="mt-1.5 text-[11px] text-rose-600">
            {{ error }}
        </p>
    </div>
</template>

<script setup>
import {
    ref,
    computed,
    watch,
    onMounted,
    onBeforeUnmount,
    nextTick,
} from "vue";

import {
    CalendarDays,
    ChevronLeft,
    ChevronRight,
    ChevronDown,
} from "lucide-vue-next";

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },

    label: {
        type: String,
        default: "",
    },

    placeholder: {
        type: String,
        default: "Pilih tanggal...",
    },

    error: {
        type: [String, Boolean],
        default: "",
    },

    required: {
        type: Boolean,
        default: false,
    },

    minDate: {
        type: String,
        default: "",
    },

    maxDate: {
        type: String,
        default: "",
    },
});

/*
|--------------------------------------------------------------------------
| Emits
|--------------------------------------------------------------------------
*/

const emit = defineEmits([
    "update:modelValue",
    "blur",
]);

/*
|--------------------------------------------------------------------------
| Refs
|--------------------------------------------------------------------------
*/

const pickerRef = ref(null);
const inputRef = ref(null);
const monthDropdownRef = ref(null);
const yearDropdownRef = ref(null);

const isOpen = ref(false);
const isMonthOpen = ref(false);
const isYearOpen = ref(false);

/*
|--------------------------------------------------------------------------
| Month & Week
|--------------------------------------------------------------------------
*/

const months = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
];

const weekDays = [
    "Sen",
    "Sel",
    "Rab",
    "Kam",
    "Jum",
    "Sab",
    "Min",
];

/*
|--------------------------------------------------------------------------
| Current Calendar
|--------------------------------------------------------------------------
*/

const getToday = () => new Date();

const initialToday = getToday();

const currentMonth = ref(initialToday.getMonth());
const currentYear = ref(initialToday.getFullYear());

/*
|--------------------------------------------------------------------------
| Month Dropdown
|--------------------------------------------------------------------------
*/

const toggleMonthDropdown = async () => {
    isMonthOpen.value = !isMonthOpen.value;
    isYearOpen.value = false;

    if (isMonthOpen.value) {
        await nextTick();

        const selectedMonth =
            monthDropdownRef.value?.querySelector(
                `[data-month="${currentMonth.value}"]`
            );

        selectedMonth?.scrollIntoView({
            block: "nearest",
        });
    }
};

const selectMonth = (month) => {
    currentMonth.value = month;
    isMonthOpen.value = false;
};

/*
|--------------------------------------------------------------------------
| Year Dropdown
|--------------------------------------------------------------------------
*/

const toggleYearDropdown = async () => {
    isYearOpen.value = !isYearOpen.value;
    isMonthOpen.value = false;

    if (isYearOpen.value) {
        await nextTick();

        const selectedYear =
            yearDropdownRef.value?.querySelector(
                `[data-year="${currentYear.value}"]`
            );

        selectedYear?.scrollIntoView({
            block: "nearest",
        });
    }
};

const selectYear = (year) => {
    currentYear.value = year;
    isYearOpen.value = false;
};

/*
|--------------------------------------------------------------------------
| Years
|--------------------------------------------------------------------------
*/

const years = computed(() => {
    const current = getToday().getFullYear();
    const result = [];

    for (
        let year = current;
        year >= current - 100;
        year--
    ) {
        result.push(year);
    }

    return result;
});

/*
|--------------------------------------------------------------------------
| Helper Date
|--------------------------------------------------------------------------
*/

const pad = (value) => {
    return String(value).padStart(2, "0");
};

const formatDate = (year, month, date) => {
    return `${year}-${pad(month + 1)}-${pad(date)}`;
};

const parseDate = (value) => {
    if (!value) {
        return null;
    }

    const parts = value.split("-");

    if (parts.length !== 3) {
        return null;
    }

    return new Date(
        Number(parts[0]),
        Number(parts[1]) - 1,
        Number(parts[2])
    );
};

/*
|--------------------------------------------------------------------------
| Display Value
|--------------------------------------------------------------------------
*/

const displayValue = computed(() => {
    const date = parseDate(props.modelValue);

    if (!date) {
        return "";
    }

    return `${pad(date.getDate())} ${months[date.getMonth()]
        } ${date.getFullYear()}`;
});

/*
|--------------------------------------------------------------------------
| Calendar Days
|--------------------------------------------------------------------------
*/

const calendarDays = computed(() => {
    const days = [];

    const firstDay = new Date(
        currentYear.value,
        currentMonth.value,
        1
    );

    const lastDay = new Date(
        currentYear.value,
        currentMonth.value + 1,
        0
    );

    /*
     * JavaScript:
     * Minggu = 0
     * Senin = 1
     *
     * Kita ubah supaya:
     * Senin = 0
     * Minggu = 6
     */

    const firstWeekDay =
        firstDay.getDay() === 0
            ? 6
            : firstDay.getDay() - 1;

    /*
     * Hari bulan sebelumnya
     */

    const previousMonthLastDay = new Date(
        currentYear.value,
        currentMonth.value,
        0
    ).getDate();

    for (
        let i = firstWeekDay - 1;
        i >= 0;
        i--
    ) {
        const date = previousMonthLastDay - i;

        const previousMonth =
            currentMonth.value === 0
                ? 11
                : currentMonth.value - 1;

        const previousYear =
            currentMonth.value === 0
                ? currentYear.value - 1
                : currentYear.value;

        const value = formatDate(
            previousYear,
            previousMonth,
            date
        );

        days.push({
            key: `prev-${value}`,
            date,
            month: previousMonth,
            year: previousYear,
            value,
            currentMonth: false,
            disabled: true,
            isToday: false,
            isSelected: false,
        });
    }

    /*
     * Hari bulan sekarang
     */

    for (
        let date = 1;
        date <= lastDay.getDate();
        date++
    ) {
        const value = formatDate(
            currentYear.value,
            currentMonth.value,
            date
        );

        days.push({
            key: `current-${value}`,
            date,
            month: currentMonth.value,
            year: currentYear.value,
            value,
            currentMonth: true,
            disabled: isDateDisabled(value),
            isToday: value === formatToday(),
            isSelected:
                value === props.modelValue,
        });
    }

    /*
     * Hari bulan berikutnya
     *
     * Supaya kalender selalu memiliki
     * 6 baris x 7 hari = 42 cell.
     */

    const remaining = 42 - days.length;

    for (
        let date = 1;
        date <= remaining;
        date++
    ) {
        const nextMonth =
            currentMonth.value === 11
                ? 0
                : currentMonth.value + 1;

        const nextYear =
            currentMonth.value === 11
                ? currentYear.value + 1
                : currentYear.value;

        const value = formatDate(
            nextYear,
            nextMonth,
            date
        );

        days.push({
            key: `next-${value}`,
            date,
            month: nextMonth,
            year: nextYear,
            value,
            currentMonth: false,
            disabled: true,
            isToday: false,
            isSelected: false,
        });
    }

    return days;
});

/*
|--------------------------------------------------------------------------
| Today
|--------------------------------------------------------------------------
*/

const formatToday = () => {
    const now = getToday();

    return formatDate(
        now.getFullYear(),
        now.getMonth(),
        now.getDate()
    );
};

/*
|--------------------------------------------------------------------------
| Date Disabled
|--------------------------------------------------------------------------
*/

const isDateDisabled = (value) => {
    if (
        props.minDate &&
        value < props.minDate
    ) {
        return true;
    }

    if (
        props.maxDate &&
        value > props.maxDate
    ) {
        return true;
    }

    return false;
};

/*
|--------------------------------------------------------------------------
| Day Class
|--------------------------------------------------------------------------
*/

const getDayClass = (day) => {
    if (day.disabled) {
        return "text-blue-950/20 cursor-not-allowed";
    }

    if (day.isSelected) {
        return "bg-[#B20600] text-white font-semibold hover:bg-[#8E0500]";
    }

    if (day.isToday) {
        return "text-[#B20600] font-semibold hover:bg-[#B20600]/5";
    }

    return "text-blue-950/80 hover:bg-[#B20600]/5 hover:text-[#B20600]";
};

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const previousMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else {
        currentMonth.value--;
    }
};

const nextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value++;
    } else {
        currentMonth.value++;
    }
};

/*
|--------------------------------------------------------------------------
| Select Date
|--------------------------------------------------------------------------
*/

const selectDate = (day) => {
    if (day.disabled) {
        return;
    }

    emit("update:modelValue", day.value);

    isOpen.value = false;
    isMonthOpen.value = false;
    isYearOpen.value = false;
};

/*
|--------------------------------------------------------------------------
| Select Today
|--------------------------------------------------------------------------
*/

const selectToday = () => {
    const now = getToday();

    const value = formatDate(
        now.getFullYear(),
        now.getMonth(),
        now.getDate()
    );

    if (isDateDisabled(value)) {
        return;
    }

    emit("update:modelValue", value);

    currentMonth.value = now.getMonth();
    currentYear.value = now.getFullYear();

    isOpen.value = false;
    isMonthOpen.value = false;
    isYearOpen.value = false;
};

/*
|--------------------------------------------------------------------------
| Clear
|--------------------------------------------------------------------------
*/

const clearDate = () => {
    emit("update:modelValue", "");

    isOpen.value = false;
    isMonthOpen.value = false;
    isYearOpen.value = false;

    /*
     * Setelah tanggal dihapus,
     * kalender dikembalikan ke bulan & tahun sekarang.
     */

    const now = getToday();

    currentMonth.value = now.getMonth();
    currentYear.value = now.getFullYear();
};

/*
|--------------------------------------------------------------------------
| Open / Close
|--------------------------------------------------------------------------
*/

const togglePicker = () => {
    isOpen.value = !isOpen.value;

    /*
     * Jika ditutup
     */

    if (!isOpen.value) {
        isMonthOpen.value = false;
        isYearOpen.value = false;

        return;
    }

    /*
     * Reset dropdown
     */

    isMonthOpen.value = false;
    isYearOpen.value = false;

    /*
     * Jika sudah ada tanggal yang dipilih,
     * tampilkan bulan & tahun tanggal tersebut.
     */

    const selected = parseDate(
        props.modelValue
    );

    if (selected) {
        currentMonth.value = selected.getMonth();
        currentYear.value = selected.getFullYear();

        return;
    }

    /*
     * Jika belum ada tanggal,
     * tampilkan bulan & tahun sekarang.
     */

    const now = getToday();

    currentMonth.value = now.getMonth();
    currentYear.value = now.getFullYear();
};

/*
|--------------------------------------------------------------------------
| Update Calendar ketika v-model berubah
|--------------------------------------------------------------------------
*/

watch(
    () => props.modelValue,
    (value) => {
        const selected = parseDate(value);

        if (!selected) {
            return;
        }

        currentMonth.value = selected.getMonth();
        currentYear.value = selected.getFullYear();
    }
);

/*
|--------------------------------------------------------------------------
| Click Outside
|--------------------------------------------------------------------------
*/

const handleClickOutside = (event) => {
    if (
        pickerRef.value &&
        !pickerRef.value.contains(event.target)
    ) {
        isOpen.value = false;
        isMonthOpen.value = false;
        isYearOpen.value = false;
    }
};

/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(() => {
    document.addEventListener(
        "mousedown",
        handleClickOutside
    );
});

onBeforeUnmount(() => {
    document.removeEventListener(
        "mousedown",
        handleClickOutside
    );
});
</script>
