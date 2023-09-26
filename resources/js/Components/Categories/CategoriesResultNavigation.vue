<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import BreezeDropdown from "@/Components/Dropdown.vue";
import { NewspaperIcon, ArrowUturnLeftIcon } from "@heroicons/vue/24/solid";
import { ChevronDownIcon, ChevronUpIcon } from "@heroicons/vue/24/outline";
const props = defineProps({
    city: Object,
    departement: Object,
    discipline: Object,
    categories: Object,
    categoriesWithoutProduit: Object,
    allStructureTypes: Object,
    category: Object,
    structuretypeElected: Object,
});

const openCategories = ref(false);
const openStructuresTypes = ref(false);

const showCategories = () => {
    openStructuresTypes.value = false;
    openCategories.value = !openCategories.value;
};

const showStructuresTypes = () => {
    openCategories.value = false;
    openStructuresTypes.value = !openStructuresTypes.value;
};
</script>
<template>
    <!--  desktop Base-->
    <div
        v-if="!departement && !city && !structuretypeElected && !category"
        class="mx-auto hidden max-w-full md:block"
    >
        <div class="flex h-full w-full flex-row items-stretch justify-around">
            <Link
                :href="
                    route('disciplines.show', {
                        discipline: discipline.slug,
                    })
                "
                class="group relative flex w-full items-center justify-center border-b-8 border-blue-400 py-3 text-center transition duration-150 ease-in-out hover:bg-blue-400 focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-7 w-7 text-gray-700 group-hover:text-white group-focus:text-white"
                />
            </Link>
            <Link
                preserve-scroll
                v-for="categorie in categories"
                :key="categorie.id"
                :href="
                    route('disciplines.categories.show', {
                        discipline: discipline.slug,
                        category: categorie.id,
                    })
                "
                class="w-full border-b-8 border-blue-400 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                {{ categorie.nom_categorie_client }}
            </Link>
            <BreezeDropdown
                align="right"
                width="w-full"
                marginTop="4"
                class="group w-full border-b-8 border-blue-400 py-2 text-center text-sm font-semibold leading-5 text-gray-700 hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <template #trigger>
                    <span class="inline-flex">
                        <button
                            type="button"
                            class="inline-flex w-full items-center py-2 text-center text-sm font-semibold leading-5 text-gray-700 focus:outline-none group-hover:text-white group-focus:text-blue-400"
                        >
                            Plus
                            <ChevronDownIcon class="ml-2 h-5 w-5" />
                        </button>
                    </span>
                </template>

                <template #content>
                    <Link
                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                        preserve-scroll
                        :href="
                            route('disciplines.categories.show', {
                                discipline: discipline.slug,
                                category: category.id,
                            })
                        "
                        v-for="category in categoriesWithoutProduit"
                        :key="category.id"
                    >
                        {{ category.nom_categorie_pro }}
                    </Link>
                </template>
            </BreezeDropdown>
            <Link
                preserve-scroll
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
                :href="
                    route('disciplines.structuretypes.show', {
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="w-full border-b-8 border-sky-700 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-700 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                {{ structureType.name }}
            </Link>
            <Link
                preserve-scroll
                :href="route('welcome')"
                class="group inline-flex w-full items-center justify-center border-b-8 border-pink-600 py-3 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:bg-pink-600 hover:text-white focus:bg-pink-600 focus:text-white focus:outline-none"
            >
                <NewspaperIcon
                    class="mr-2 h-7 w-7 text-pink-600 group-hover:text-white"
                />
                Blog
            </Link>
        </div>
    </div>

    <!--  mobile Base-->
    <div
        v-if="!departement && !city && !structuretypeElected && !category"
        :class="{
            'bg-blue-500': !openStructuresTypes,
            'bg-sky-800': openStructuresTypes,
        }"
        class="grid w-full grid-cols-6 gap-y-2 border-b border-gray-100 text-white md:hidden"
    >
        <div
            class="col-span-1 flex items-center justify-center bg-slate-700 px-2 py-3"
        >
            <Link
                :href="
                    route('disciplines.show', {
                        discipline: discipline.slug,
                    })
                "
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-6 w-6 text-white hover:text-gray-100"
                />
            </Link>
        </div>

        <button
            type="button"
            @click="showCategories"
            class="col-span-2 flex items-center justify-center bg-blue-400 px-2 py-3 transition duration-150 ease-in-out hover:bg-blue-500"
        >
            Activités
            <ChevronUpIcon v-if="openCategories" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>
        <button
            type="button"
            @click="showStructuresTypes"
            class="col-span-2 flex items-center justify-center bg-sky-700 px-2 py-3 transition duration-150 ease-in-out hover:bg-sky-800"
        >
            Structures
            <ChevronUpIcon v-if="openStructuresTypes" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>

        <div
            class="col-span-1 flex items-center justify-center bg-pink-600 px-2 py-3 hover:bg-pink-800"
        >
            <Link
                :href="route('welcome')"
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
                ><NewspaperIcon class="h-6 w-6" />
                <p class="sr-only">Blog</p>
            </Link>
        </div>
        <template v-if="openCategories">
            <Link
                preserve-scroll
                :href="
                    route('disciplines.categories.show', {
                        discipline: discipline.slug,
                        category: category.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="category in categories"
                :key="category.id"
            >
                {{ category.nom_categorie_client }}
            </Link>
        </template>

        <template v-if="openStructuresTypes">
            <Link
                preserve-scroll
                :href="
                    route('disciplines.structuretypes.show', {
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
            >
                {{ structureType.name }}
            </Link>
        </template>
    </div>

    <!--city SANS cat / type Desktop -->
    <div
        v-if="city && !structuretypeElected && !category"
        class="mx-auto hidden max-w-full md:block"
    >
        <div class="flex h-full w-full flex-row items-stretch justify-around">
            <Link
                :href="
                    route('villes.show', {
                        city: city.id,
                    })
                "
                class="group relative flex w-full items-center justify-center border-b-8 border-blue-400 py-3 text-center transition duration-150 ease-in-out hover:bg-blue-400 focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-7 w-7 text-gray-700 group-hover:text-white group-focus:text-white"
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
                class="relative w-full border-b-8 border-blue-400 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
                :class="[
                    route().current('villes.disciplines.categories.show') &&
                    category.id === categorie.id
                        ? 'bg-blue-400 text-white'
                        : '',
                ]"
            >
                {{ categorie.nom_categorie_client }}
                <div
                    v-if="
                        route().current('villes.disciplines.categories.show') &&
                        category.id === categorie.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-blue-400"
                ></div>
            </Link>
            <BreezeDropdown
                align="right"
                width="w-full"
                marginTop="4"
                class="group w-full border-b-8 border-blue-400 py-2 text-center text-sm font-semibold leading-5 text-gray-700 hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <template #trigger>
                    <span class="inline-flex">
                        <button
                            type="button"
                            class="inline-flex w-full items-center py-2 text-center text-sm font-semibold leading-5 text-gray-700 focus:outline-none group-hover:text-white group-focus:text-blue-400"
                        >
                            Plus
                            <ChevronDownIcon class="ml-2 h-5 w-5" />
                        </button>
                    </span>
                </template>

                <template #content>
                    <Link
                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
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
                    </Link>
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
                class="relative w-full border-b-8 border-sky-700 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-700 hover:text-white focus:bg-sky-700 focus:text-white focus:outline-none"
                :class="[
                    route().current('villes.disciplines.structuretypes.show') &&
                    structureType.id === structuretypeElected.id
                        ? ' bg-sky-700 text-white'
                        : '',
                ]"
            >
                {{ structureType.name }}
                <div
                    v-if="
                        route().current(
                            'villes.disciplines.structuretypes.show'
                        ) && structureType.id === structuretypeElected.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-sky-700"
                ></div>
            </Link>
            <Link
                preserve-scroll
                :href="route('welcome')"
                class="group inline-flex w-full items-center justify-center border-b-8 border-pink-600 py-3 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:bg-pink-600 hover:text-white focus:bg-pink-600 focus:text-white focus:outline-none"
            >
                <NewspaperIcon
                    class="mr-2 h-7 w-7 text-pink-600 group-hover:text-white"
                />
                Blog
            </Link>
        </div>
    </div>

    <!--city SANS cat / type mobile -->
    <div
        v-if="city && !structuretypeElected && !category"
        :class="{
            'bg-blue-500': !openStructuresTypes,
            'bg-sky-800': openStructuresTypes,
        }"
        class="grid w-full grid-cols-6 gap-y-2 border-b border-gray-100 text-white md:hidden"
    >
        <div
            class="col-span-1 flex items-center justify-center bg-slate-700 px-2 py-3"
        >
            <Link
                :href="
                    route('villes.show', {
                        city: city.id,
                    })
                "
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-6 w-6 text-white hover:text-gray-100"
                />
            </Link>
        </div>

        <button
            type="button"
            @click="showCategories"
            class="col-span-2 flex items-center justify-center bg-blue-400 px-2 py-3 transition duration-150 ease-in-out hover:bg-blue-500"
        >
            Activités
            <ChevronUpIcon v-if="openCategories" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>
        <button
            type="button"
            @click="showStructuresTypes"
            class="col-span-2 flex items-center justify-center bg-sky-700 px-2 py-3 transition duration-150 ease-in-out hover:bg-sky-800"
        >
            Structures
            <ChevronUpIcon v-if="openStructuresTypes" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>

        <div
            class="col-span-1 flex items-center justify-center bg-pink-600 px-2 py-3 hover:bg-pink-800"
        >
            <Link
                :href="route('welcome')"
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
                ><NewspaperIcon class="h-6 w-6" />
                <p class="sr-only">Blog</p>
            </Link>
        </div>
        <template v-if="openCategories">
            <Link
                preserve-scroll
                :href="
                    route('villes.disciplines.categories.show', {
                        city: city.id,
                        discipline: discipline.slug,
                        category: category.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="category in categories"
                :key="category.id"
            >
                {{ category.nom_categorie_client }}
            </Link>
        </template>

        <template v-if="openStructuresTypes">
            <Link
                preserve-scroll
                :href="
                    route('villes.disciplines.structuretypes.show', {
                        city: city.id,
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
            >
                {{ structureType.name }}
            </Link>
        </template>
    </div>

    <!-- city AVEC cat / type desktop -->
    <div
        v-if="city && (structuretypeElected || category)"
        class="mx-auto hidden max-w-full md:block"
    >
        <div class="flex h-full w-full flex-row items-stretch justify-around">
            <Link
                :href="
                    route('villes.disciplines.show', {
                        city: city.id,
                        discipline: discipline.slug,
                    })
                "
                class="group relative flex w-full items-center justify-center border-b-8 border-blue-400 py-3 text-center transition duration-150 ease-in-out hover:bg-blue-400 focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-7 w-7 text-gray-700 group-hover:text-white group-focus:text-white"
                />
                <!-- <div
                    v-if="route().current('villes.disciplines.show')"
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-blue-400"
                ></div> -->
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
                class="relative w-full border-b-8 border-blue-400 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
                :class="[
                    route().current('villes.disciplines.categories.show') &&
                    category.id === categorie.id
                        ? 'bg-blue-400 text-white'
                        : '',
                ]"
            >
                {{ categorie.nom_categorie_client }}
                <div
                    v-if="
                        route().current('villes.disciplines.categories.show') &&
                        category.id === categorie.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-blue-400"
                ></div>
            </Link>
            <BreezeDropdown
                align="right"
                width="w-full"
                marginTop="4"
                class="group w-full border-b-8 border-blue-400 py-2 text-center text-sm font-semibold leading-5 text-gray-700 hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <template #trigger>
                    <span class="inline-flex">
                        <button
                            type="button"
                            class="inline-flex w-full items-center py-2 text-center text-sm font-semibold leading-5 text-gray-700 focus:outline-none group-hover:text-white group-focus:text-blue-400"
                        >
                            Plus
                            <ChevronDownIcon class="ml-2 h-5 w-5" />
                        </button>
                    </span>
                </template>

                <template #content>
                    <Link
                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
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
                    </Link>
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
                class="relative w-full border-b-8 border-sky-700 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-700 hover:text-white focus:bg-sky-700 focus:text-white focus:outline-none"
                :class="[
                    route().current('villes.disciplines.structuretypes.show') &&
                    structureType.id === structuretypeElected.id
                        ? ' bg-sky-700 text-white'
                        : '',
                ]"
            >
                {{ structureType.name }}
                <div
                    v-if="
                        route().current(
                            'villes.disciplines.structuretypes.show'
                        ) && structureType.id === structuretypeElected.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-sky-700"
                ></div>
            </Link>
            <Link
                preserve-scroll
                :href="route('welcome')"
                class="group inline-flex w-full items-center justify-center border-b-8 border-pink-600 py-3 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:bg-pink-600 hover:text-white focus:bg-pink-600 focus:text-white focus:outline-none"
            >
                <NewspaperIcon
                    class="mr-2 h-7 w-7 text-pink-600 group-hover:text-white"
                />
                Blog
            </Link>
        </div>
    </div>

    <!-- city AVEC cat / type mobile -->
    <div
        v-if="city && (structuretypeElected || category)"
        :class="{
            'bg-blue-500': !openStructuresTypes,
            'bg-sky-800': openStructuresTypes,
        }"
        class="grid w-full grid-cols-6 gap-y-2 border-b border-gray-100 text-white md:hidden"
    >
        <div
            class="col-span-1 flex items-center justify-center bg-slate-700 px-2 py-3"
        >
            <Link
                :href="
                    route('villes.disciplines.show', {
                        city: city.id,
                        discipline: discipline.slug,
                    })
                "
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-6 w-6 text-white hover:text-gray-100"
                />
            </Link>
        </div>

        <button
            type="button"
            @click="showCategories"
            class="col-span-2 flex items-center justify-center bg-blue-400 px-2 py-3 transition duration-150 ease-in-out hover:bg-blue-500"
        >
            Activités
            <ChevronUpIcon v-if="openCategories" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>
        <button
            type="button"
            @click="showStructuresTypes"
            class="col-span-2 flex items-center justify-center bg-sky-700 px-2 py-3 transition duration-150 ease-in-out hover:bg-sky-800"
        >
            Structures
            <ChevronUpIcon v-if="openStructuresTypes" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>

        <div
            class="col-span-1 flex items-center justify-center bg-pink-600 px-2 py-3 hover:bg-pink-800"
        >
            <Link
                :href="route('welcome')"
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
                ><NewspaperIcon class="h-6 w-6" />
                <p class="sr-only">Blog</p>
            </Link>
        </div>
        <template v-if="openCategories">
            <Link
                preserve-scroll
                :href="
                    route('villes.disciplines.categories.show', {
                        city: city.id,
                        discipline: discipline.slug,
                        category: category.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="category in categories"
                :key="category.id"
            >
                {{ category.nom_categorie_client }}
            </Link>
        </template>

        <template v-if="openStructuresTypes">
            <Link
                preserve-scroll
                :href="
                    route('villes.disciplines.structuretypes.show', {
                        city: city.id,
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
            >
                {{ structureType.name }}
            </Link>
        </template>
    </div>

    <!--Departement SANS cat/ type desktop -->
    <div
        v-if="departement && !structuretypeElected && !category"
        class="mx-auto hidden max-w-full md:block"
    >
        <div class="flex h-full w-full flex-row items-stretch justify-around">
            <Link
                :href="
                    route('departements.show', {
                        departement: departement.id,
                    })
                "
                class="group relative flex w-full items-center justify-center border-b-8 border-blue-400 py-3 text-center transition duration-150 ease-in-out hover:bg-blue-400 focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-7 w-7 text-gray-700 group-hover:text-white group-focus:text-white"
                />
            </Link>
            <Link
                preserve-scroll
                v-for="categorie in categories"
                :key="categorie.id"
                :href="
                    route('departements.disciplines.categories.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                        category: categorie.id,
                    })
                "
                class="relative w-full border-b-8 border-blue-400 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
                :class="[
                    route().current(
                        'departements.disciplines.categories.show'
                    ) && category.id === categorie.id
                        ? 'bg-blue-400 text-white'
                        : '',
                ]"
            >
                {{ categorie.nom_categorie_client }}
                <div
                    v-if="
                        route().current(
                            'departements.disciplines.categories.show'
                        ) && category.id === categorie.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-blue-400"
                ></div>
            </Link>
            <BreezeDropdown
                align="right"
                width="w-full"
                marginTop="4"
                class="group w-full border-b-8 border-blue-400 py-2 text-center text-sm font-semibold leading-5 text-gray-700 hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <template #trigger>
                    <span class="inline-flex">
                        <button
                            type="button"
                            class="inline-flex w-full items-center py-2 text-center text-sm font-semibold leading-5 text-gray-700 focus:outline-none group-hover:text-white group-focus:text-blue-400"
                        >
                            Plus
                            <ChevronDownIcon class="ml-2 h-5 w-5" />
                        </button>
                    </span>
                </template>

                <template #content>
                    <Link
                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                        preserve-scroll
                        :href="
                            route('departements.disciplines.categories.show', {
                                departement: departement.id,
                                discipline: discipline.slug,
                                category: category.id,
                            })
                        "
                        v-for="category in categoriesWithoutProduit"
                        :key="category.id"
                    >
                        {{ category.nom_categorie_pro }}
                    </Link>
                </template>
            </BreezeDropdown>
            <Link
                preserve-scroll
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
                :href="
                    route('departements.disciplines.structuretypes.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="relative w-full border-b-8 border-sky-700 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-700 hover:text-white focus:bg-sky-700 focus:text-white focus:outline-none"
                :class="[
                    route().current(
                        'departements.disciplines.structuretypes.show'
                    ) && structureType.id === structuretypeElected.id
                        ? ' bg-sky-700 text-white'
                        : '',
                ]"
            >
                {{ structureType.name }}
                <div
                    v-if="
                        route().current(
                            'departements.disciplines.structuretypes.show'
                        ) && structureType.id === structuretypeElected.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-sky-700"
                ></div>
            </Link>
            <Link
                preserve-scroll
                :href="route('welcome')"
                class="group inline-flex w-full items-center justify-center border-b-8 border-pink-600 py-3 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:bg-pink-600 hover:text-white focus:bg-pink-600 focus:text-white focus:outline-none"
            >
                <NewspaperIcon
                    class="mr-2 h-7 w-7 text-pink-600 group-hover:text-white"
                />
                Blog
            </Link>
        </div>
    </div>

    <!--departement SANS cat/ type Mobile -->
    <div
        v-if="departement && !structuretypeElected && !category"
        :class="{
            'bg-blue-500': !openStructuresTypes,
            'bg-sky-800': openStructuresTypes,
        }"
        class="grid w-full grid-cols-6 gap-y-2 border-b border-gray-100 text-white md:hidden"
    >
        <div
            class="col-span-1 flex items-center justify-center bg-slate-700 px-2 py-3"
        >
            <Link
                :href="
                    route('departements.disciplines.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                    })
                "
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-6 w-6 text-white hover:text-gray-100"
                />
            </Link>
        </div>

        <button
            type="button"
            @click="showCategories"
            class="col-span-2 flex items-center justify-center bg-blue-400 px-2 py-3 transition duration-150 ease-in-out hover:bg-blue-500"
        >
            Activités
            <ChevronUpIcon v-if="openCategories" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>
        <button
            type="button"
            @click="showStructuresTypes"
            class="col-span-2 flex items-center justify-center bg-sky-700 px-2 py-3 transition duration-150 ease-in-out hover:bg-sky-800"
        >
            Structures
            <ChevronUpIcon v-if="openStructuresTypes" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>

        <div
            class="col-span-1 flex items-center justify-center bg-pink-600 px-2 py-3 hover:bg-pink-800"
        >
            <Link
                :href="route('welcome')"
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
                ><NewspaperIcon class="h-6 w-6" />
                <p class="sr-only">Blog</p>
            </Link>
        </div>
        <template v-if="openCategories">
            <Link
                preserve-scroll
                :href="
                    route('departements.disciplines.categories.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                        category: category.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="category in categories"
                :key="category.id"
            >
                {{ category.nom_categorie_client }}
            </Link>
        </template>

        <template v-if="openStructuresTypes">
            <Link
                preserve-scroll
                :href="
                    route('departements.disciplines.structuretypes.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
            >
                {{ structureType.name }}
            </Link>
        </template>
    </div>

    <!--departement AVEC cat/ type Desktop -->
    <div
        v-if="departement && (structuretypeElected || category)"
        class="mx-auto hidden max-w-full md:block"
    >
        <div class="flex h-full w-full flex-row items-stretch justify-around">
            <Link
                :href="
                    route('departements.disciplines.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                    })
                "
                class="group relative flex w-full items-center justify-center border-b-8 border-blue-400 py-3 text-center transition duration-150 ease-in-out hover:bg-blue-400 focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-7 w-7 text-gray-700 group-hover:text-white group-focus:text-white"
                />
            </Link>
            <Link
                preserve-scroll
                v-for="categorie in categories"
                :key="categorie.id"
                :href="
                    route('departements.disciplines.categories.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                        category: categorie.id,
                    })
                "
                class="relative w-full border-b-8 border-blue-400 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
                :class="[
                    route().current(
                        'departements.disciplines.categories.show'
                    ) && category.id === categorie.id
                        ? 'bg-blue-400 text-white'
                        : '',
                ]"
            >
                {{ categorie.nom_categorie_client }}
                <div
                    v-if="
                        route().current(
                            'departements.disciplines.categories.show'
                        ) && category.id === categorie.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-blue-400"
                ></div>
            </Link>
            <BreezeDropdown
                align="right"
                width="w-full"
                marginTop="4"
                class="group w-full border-b-8 border-blue-400 py-2 text-center text-sm font-semibold leading-5 text-gray-700 hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <template #trigger>
                    <span class="inline-flex">
                        <button
                            type="button"
                            class="inline-flex w-full items-center py-2 text-center text-sm font-semibold leading-5 text-gray-700 focus:outline-none group-hover:text-white group-focus:text-blue-400"
                        >
                            Plus
                            <ChevronDownIcon class="ml-2 h-5 w-5" />
                        </button>
                    </span>
                </template>

                <template #content>
                    <Link
                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                        preserve-scroll
                        :href="
                            route('departements.disciplines.categories.show', {
                                departement: departement.id,
                                discipline: discipline.slug,
                                category: category.id,
                            })
                        "
                        v-for="category in categoriesWithoutProduit"
                        :key="category.id"
                    >
                        {{ category.nom_categorie_pro }}
                    </Link>
                </template>
            </BreezeDropdown>
            <Link
                preserve-scroll
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
                :href="
                    route('departements.disciplines.structuretypes.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="relative w-full border-b-8 border-sky-700 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-700 hover:text-white focus:bg-sky-700 focus:text-white focus:outline-none"
                :class="[
                    route().current(
                        'departements.disciplines.structuretypes.show'
                    ) && structureType.id === structuretypeElected.id
                        ? ' bg-sky-700 text-white'
                        : '',
                ]"
            >
                {{ structureType.name }}
                <div
                    v-if="
                        route().current(
                            'departements.disciplines.structuretypes.show'
                        ) && structureType.id === structuretypeElected.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-sky-700"
                ></div>
            </Link>
            <Link
                preserve-scroll
                :href="route('welcome')"
                class="group inline-flex w-full items-center justify-center border-b-8 border-pink-600 py-3 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:bg-pink-600 hover:text-white focus:bg-pink-600 focus:text-white focus:outline-none"
            >
                <NewspaperIcon
                    class="mr-2 h-7 w-7 text-pink-600 group-hover:text-white"
                />
                Blog
            </Link>
        </div>
    </div>

    <!--departement AVEC Cat ou Type Mobile -->
    <div
        v-if="departement && (structuretypeElected || category)"
        :class="{
            'bg-blue-500': !openStructuresTypes,
            'bg-sky-800': openStructuresTypes,
        }"
        class="grid w-full grid-cols-6 gap-y-2 border-b border-gray-100 text-white md:hidden"
    >
        <div
            class="col-span-1 flex items-center justify-center bg-slate-700 px-2 py-3"
        >
            <Link
                :href="
                    route('departements.disciplines.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                    })
                "
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-6 w-6 text-white hover:text-gray-100"
                />
            </Link>
        </div>

        <button
            type="button"
            @click="showCategories"
            class="col-span-2 flex items-center justify-center bg-blue-400 px-2 py-3 transition duration-150 ease-in-out hover:bg-blue-500"
        >
            Activités
            <ChevronUpIcon v-if="openCategories" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>
        <button
            type="button"
            @click="showStructuresTypes"
            class="col-span-2 flex items-center justify-center bg-sky-700 px-2 py-3 transition duration-150 ease-in-out hover:bg-sky-800"
        >
            Structures
            <ChevronUpIcon v-if="openStructuresTypes" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>

        <div
            class="col-span-1 flex items-center justify-center bg-pink-600 px-2 py-3 hover:bg-pink-800"
        >
            <Link
                :href="route('welcome')"
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
                ><NewspaperIcon class="h-6 w-6" />
                <p class="sr-only">Blog</p>
            </Link>
        </div>
        <template v-if="openCategories">
            <Link
                preserve-scroll
                :href="
                    route('departements.disciplines.categories.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                        category: category.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="category in categories"
                :key="category.id"
            >
                {{ category.nom_categorie_client }}
            </Link>
        </template>

        <template v-if="openStructuresTypes">
            <Link
                preserve-scroll
                :href="
                    route('departements.disciplines.structuretypes.show', {
                        departement: departement.id,
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
            >
                {{ structureType.name }}
            </Link>
        </template>
    </div>

    <!-- category desktop -->
    <div
        v-if="!departement && !city && category"
        class="mx-auto hidden max-w-full md:block"
    >
        <div class="flex h-full w-full flex-row items-stretch justify-around">
            <Link
                :href="
                    route('disciplines.show', {
                        discipline: discipline.slug,
                    })
                "
                class="group relative flex w-full items-center justify-center border-b-8 border-blue-400 py-3 text-center transition duration-150 ease-in-out hover:bg-blue-400 focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-7 w-7 text-gray-700 group-hover:text-white group-focus:text-white"
                />
                <!-- <div
                    v-if="route().current('disciplines.show')"
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-blue-400"
                ></div> -->
            </Link>
            <Link
                preserve-scroll
                v-for="categorie in categories"
                :key="categorie.id"
                :href="
                    route('disciplines.categories.show', {
                        discipline: discipline.slug,
                        category: categorie.id,
                    })
                "
                class="relative w-full border-b-8 border-blue-400 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
                :class="[
                    route().current('disciplines.categories.show') &&
                    category.id === categorie.id
                        ? 'bg-blue-400 text-white'
                        : '',
                ]"
            >
                {{ categorie.nom_categorie_client }}
                <div
                    v-if="
                        route().current('disciplines.categories.show') &&
                        category.id === categorie.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-blue-400"
                ></div>
            </Link>
            <BreezeDropdown
                align="right"
                width="w-full"
                marginTop="4"
                class="group w-full border-b-8 border-blue-400 py-2 text-center text-sm font-semibold leading-5 text-gray-700 hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <template #trigger>
                    <span class="inline-flex">
                        <button
                            type="button"
                            class="inline-flex w-full items-center py-2 text-center text-sm font-semibold leading-5 text-gray-700 focus:outline-none group-hover:text-white group-focus:text-blue-400"
                        >
                            Plus
                            <ChevronDownIcon class="ml-2 h-5 w-5" />
                        </button>
                    </span>
                </template>

                <template #content>
                    <Link
                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                        preserve-scroll
                        :href="
                            route('disciplines.categories.show', {
                                discipline: discipline.slug,
                                category: category.id,
                            })
                        "
                        v-for="category in categoriesWithoutProduit"
                        :key="category.id"
                    >
                        {{ category.nom_categorie_pro }}
                    </Link>
                </template>
            </BreezeDropdown>
            <Link
                preserve-scroll
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
                :href="
                    route('disciplines.structuretypes.show', {
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="relative w-full border-b-8 border-sky-700 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-700 hover:text-white focus:bg-sky-700 focus:text-white focus:outline-none"
                :class="[
                    route().current('disciplines.structuretypes.show') &&
                    structureType.id === structuretypeElected.id
                        ? ' bg-sky-700 text-white'
                        : '',
                ]"
            >
                {{ structureType.name }}
                <div
                    v-if="
                        route().current('disciplines.structuretypes.show') &&
                        structureType.id === structuretypeElected.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-sky-700"
                ></div>
            </Link>
            <Link
                preserve-scroll
                :href="route('welcome')"
                class="group inline-flex w-full items-center justify-center border-b-8 border-pink-600 py-3 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:bg-pink-600 hover:text-white focus:bg-pink-600 focus:text-white focus:outline-none"
            >
                <NewspaperIcon
                    class="mr-2 h-7 w-7 text-pink-600 group-hover:text-white"
                />
                Blog
            </Link>
        </div>
    </div>

    <!-- category mobile -->
    <div
        v-if="!departement && !city && category"
        :class="{
            'bg-blue-500': !openStructuresTypes,
            'bg-sky-800': openStructuresTypes,
        }"
        class="grid w-full grid-cols-6 gap-y-2 border-b border-gray-100 text-white md:hidden"
    >
        <div
            class="col-span-1 flex items-center justify-center bg-slate-700 px-2 py-3"
        >
            <Link
                :href="
                    route('disciplines.show', {
                        discipline: discipline.slug,
                    })
                "
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-6 w-6 text-white hover:text-gray-100"
                />
            </Link>
        </div>

        <button
            type="button"
            @click="showCategories"
            class="col-span-2 flex items-center justify-center bg-blue-400 px-2 py-3 transition duration-150 ease-in-out hover:bg-blue-500"
        >
            Activités
            <ChevronUpIcon v-if="openCategories" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>
        <button
            type="button"
            @click="showStructuresTypes"
            class="col-span-2 flex items-center justify-center bg-sky-700 px-2 py-3 transition duration-150 ease-in-out hover:bg-sky-800"
        >
            Structures
            <ChevronUpIcon v-if="openStructuresTypes" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>

        <div
            class="col-span-1 flex items-center justify-center bg-pink-600 px-2 py-3 hover:bg-pink-800"
        >
            <Link
                :href="route('welcome')"
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
                ><NewspaperIcon class="h-6 w-6" />
                <p class="sr-only">Blog</p>
            </Link>
        </div>
        <template v-if="openCategories">
            <Link
                preserve-scroll
                :href="
                    route('disciplines.categories.show', {
                        discipline: discipline.slug,
                        category: category.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="category in categories"
                :key="category.id"
            >
                {{ category.nom_categorie_client }}
            </Link>
        </template>

        <template v-if="openStructuresTypes">
            <Link
                preserve-scroll
                :href="
                    route('disciplines.structuretypes.show', {
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
            >
                {{ structureType.name }}
            </Link>
        </template>
    </div>

    <!-- structuretypeElected desktop -->
    <div
        v-if="!departement && !city && structuretypeElected"
        class="mx-auto hidden max-w-full md:block"
    >
        <div class="flex h-full w-full flex-row items-stretch justify-around">
            <Link
                :href="
                    route('disciplines.show', {
                        discipline: discipline.slug,
                    })
                "
                class="group relative flex w-full items-center justify-center border-b-8 border-blue-400 py-3 text-center transition duration-150 ease-in-out hover:bg-blue-400 focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-7 w-7 text-gray-700 group-hover:text-white group-focus:text-white"
                />
            </Link>
            <Link
                preserve-scroll
                v-for="categorie in categories"
                :key="categorie.id"
                :href="
                    route('disciplines.categories.show', {
                        discipline: discipline.slug,
                        category: categorie.id,
                    })
                "
                class="w-full border-b-8 border-blue-400 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                {{ categorie.nom_categorie_client }}
            </Link>
            <BreezeDropdown
                align="right"
                width="w-full"
                marginTop="4"
                class="group w-full border-b-8 border-blue-400 py-2 text-center text-sm font-semibold leading-5 text-gray-700 hover:bg-blue-400 hover:text-white focus:bg-blue-400 focus:text-white focus:outline-none"
            >
                <template #trigger>
                    <span class="inline-flex">
                        <button
                            type="button"
                            class="inline-flex w-full items-center py-2 text-center text-sm font-semibold leading-5 text-gray-700 focus:outline-none group-hover:text-white group-focus:text-blue-400"
                        >
                            Plus
                            <ChevronDownIcon class="ml-2 h-5 w-5" />
                        </button>
                    </span>
                </template>

                <template #content>
                    <Link
                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                        preserve-scroll
                        :href="
                            route('disciplines.categories.show', {
                                discipline: discipline.slug,
                                category: category.id,
                            })
                        "
                        v-for="category in categoriesWithoutProduit"
                        :key="category.id"
                    >
                        {{ category.nom_categorie_pro }}
                    </Link>
                </template>
            </BreezeDropdown>
            <Link
                preserve-scroll
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
                :href="
                    route('disciplines.structuretypes.show', {
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="relative w-full border-b-8 border-sky-700 py-4 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-sky-700 hover:text-white focus:bg-sky-700 focus:text-white focus:outline-none"
                :class="[
                    route().current('disciplines.structuretypes.show') &&
                    structureType.id === structuretypeElected.id
                        ? ' bg-sky-700 text-white'
                        : '',
                ]"
            >
                {{ structureType.name }}
                <div
                    v-if="
                        route().current('disciplines.structuretypes.show') &&
                        structureType.id === structuretypeElected.id
                    "
                    class="absolute inset-x-1/2 -bottom-4 h-5 w-5 rotate-45 bg-sky-700"
                ></div>
            </Link>
            <Link
                preserve-scroll
                :href="route('welcome')"
                class="group inline-flex w-full items-center justify-center border-b-8 border-pink-600 py-3 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:bg-pink-600 hover:text-white focus:bg-pink-600 focus:text-white focus:outline-none"
            >
                <NewspaperIcon
                    class="mr-2 h-7 w-7 text-pink-600 group-hover:text-white"
                />
                Blog
            </Link>
        </div>
    </div>

    <!-- structuretypeElected mobile -->
    <div
        v-if="!departement && !city && structuretypeElected"
        :class="{
            'bg-blue-500': !openStructuresTypes,
            'bg-sky-800': openStructuresTypes,
        }"
        class="grid w-full grid-cols-6 gap-y-2 border-b border-gray-100 text-white md:hidden"
    >
        <div
            class="col-span-1 flex items-center justify-center bg-slate-700 px-2 py-3"
        >
            <Link
                :href="
                    route('disciplines.show', {
                        discipline: discipline.slug,
                    })
                "
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
            >
                <ArrowUturnLeftIcon
                    class="h-6 w-6 text-white hover:text-gray-100"
                />
            </Link>
        </div>

        <button
            type="button"
            @click="showCategories"
            class="col-span-2 flex items-center justify-center bg-blue-400 px-2 py-3 transition duration-150 ease-in-out hover:bg-blue-500"
        >
            Activités
            <ChevronUpIcon v-if="openCategories" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>
        <button
            type="button"
            @click="showStructuresTypes"
            class="col-span-2 flex items-center justify-center bg-sky-700 px-2 py-3 transition duration-150 ease-in-out hover:bg-sky-800"
        >
            Structures
            <ChevronUpIcon v-if="openStructuresTypes" class="ml-1.5 h-5 w-5" />
            <ChevronDownIcon v-else class="ml-1.5 h-5 w-5" />
        </button>

        <div
            class="col-span-1 flex items-center justify-center bg-pink-600 px-2 py-3 hover:bg-pink-800"
        >
            <Link
                :href="route('welcome')"
                class="items-center text-center transition duration-150 ease-in-out focus:outline-none"
                ><NewspaperIcon class="h-6 w-6" />
                <p class="sr-only">Blog</p>
            </Link>
        </div>
        <template v-if="openCategories">
            <Link
                preserve-scroll
                :href="
                    route('disciplines.categories.show', {
                        discipline: discipline.slug,
                        category: category.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="category in categories"
                :key="category.id"
            >
                {{ category.nom_categorie_client }}
            </Link>
        </template>

        <template v-if="openStructuresTypes">
            <Link
                preserve-scroll
                :href="
                    route('disciplines.structuretypes.show', {
                        discipline: discipline.slug,
                        structuretype: structureType.id,
                    })
                "
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out last:pb-3"
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
            >
                {{ structureType.name }}
            </Link>
        </template>
    </div>
</template>
