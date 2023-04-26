<script setup>
import { ref, computed, watch } from "vue";
const emit = defineEmits(["update:modelValue"]);
const props = defineProps({
    disciplines: Object,
    errors: Object,
    discipline: Number,
    selectedDiscipline: Object,
    disciplinesDejaUsed: Array,
});

let searchTerm = ref("");

const disciplinesDejaUsed = ref(props.disciplinesDejaUsed);

const searchDisciplines = computed(() => {
    if (searchTerm.value === "") {
        return [];
    }
    let matches = 0;
    return props.disciplines.filter((discipline) => {
        if (
            discipline.name
                .toLowerCase()
                .includes(searchTerm.value.toLowerCase()) &&
            matches < 10
        ) {
            matches++;
            return discipline;
        }
    });
});

let selectedDiscipline = ref(
    props.disciplines.find((discipline) => discipline.id === props.discipline)
);

watch(
    () => props.selectedDiscipline,
    (newVal) => {
        selectedDiscipline.value = newVal;
    }
);

const selectDiscipline = (discipline) => {
    selectedDiscipline.value = discipline;
    searchTerm.value = "";
    // Emit the selected discipline value to the parent component
    emit("update:modelValue", discipline.id);
};
</script>
<template>
    <div class="min-w-screen min-h-72 flex items-center justify-start">
        <div class="relative max-w-md space-y-3">
            <label for="search" class="text-lg font-medium text-gray-700">
                Taper le nom de la discipline à proposer:
            </label>

            <input
                type="text"
                id="search"
                v-model="searchTerm"
                placeholder="rugby, randonnées..."
                class="mb-0.5 w-full rounded border border-gray-300 p-3 placeholder-gray-400 placeholder-opacity-50 sm:text-sm"
            />

            <ul
                v-if="searchDisciplines.length"
                class="absolute z-10 w-full space-y-1 rounded border border-gray-300 bg-white px-4 py-2"
            >
                <li
                    class="border-b border-gray-200 px-1 pt-1 pb-2 text-sm font-medium text-gray-700"
                >
                    liste de {{ searchDisciplines.length }} de
                    {{ disciplines.length }} resultats
                </li>
                <li
                    v-for="discipline in searchDisciplines"
                    :key="discipline.id"
                    @click="selectDiscipline(discipline)"
                    :disabled="disciplinesDejaUsed.includes(discipline.id)"
                    class="cursor-pointer p-1 hover:bg-gray-100"
                >
                    {{ discipline.name }}
                </li>
            </ul>
            <p
                v-if="selectedDiscipline"
                class="pt-2 text-sm font-medium text-gray-700"
            >
                Vous avez selectionné:
                <span class="text-base font-semibold text-blue-700">{{
                    selectedDiscipline.name
                }}</span>
            </p>
        </div>
    </div>
</template>
