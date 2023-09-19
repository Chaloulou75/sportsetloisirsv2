<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";
import DisciplinesSimilaires from "@/Components/Disciplines/DisciplinesSimilaires.vue";
import {
    AdjustmentsHorizontalIcon,
    HomeIcon,
    ListBulletIcon,
    MapIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { useElementVisibility } from "@vueuse/core";

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

const mapStructure = ref(null);
const mapIsVisible = useElementVisibility(mapStructure);
const listeStructure = ref(null);
const listeIsVisible = useElementVisibility(listeStructure);
const goToMap = () => {
    mapStructure.value.scrollIntoView({ behavior: "smooth" });
};
const goToListe = () => {
    listeStructure.value.scrollIntoView({ behavior: "smooth" });
};

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
            <ResultsHeader>
                <template v-slot:title>
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
                </template>
                <template v-slot:ariane>
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
                                    <HomeIcon class="h-4 w-4" />

                                    <span
                                        class="ms-1.5 hidden text-xs font-medium md:block"
                                    >
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
                            <li class="relative hidden items-center md:flex">
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
                </template>
            </ResultsHeader>
            <CategoriesResultNavigation
                :city="city"
                :discipline="discipline"
                :allStructureTypes="allStructureTypes"
                :categories="categories"
                :structuretypeElected="structuretypeElected"
                :categoriesWithoutProduit="categoriesWithoutProduit"
            />
        </template>
        <template v-if="structures.data.length > 0">
            <div
                class="relative mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
            >
                <div class="md:w-1/2" ref="listeStructure">
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
                    <button
                        v-if="!mapIsVisible && listeIsVisible"
                        type="button"
                        class="fixed inset-x-2 bottom-4 z-[9999] mx-auto flex w-1/2 items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-white hover:bg-gray-800 md:hidden"
                        @click="goToMap"
                    >
                        <MapIcon class="mr-2 h-5 w-5" />
                        Carte
                    </button>
                </div>
                <div class="space-y-4 md:sticky md:w-1/2">
                    <div ref="mapStructure">
                        <LeafletMapMultiple
                            class="md:top-2"
                            :structures="structures.data"
                            :hovered-structure="hoveredStructure"
                            :zoom="12"
                        />
                        <button
                            v-if="mapIsVisible"
                            type="button"
                            class="fixed inset-x-2 bottom-4 z-[9999] mx-auto flex w-1/2 items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-white hover:bg-gray-800 md:hidden"
                            @click="goToListe"
                        >
                            <ListBulletIcon class="mr-2 h-5 w-5" />
                            Liste
                        </button>
                    </div>

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
