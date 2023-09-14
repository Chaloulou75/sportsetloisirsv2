<script setup>
import { Link } from "@inertiajs/vue3";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import { HomeIcon, PlusIcon } from "@heroicons/vue/24/solid";
const props = defineProps({
    city: Object,
    discipline: Object,
    categories: Object,
    categoriesWithoutProduit: Object,
    allStructureTypes: Object,
    category: Object,
});
</script>
<template>
    <div class="block border-b-8 border-indigo-400 bg-slate-100/60 py-2">
        <div class="mx-auto max-w-full">
            <div
                class="flex h-full w-full flex-col items-center justify-around space-y-1.5 md:flex-row md:space-y-0 md:divide-x md:divide-gray-700"
            >
                <Link
                    preserve-scroll
                    :href="route('welcome')"
                    class="hidden items-center pt-1 text-center transition duration-150 ease-in-out focus:outline-none md:inline-flex md:px-4 lg:px-8"
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
                    class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-gray-900 focus:text-gray-900 focus:outline-none md:px-4 lg:px-8"
                    :class="[
                        route().current('villes.disciplines.categories.show') &&
                        category.id === categorie.id
                            ? 'underline decoration-indigo-500 decoration-4 underline-offset-2'
                            : '',
                    ]"
                >
                    {{ categorie.nom_categorie_client }}
                </Link>
                <BreezeDropdown align="right" width="48">
                    <template #trigger>
                        <span class="inline-flex">
                            <button
                                type="button"
                                class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-gray-900 focus:text-gray-900 focus:outline-none md:px-4 lg:px-8"
                            >
                                <PlusIcon class="h-5 w-5" />
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <BreezeDropdownLink
                            preserve-scroll
                            :href="
                                route('villes.disciplines.categories.show', {
                                    city: city.id,
                                    discipline: discipline.slug,
                                    category: category.id,
                                })
                            "
                            v-for="category in categoriesWithoutProduit"
                            :key="category.id"
                        >
                            {{ category.nom_categorie_pro }}
                        </BreezeDropdownLink>
                    </template>
                </BreezeDropdown>
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
                    class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-gray-900 focus:text-gray-900 focus:outline-none md:px-4 lg:px-8"
                >
                    {{ structureType.name }}
                </Link>
                <Link
                    preserve-scroll
                    :href="route('welcome')"
                    class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:text-gray-50 focus:text-gray-50 focus:outline-none md:px-4 lg:px-8"
                >
                    Blog
                </Link>
            </div>
        </div>
    </div>
</template>
