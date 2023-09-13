<script setup>
import { ref, computed, watchEffect } from "vue";
const emit = defineEmits(["update:model-value"]);
const props = defineProps({
    disciplines: Object,
});

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

let selectedDiscipline = ref(null);

const selectDiscipline = (discipline) => {
    selectedDiscipline.value = discipline;
    searchTerm.value = selectedDiscipline.value.name;
    // emit the selected discipline ID
    emit("update:model-value", discipline.slug);
};

watchEffect(() => {
    if (searchTerm.value === "") {
        selectedDiscipline.value = null;
    }
});
</script>
<template>
    <div class="flex w-full items-center justify-start md:w-auto">
        <div class="relative w-full md:w-auto">
            <input
                type="text"
                id="search"
                v-model="searchTerm"
                placeholder="rugby, randonnées..."
                class="mb-0.5 w-full rounded border border-gray-300 p-2 placeholder-gray-400 placeholder-opacity-50 sm:text-sm"
            />

            <ul
                v-if="searchDisciplines.length && !selectedDiscipline"
                class="absolute z-10 w-full space-y-1 rounded border border-gray-300 bg-white px-2 py-2"
            >
                <li
                    class="border-b border-gray-200 px-1 pb-2 pt-1 text-sm font-medium text-gray-700"
                >
                    liste de {{ searchDisciplines.length }} de
                    {{ disciplines.length }} resultats
                </li>
                <li
                    v-for="discipline in searchDisciplines"
                    :key="discipline.id"
                    @click="selectDiscipline(discipline)"
                    class="cursor-pointer p-1 text-sm hover:bg-indigo-200"
                >
                    {{ discipline.name }}
                </li>
            </ul>
        </div>
    </div>
</template>
