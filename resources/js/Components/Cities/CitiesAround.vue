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
            class="mx-auto w-full rounded bg-gray-50 px-4 py-4 text-gray-600 shadow-lg"
        >
            <h3 class="mb-2 text-center font-semibold">
                Les localités à proximités
            </h3>
            <ul class="list-inside list-disc space-y-1.5">
                <li v-for="city in citiesAround" :key="city.id">
                    <Link
                        :href="route('villes.show', city.slug)"
                        :active="route().current('villes.show', city.slug)"
                        class="hover:text-gray-800 hover:underline"
                    >
                        {{ formatCityName(city.ville) }}
                        <span class="text-xs">({{ city.code_postal }})</span>
                    </Link>
                </li>
            </ul>
        </div>
    </TransitionRoot>
</template>
