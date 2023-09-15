<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";
import DisciplinesSimilaires from "@/Components/Disciplines/DisciplinesSimilaires.vue";

const props = defineProps({
    familles: Object,
    structuretypeElected: Object,
    allStructureTypes: Object,
    categories: Object,
    categoriesWithoutProduit: Object,
    city: Object,
    citiesAround: Object,
    structures: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    allCities: Object,
});

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

const hoveredStructure = ref(null);

function showTooltip(structure) {
    hoveredStructure.value = structure.id;
}
function hideTooltip() {
    hoveredStructure.value = null;
}
</script>

<template>
    <Head
        :title="`${formatCityName(city.ville)}`"
        :description="`${structuretypeElected.name} de ${
            discipline.name
        } à ${formatCityName(city.ville)}. Choisissez parmi plus de ${
            city.structures_count
        } structures pour pratiquer une activité sportive ou de loisirs à ${formatCityName(
            city.ville
        )}`"
    />

    <ResultLayout
        :listDisciplines="listDisciplines"
        :allCities="allCities"
        :discipline="discipline"
        :categories="categories"
    >
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <div class="mx-auto my-6 max-w-full px-2 py-4 md:px-4 lg:px-6">
                <div
                    class="mx-auto my-2 flex w-full flex-col items-center justify-center space-y-2 bg-slate-100/60 px-2 py-2 md:w-1/3"
                >
                    <h1
                        class="border-b-2 border-slate-400 text-center text-2xl font-bold leading-tight tracking-widest text-gray-800 md:text-4xl"
                    >
                        {{ discipline.name }}
                        <span class="lowercase">à</span>
                        {{ formatCityName(city.ville) }}
                    </h1>
                    <h2
                        class="text-center text-lg font-semibold leading-tight tracking-widest text-gray-800 md:text-2xl"
                    >
                        {{ structuretypeElected.name }}
                    </h2>
                </div>
                <div
                    class="mx-auto flex w-full flex-col items-center justify-center space-y-2 bg-gray-100/60 px-2 py-2 md:w-1/3"
                >
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex overflow-hidden rounded-lg border border-gray-200 text-gray-600"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                        />
                                    </svg>

                                    <span class="ms-1.5 text-xs font-medium">
                                        Accueil
                                    </span>
                                </Link>
                            </li>

                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="route('villes.show', city.id)"
                                    class="flex h-10 shrink-0 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ formatCityName(city.ville) }}
                                </Link>
                            </li>
                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('villes.disciplines.show', {
                                            city: city.id,
                                            discipline: discipline.slug,
                                        })
                                    "
                                    class="flex h-10 shrink-0 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ discipline.name }}
                                </Link>
                            </li>
                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route(
                                            'villes.disciplines.structuretypes.show',
                                            {
                                                city: city.id,
                                                discipline: discipline.slug,
                                                structuretype:
                                                    structuretypeElected.id,
                                            }
                                        )
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ structuretypeElected.name }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <CategoriesResultNavigation
                :city="city"
                :discipline="discipline"
                :allStructureTypes="allStructureTypes"
                :categories="categories"
                :structuretypeElected="structuretypeElected"
                :categoriesWithoutProduit="categoriesWithoutProduit"
            />
        </template>
        <div class="mx-auto max-w-full px-2 py-4 sm:px-3 lg:px-6">
            <div class="flex items-center justify-around space-x-4">
                <div class="my-4 w-full">
                    <div class="mt-1">
                        <nav
                            class="flex w-full flex-col items-stretch justify-between divide-y divide-green-600 rounded-sm border border-gray-300 bg-white/20 px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none sm:text-base md:flex-row md:items-center md:divide-y-0"
                        >
                            <Link
                                v-for="categorie in categories"
                                :key="categorie.id"
                                :href="
                                    route(
                                        'villes.disciplines.categories.show',
                                        {
                                            city: city.id,
                                            discipline: discipline.slug,
                                            category: categorie.id,
                                        }
                                    )
                                "
                                :class="[
                                    'w-full px-2 py-3 text-center text-sm font-medium leading-5 text-gray-700 ring-white ring-opacity-10 ring-offset-2 ring-offset-green-200 focus:outline-none focus:ring-2',
                                    route().current(
                                        'villes.disciplines.categories.show'
                                    )
                                        ? 'bg-green-600 text-white'
                                        : 'text-gray-700 hover:bg-white/50 hover:text-gray-800',
                                ]"
                            >
                                {{ categorie.nom_categorie_client }}
                            </Link>
                            <Link
                                v-for="structureType in allStructureTypes"
                                :key="structureType.id"
                                :href="
                                    route(
                                        'villes.disciplines.structuretypes.show',
                                        {
                                            city: city.id,
                                            discipline: discipline.slug,
                                            structuretype: structureType.id,
                                        }
                                    )
                                "
                                :class="[
                                    'w-full px-2 py-3 text-center text-sm font-medium leading-5 text-gray-700 ring-white ring-opacity-10 ring-offset-2 ring-offset-green-200 focus:outline-none focus:ring-2',
                                    route().current(
                                        'villes.disciplines.structuretypes.show'
                                    ) &&
                                    structuretypeElected.id === structureType.id
                                        ? 'bg-green-600 text-white'
                                        : 'text-gray-700 hover:bg-white/50 hover:text-gray-800',
                                ]"
                            >
                                {{ structureType.name }}
                            </Link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="structures.data.length > 0">
            <div
                class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
            >
                <div class="md:w-1/2">
                    <div
                        class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-2"
                    >
                        <StructureCard
                            v-for="(structure, index) in structures.data"
                            :key="structure.id"
                            :index="index"
                            :structure="structure"
                            @mouseover="showTooltip(structure)"
                            @mouseout="hideTooltip()"
                            :link="
                                route(
                                    'villes.disciplines.structuretypes.structures.show',
                                    {
                                        city: city.id,
                                        discipline: discipline.slug,
                                        structuretype: structuretypeElected.id,
                                        structure: structure.slug,
                                    }
                                )
                            "
                        />
                    </div>
                    <div class="flex justify-end p-10">
                        <Pagination :links="structures.links" />
                    </div>
                </div>
                <div class="space-y-4 md:sticky md:w-1/2">
                    <LeafletMapMultiple
                        class="md:top-2"
                        :structures="structures.data"
                        :hovered-structure="hoveredStructure"
                        :zoom="12"
                    />
                    <CitiesAround :citiesAround="citiesAround" />
                    <DisciplinesSimilaires
                        :disciplinesSimilaires="disciplinesSimilaires"
                    />
                </div>
            </div>
        </template>
        <template v-else>
            <div
                class="mx-auto min-h-screen max-w-full px-2 py-12 sm:px-6 lg:px-8"
            >
                <p class="font-medium text-gray-700">
                    Dommage, il n'y a pas encore de structures inscrites de type
                    <span class="font-semibold text-gray-800">{{
                        structuretypeElected.name
                    }}</span>
                    en
                    <span class="font-semibold text-gray-800">{{
                        discipline.name
                    }}</span>
                    à
                    <span class="font-semibold text-gray-800">{{
                        formatCityName(city.ville)
                    }}</span>
                </p>
            </div>
        </template>
    </ResultLayout>
</template>
