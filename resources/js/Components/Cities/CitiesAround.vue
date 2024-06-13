<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";
const props = defineProps({
    citiesAround: Object,
});
const isShowing = ref(true);
const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};
</script>
<template>
    <TransitionRoot
        appear
        :show="isShowing"
        enter="transition-opacity ease-linear duration-400"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-linear duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
    >
        <div
            v-if="citiesAround.length > 0"
            class="mx-auto w-full px-4 py-4 text-gray-600"
        >
            <h2
                class="my-4 text-center text-lg font-semibold text-gray-600 md:my-8 md:text-2xl"
            >
                Les localités à proximités
            </h2>
            <div
                class="flex flex-col items-center justify-start space-x-4 px-2 md:flex-row md:px-4"
            >
                <Link
                    v-for="city in citiesAround"
                    :key="city.id"
                    :href="route('villes.show', city.slug)"
                    :active="route().current('villes.show', city.slug)"
                    class="flex items-center justify-center rounded px-12 py-3 text-sm font-medium text-gray-600 shadow-sm ring ring-gray-400 hover:bg-indigo-500 hover:text-white hover:shadow-lg hover:ring-gray-100 focus:outline-none focus:ring active:bg-indigo-500"
                >
                    <p>
                        {{ formatCityName(city.ville) }}
                        <span class="text-xs">({{ city.code_postal }})</span>
                    </p>
                </Link>
            </div>
        </div>
    </TransitionRoot>
</template>
