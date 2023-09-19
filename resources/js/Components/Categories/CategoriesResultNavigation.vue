<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import { HomeIcon, NewspaperIcon } from "@heroicons/vue/24/solid";
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
    <!--city desktop -->
    <div
        v-if="city"
        class="hidden border-b-8 border-indigo-400 bg-slate-100/60 py-2 md:block"
    >
        <div class="mx-auto max-w-full">
            <div
                class="flex h-full w-full flex-col items-center justify-around space-y-1.5 md:flex-row md:space-y-0 md:divide-x md:divide-gray-700"
            >
                <Link
                    :href="
                        route('villes.disciplines.show', {
                            city: city.id,
                            discipline: discipline.slug,
                        })
                    "
                    class="hidden items-center pt-1 text-center transition duration-150 ease-in-out focus:outline-none md:inline-flex md:px-4 lg:px-8"
                >
                    <HomeIcon
                        class="h-8 w-8 text-blue-700 hover:text-indigo-700"
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
                    class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-blue-400 focus:text-blue-400 focus:outline-none md:px-4 lg:px-8"
                    :class="[
                        route().current('villes.disciplines.categories.show') &&
                        category.id === categorie.id
                            ? 'underline decoration-blue-500 decoration-4 underline-offset-2'
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
                                class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-blue-400 focus:text-blue-400 focus:outline-none md:px-4 lg:px-8"
                            >
                                Plus
                                <ChevronDownIcon class="ml-2 h-5 w-5" />
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
                    class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-sky-700 focus:text-sky-700 focus:outline-none md:px-4 lg:px-8"
                    :class="[
                        route().current(
                            'villes.disciplines.structuretypes.show'
                        ) && structureType.id === structuretypeElected.id
                            ? 'underline decoration-sky-700 decoration-4 underline-offset-2'
                            : '',
                    ]"
                >
                    {{ structureType.name }}
                </Link>
                <Link
                    preserve-scroll
                    :href="route('welcome')"
                    class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:text-pink-600 focus:text-pink-600 focus:outline-none md:px-4 lg:px-8"
                >
                    <NewspaperIcon class="mr-2 h-6 w-6 text-pink-600" />
                    Blog
                </Link>
            </div>
        </div>
    </div>
    <!--city mobile -->
    <div
        v-if="city"
        class="grid w-full grid-cols-6 border-b border-gray-100 bg-slate-900/90 text-white md:hidden"
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
                <HomeIcon class="h-6 w-6 text-white hover:text-gray-100" />
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
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out"
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
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out"
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
            >
                {{ structureType.name }}
            </Link>
        </template>
    </div>

    <!--departement desktop -->
    <div
        v-if="departement"
        class="hidden border-b-8 border-indigo-400 bg-slate-100/60 py-2 md:block"
    >
        <div class="mx-auto max-w-full">
            <div
                class="flex h-full w-full flex-col items-center justify-around space-y-1.5 md:flex-row md:space-y-0 md:divide-x md:divide-gray-700"
            >
                <Link
                    :href="
                        route('departements.disciplines.show', {
                            departement: departement.id,
                            discipline: discipline.slug,
                        })
                    "
                    class="hidden items-center pt-1 text-center transition duration-150 ease-in-out focus:outline-none md:inline-flex md:px-4 lg:px-8"
                >
                    <HomeIcon
                        class="h-8 w-8 text-blue-700 hover:text-indigo-700"
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
                    class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-blue-400 focus:text-blue-400 focus:outline-none md:px-4 lg:px-8"
                    :class="[
                        route().current(
                            'departements.disciplines.categories.show'
                        ) && category.id === categorie.id
                            ? 'underline decoration-blue-500 decoration-4 underline-offset-2'
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
                                class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-blue-400 focus:text-blue-400 focus:outline-none md:px-4 lg:px-8"
                            >
                                Plus
                                <ChevronDownIcon class="ml-2 h-5 w-5" />
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <BreezeDropdownLink
                            preserve-scroll
                            :href="
                                route(
                                    'departements.disciplines.categories.show',
                                    {
                                        departement: departement.id,
                                        discipline: discipline.slug,
                                        category: category.id,
                                    }
                                )
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
                        route('departements.disciplines.structuretypes.show', {
                            departement: departement.id,
                            discipline: discipline.slug,
                            structuretype: structureType.id,
                        })
                    "
                    class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-sky-700 focus:text-sky-700 focus:outline-none md:px-4 lg:px-8"
                    :class="[
                        route().current(
                            'departements.disciplines.structuretypes.show'
                        ) && structureType.id === structuretypeElected.id
                            ? 'underline decoration-sky-700 decoration-4 underline-offset-2'
                            : '',
                    ]"
                >
                    {{ structureType.name }}
                </Link>
                <Link
                    preserve-scroll
                    :href="route('welcome')"
                    class="inline-flex items-center pt-1 text-center text-sm font-semibold leading-5 text-gray-800 transition duration-150 ease-in-out hover:text-pink-600 focus:text-pink-600 focus:outline-none md:px-4 lg:px-8"
                >
                    <NewspaperIcon class="mr-2 h-6 w-6 text-pink-600" />
                    Blog
                </Link>
            </div>
        </div>
    </div>
    <!--departement mobile -->
    <div
        v-if="departement"
        class="grid w-full grid-cols-6 border-b border-gray-100 bg-slate-900/90 text-white md:hidden"
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
                <HomeIcon class="h-6 w-6 text-white hover:text-gray-100" />
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
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out"
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
                class="col-span-6 px-4 py-2 transition duration-150 ease-in-out"
                v-for="structureType in allStructureTypes"
                :key="structureType.id"
            >
                {{ structureType.name }}
            </Link>
        </template>
    </div>
</template>
