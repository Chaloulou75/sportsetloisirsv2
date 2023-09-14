<script setup>
import { Link } from "@inertiajs/vue3";
import { HomeIcon } from "@heroicons/vue/24/solid";
const props = defineProps({
    city: Object,
    discipline: Object,
    categories: Object,
    allStructureTypes: Object,
    category: Object,
});
</script>
<template>
    <div class="block border-b-8 border-indigo-400 bg-slate-100/60 py-2">
        <div class="mx-auto max-w-full">
            <div
                class="flex h-full w-full flex-col flex-wrap items-center justify-around space-y-1.5 md:flex-row md:space-y-0 md:divide-x md:divide-gray-700"
            >
                <Link
                    preserve-scroll
                    :href="route('welcome')"
                    class="hidden items-center px-8 pt-1 transition duration-150 ease-in-out focus:outline-none md:inline-flex"
                >
                    <HomeIcon
                        class="h-8 w-8 text-blue-700 hover:text-blue-800"
                    />
                </Link>
                <Link
                    preserve-scroll
                    v-for="categorie in categories"
                    :key="categorie.id"
                    :href="
                        route('villes.disciplines.categories.show', {
                            city: city.id,
                            discipline: discipline.slug,
                            category: categorie.id,
                        })
                    "
                    class="inline-flex items-center px-8 pt-1 text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-gray-900 focus:text-gray-900 focus:outline-none"
                    :class="[
                        route().current('villes.disciplines.categories.show') &&
                        category.id === categorie.id
                            ? 'underline decoration-indigo-500 decoration-4 underline-offset-2'
                            : '',
                    ]"
                >
                    {{ categorie.nom_categorie_client }}
                </Link>
                <Link
                    preserve-scroll
                    v-for="structureType in allStructureTypes"
                    :key="structureType.id"
                    :href="
                        route('villes.disciplines.structuretypes.show', {
                            city: city.id,
                            discipline: discipline.slug,
                            structuretype: structureType.id,
                        })
                    "
                    class="inline-flex items-center px-8 pt-1 text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-gray-900 focus:text-gray-900 focus:outline-none"
                >
                    {{ structureType.name }}
                </Link>
                <Link
                    preserve-scroll
                    :href="route('welcome')"
                    class="inline-flex items-center px-8 pt-1 text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:text-gray-50 focus:text-gray-50 focus:outline-none"
                >
                    Blog
                </Link>
            </div>
        </div>
    </div>
</template>
