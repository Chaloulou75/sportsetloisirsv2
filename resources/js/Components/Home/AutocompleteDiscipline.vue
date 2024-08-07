<script setup>
import { ref, computed, watchEffect } from "vue";
import { onClickOutside } from "@vueuse/core";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faQuestion } from "@fortawesome/free-solid-svg-icons";
library.add(faQuestion);

const emit = defineEmits(["update:model-value"]);
const props = defineProps({
    disciplines: Object,
});

let searchTerm = ref("");

const target = ref(null);
let isInputFocused = ref(false);

const handleComponentClickAway = () => {
    isInputFocused.value = false;
};

onClickOutside(target, handleComponentClickAway);

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
    isInputFocused.value = false;
    emit("update:model-value", discipline.slug);
};

watchEffect(() => {
    if (searchTerm.value === "") {
        selectedDiscipline.value = null;
        emit("update:model-value", null);
    }
});
</script>
<template>
    <div
        ref="target"
        class="flex w-full flex-col items-start justify-start md:w-full"
    >
        <label for="discipline" class="text-lg font-medium text-gray-700">
            Rechercher une discipline:
            <span
                v-if="selectedDiscipline"
                class="text-base font-semibold text-blue-700"
                >{{ selectedDiscipline.name }}</span
            >
        </label>
        <div class="relative flex w-full">
            <span
                class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-2 text-3xl text-gray-400 md:px-3"
                ><font-awesome-icon icon="fa-solid fa-question" />
            </span>
            <input
                type="text"
                id="discipline"
                v-model="searchTerm"
                @focus="isInputFocused = true"
                placeholder="rugby, randonnées..."
                class="block w-full flex-1 rounded-none rounded-r-md border border-gray-300 p-2 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg"
            />

            <ul
                v-if="
                    searchDisciplines.length &&
                    (!selectedDiscipline || isInputFocused)
                "
                class="absolute z-10 mt-12 w-full space-y-1 rounded border border-gray-300 bg-white px-2 py-2"
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
                    class="cursor-pointer p-1 text-sm hover:bg-indigo-100"
                >
                    {{ discipline.name }}
                </li>
            </ul>
        </div>
    </div>
</template>
