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
            <ul class="list-inside list-disc space-y-1.5">
                <li v-for="city in citiesAround" :key="city.id">
                    <Link
                        :href="route('villes.show', city.slug)"
                        :active="route().current('villes.show', city.slug)"
                        class="hover:font-semibold hover:text-gray-900"
                    >
                        {{ formatCityName(city.ville) }}
                        <span class="text-xs">({{ city.code_postal }})</span>
                    </Link>
                </li>
            </ul>
        </div>
    </TransitionRoot>
</template>
