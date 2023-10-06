<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, nextTick, watch, onMounted } from "vue";
import { useCookies } from "@vueuse/integrations/useCookies";
import { BookmarkIcon, MapPinIcon } from "@heroicons/vue/24/outline";
import { HeartIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    structure: Object,
    link: {
        type: String,
        default: null,
    },
    data: {
        type: Object,
        default: null,
    },
});

const getUniqueActivitesDiscipline = (activites) => {
    const uniqueNames = new Set();
    return activites.filter((activite) => {
        if (!uniqueNames.has(activite.discipline.name)) {
            uniqueNames.add(activite.discipline.name);
            return true;
        }
        return false;
    });
};

const getUniqueActivitesTitre = (activites) => {
    const uniqueNames = new Set();
    return activites.filter((activite) => {
        if (!uniqueNames.has(activite.titre)) {
            uniqueNames.add(activite.titre);
            return true;
        }
        return false;
    });
};

const formatCurrency = (value) => {
    const numericValue = Number(value.replace(/[^0-9.-]+/g, ""));
    if (!isNaN(numericValue)) {
        if (numericValue % 1 === 0) {
            return numericValue.toLocaleString() + " €";
        } else {
            return numericValue.toFixed(2) + " €";
        }
    }
    return value;
};

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};
</script>

<template>
    <div class="block rounded-lg bg-gray-50 px-0 shadow-sm shadow-sky-700">
        <div class="relative">
            <!-- Image -->
            <img
                alt="Home"
                src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                class="h-24 w-full rounded-md object-cover"
            />
        </div>

        <div class="flex flex-col px-2 py-1">
            <div
                class="text-center text-xs font-medium uppercase text-pink-500"
            >
                {{ structure.structuretype.name }}
            </div>
            <Link
                :href="link"
                :data="data"
                class="text-center text-sm font-semibold text-green-600 hover:text-green-700"
            >
                {{ structure.name }}
            </Link>

            <ul>
                <li
                    class="list-inside list-disc text-xs font-semibold"
                    v-for="(activite, index) in getUniqueActivitesTitre(
                        structure.activites
                    )"
                    :key="activite.id"
                >
                    {{ activite.titre }}
                </li>
            </ul>
            <ul class="flex items-center py-1.5">
                <li
                    v-if="structure.adresses.length > 0"
                    class="list-inside list-disc text-xs font-medium"
                >
                    {{ structure.adresses[0].address }},
                    {{ formatCityName(structure.adresses[0].city) }}
                    <span class="text-xs"
                        >({{ structure.adresses[0].zip_code }})</span
                    >
                </li>
                <li v-else class="list-inside list-disc text-sm font-medium">
                    {{ formatCityName(structure.city) }}
                    <span class="text-xs">({{ structure.zip_code }})</span>
                </li>
            </ul>
        </div>
    </div>
</template>
