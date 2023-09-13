<script setup>
import { ref, computed, watchEffect } from "vue";
const emit = defineEmits(["update:model-value"]);
const props = defineProps({
    cities: Object,
});

let searchTerm = ref("");

const searchCities = computed(() => {
    if (searchTerm.value === "") {
        return [];
    }
    let matches = 0;
    return props.cities.filter((city) => {
        if (
            city.ville.toLowerCase().includes(searchTerm.value.toLowerCase()) &&
            matches < 10
        ) {
            matches++;
            return city;
        }
    });
});

let selectedCity = ref(props.cities.find((city) => city.id === city));

const selectCity = (city) => {
    selectedCity.value = city;
    searchTerm.value = formatCityName(selectedCity.value.ville);
    emit("update:model-value", city.id);
};

watchEffect(() => {
    if (searchTerm.value === "") {
        selectedCity.value = null; // Reset selectedCity to null
    }
});

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};
</script>
<template>
    <div class="flex w-full items-center justify-start md:w-auto">
        <div class="relative w-full md:w-auto">
            <input
                type="text"
                id="localite"
                v-model="searchTerm"
                placeholder="Toulouse"
                class="mb-0.5 w-full rounded border border-gray-300 p-2 placeholder-gray-400 placeholder-opacity-50 sm:text-sm"
            />

            <ul
                v-if="searchCities.length && !selectedCity"
                class="absolute z-10 w-full space-y-1 rounded border border-gray-300 bg-white px-2 py-2"
            >
                <li
                    class="border-b border-gray-200 px-1 pb-2 pt-1 text-sm font-medium text-gray-700"
                >
                    liste de {{ searchCities.length }} de
                    {{ cities.length }} resultats
                </li>
                <li
                    v-for="city in searchCities"
                    :key="city.id"
                    @click="selectCity(city)"
                    class="cursor-pointer p-1 text-sm hover:bg-indigo-100"
                >
                    {{ formatCityName(city.ville) }}
                </li>
            </ul>
        </div>
    </div>
</template>