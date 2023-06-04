<script setup>
import { ref, computed, watch } from "vue";
const emit = defineEmits(['update:model-value', 'update:discipline']);
const props = defineProps({
    disciplines: Object,
    errors: Object,
    discipline: Number,
    selectedDiscipline: Object,
    dejaUsedDisciplines: Array,
});

const dejaUsedDisciplinesRef = computed(() => props.dejaUsedDisciplines);

let searchTerm = ref("");

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
    // Add the selected discipline ID to dejaUsedDisciplinesRef
    // dejaUsedDisciplinesRef.value.push(discipline.id);
    emit('update:model-value', discipline.id);
};

</script>
<template>
    <div class="flex items-center justify-start min-w-screen min-h-72">
        <div class="relative max-w-md space-y-3">
            <label for="search" class="text-lg font-medium text-gray-700">
                Taper le nom de la discipline à proposer:
            </label>

            <input type="text" id="search" v-model="searchTerm" placeholder="rugby, randonnées..."
                class="mb-0.5 w-full rounded border border-gray-300 p-3 placeholder-gray-400 placeholder-opacity-50 sm:text-sm" />

            <ul v-if="searchDisciplines.length"
                class="absolute z-10 w-full px-4 py-2 space-y-1 bg-white border border-gray-300 rounded">
                <li class="px-1 pt-1 pb-2 text-sm font-medium text-gray-700 border-b border-gray-200">
                    liste de {{ searchDisciplines.length }} de
                    {{ disciplines.length }} resultats
                </li>
                <li v-for="discipline in searchDisciplines" :key="discipline.id" @click="selectDiscipline(discipline)"
                    :class="{
                        'pointer-events-none text-gray-400':
                            dejaUsedDisciplinesRef.includes(discipline.id),
                    }" class="p-1 cursor-pointer hover:bg-blue-200">
                    {{ discipline.name }}
                    <span v-if="dejaUsedDisciplinesRef.includes(discipline.id)" class="text-xs italic text-gray-400">(déjà
                        sélectionné)</span>
                </li>
            </ul>
            <p v-if="selectedDiscipline" class="pt-2 text-sm font-medium text-gray-700">
                Vous avez selectionné:
                <span class="text-base font-semibold text-blue-700">{{
                    selectedDiscipline.name
                }}</span>
            </p>
        </div>
    </div>
</template>
