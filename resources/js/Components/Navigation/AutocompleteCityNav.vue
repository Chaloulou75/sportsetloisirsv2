<script setup>
import { ref, computed, watchEffect, onMounted } from "vue";
import { onClickOutside } from "@vueuse/core";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faLocationDot } from "@fortawesome/free-solid-svg-icons";
library.add(faLocationDot);

const emit = defineEmits(["update:model-value"]);
const props = defineProps({
    cities: Object,
    currentCity: Object,
});

const target = ref(null);
let isInputFocused = ref(false);

const handleComponentClickAway = () => {
    isInputFocused.value = false;
};

onClickOutside(target, handleComponentClickAway);

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

let searchTerm = ref(
    props.currentCity ? formatCityName(props.currentCity.ville) : ""
);
//
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

let selectedCity = ref(props.cities.find((city) => city.slug === city));

const selectCity = (city) => {
    selectedCity.value = city;
    searchTerm.value = formatCityName(selectedCity.value.ville);
    isInputFocused.value = false;
    emit("update:model-value", city.slug);
};

watchEffect(() => {
    if (searchTerm.value === "") {
        selectedCity.value = null;
        emit("update:model-value", null);
    }
});

onMounted(() => {
    selectedCity.value = ref(props.currentCity ? props.currentCity : null);
});
</script>
<template>
    <div class="flex w-full items-center justify-start md:w-1/2">
        <div ref="target" class="relative flex w-full">
            <span
                class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-2 text-3xl text-gray-400 md:px-3"
                ><font-awesome-icon icon="fa-solid fa-location-dot" />
                <!-- <MapPinIcon class="h-6 w-6 md:h-7 md:w-7"
            /> -->
            </span>

            <input
                type="text"
                v-model="searchTerm"
                @focus="isInputFocused = true"
                placeholder="Toulouse"
                class="block w-full flex-1 rounded-none rounded-r-md border border-gray-300 p-2 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg"
            />

            <ul
                v-if="searchCities.length && (!selectedCity || isInputFocused)"
                class="absolute z-10 mt-12 w-full space-y-1 rounded border border-gray-300 bg-white px-2 py-2"
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
