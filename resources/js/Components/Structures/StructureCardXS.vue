<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { TransitionRoot } from "@headlessui/vue";

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

const isShowing = ref(true);

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
                        v-for="activite in getUniqueActivitesTitre(
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
                        class="list-inside list-disc text-xs font-medium normal-case"
                    >
                        {{ structure.adresses[0].city_name }}
                        <span class="text-xs"
                            >({{ structure.adresses[0].zip_code }})</span
                        >
                    </li>
                    <li
                        v-else
                        class="list-inside list-disc text-sm font-medium normal-case"
                    >
                        {{ structure.city_name }}
                        <span class="text-xs">({{ structure.zip_code }})</span>
                    </li>
                </ul>
            </div>
        </div>
    </TransitionRoot>
</template>
