<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import { debounce } from "lodash";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";

const props = defineProps({
    familles: Object,
    city: Object,
    citiesAround: Object,
    structures: Object,
    produits: Object,
    listDisciplines: Object,
    allCities: Object,
    filters: Object,
});

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const LeafletMapMultiple = defineAsyncComponent(() =>
    import("@/Components/LeafletMapMultiple.vue")
);

const ProduitCard = defineAsyncComponent(() =>
    import("@/Components/Structures/ProduitCard.vue")
);

const LeafletMapProduitMultiple = defineAsyncComponent(() =>
    import("@/Components/LeafletMapProduitMultiple.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

// let discipline = ref("");
// function resetSearch() {
//     discipline.value = "";
// }

// watch(
//     discipline,
//     debounce(function (value) {
//         router.get(
//             `/villes/${city.ville_formatee}`,
//             { discipline: value },
//             { preserveState: true,
//             replace: true }
//         );
//     }, 500)
// );

const flattenedDisciplines = computed(() => {
    const uniqueDisciplines = new Map();
    props.structures.data.forEach((structure) => {
        structure.disciplines.forEach((discipline) => {
            const disciplineId = discipline.discipline_id;
            if (!uniqueDisciplines.has(disciplineId)) {
                uniqueDisciplines.set(disciplineId, discipline.discipline);
            }
        });
    });
    return Array.from(uniqueDisciplines.values());
});

const hoveredStructure = ref(null);
const hoveredProduit = ref(null);

const showTooltip = (structure) => {
    hoveredStructure.value = structure.id;
};
const showProduitTooltip = (produit) => {
    hoveredProduit.value = produit.id;
};
const hideTooltip = () => {
    hoveredStructure.value = null;
};
const hideProduitTooltip = () => {
    hoveredProduit.value = null;
};

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};
</script>

<template>
    <Head
        :title="formatCityName(city.ville)"
        :description="
            'Envie de faire du sport à ' +
            formatCityName(city.ville) +
            '? Choisissez parmi plus de ' +
            city.structures_count +
            ' structures pour pratiquer une activité sportive ou de loisirs à ' +
            formatCityName(city.ville)
        "
    />

    <ResultLayout :listDisciplines="listDisciplines" :allCities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <div class="mx-auto my-6 max-w-full px-2 py-4 md:px-4 lg:px-6">
                <div
                    class="mx-auto my-2 flex w-full flex-col items-center justify-center space-y-2 bg-slate-100/60 px-2 py-2 md:w-1/4"
                >
                    <h1
                        class="border-b-2 border-slate-400 text-2xl font-bold leading-tight tracking-widest text-gray-800 md:text-4xl"
                    >
                        {{ formatCityName(city.ville) }}
                        <span class="text-sm text-gray-600"
                            >({{ city.code_postal }})
                        </span>
                    </h1>
                </div>
                <div
                    class="mx-auto flex w-full flex-col items-center justify-center space-y-2 bg-slate-100/60 px-2 py-2 md:w-1/4"
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
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ formatCityName(city.ville) }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </template>

        <template v-if="structures.data.length > 0">
            <div
                class="mx-auto max-w-full px-2 py-6 sm:px-6 md:space-x-4 md:py-12 lg:px-8"
            >
                <h2
                    class="mb-4 text-center text-lg font-semibold text-gray-600"
                >
                    Les disciplines pratiquées à
                    <span class="text-indigo-700">{{
                        formatCityName(city.ville)
                    }}</span>
                    <span class="text-xs text-gray-600">
                        ({{ city.code_postal }})</span
                    >
                </h2>
                <div
                    class="flex w-full flex-col flex-wrap items-center justify-center gap-3 text-gray-700 md:flex-row"
                >
                    <Link
                        v-for="discipline in flattenedDisciplines"
                        :key="discipline.id"
                        :href="
                            route('villes.disciplines.show', {
                                city: city.id,
                                discipline: discipline.slug,
                            })
                        "
                        class="inline-block w-48 rounded border border-gray-600 px-4 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                    >
                        {{ discipline.name }}
                    </Link>
                </div>
            </div>
            <section>
                <h2 class="text-center text-lg font-semibold text-gray-600">
                    Les structures disponibles à
                    <span class="text-indigo-700">{{
                        formatCityName(city.ville)
                    }}</span>
                    <span class="text-xs text-gray-600">
                        ({{ city.code_postal }})</span
                    >
                </h2>
                <div
                    class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-8 lg:px-8"
                >
                    <div class="md:w-1/2">
                        <div
                            class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2"
                        >
                            <StructureCard
                                v-for="(structure, index) in structures.data"
                                :key="structure.id"
                                :index="index"
                                :structure="structure"
                                @mouseover="showTooltip(structure)"
                                @mouseout="hideTooltip"
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
                            :zoom="11"
                        />
                        <CitiesAround :citiesAround="citiesAround" />
                    </div>
                </div>
            </section>
        </template>
        <template v-if="produits.data.length > 0">
            <div
                class="mx-auto max-w-full px-2 py-6 sm:px-6 md:space-x-4 md:py-12 lg:px-8"
            >
                <h2 class="text-center text-lg font-semibold text-gray-600">
                    Les activités disponibles à
                    <span class="text-indigo-700">{{
                        formatCityName(city.ville)
                    }}</span>
                    <span class="text-xs text-gray-600">
                        ({{ city.code_postal }})</span
                    >
                </h2>
                <div
                    class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-8 lg:px-8"
                >
                    <div class="md:w-1/2">
                        <div
                            class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2"
                        >
                            <ProduitCard
                                v-for="produit in produits.data"
                                :key="produit.id"
                                :produit="produit"
                                @mouseover="showProduitTooltip(produit)"
                                @mouseout="hideProduitTooltip"
                            />
                        </div>
                        <div class="flex justify-end p-10">
                            <Pagination :links="produits.links" />
                        </div>
                    </div>
                    <div class="space-y-4 md:sticky md:w-1/2">
                        <LeafletMapProduitMultiple
                            class="md:top-2"
                            :produits="produits.data"
                            :hovered-produit="hoveredProduit"
                            :zoom="16"
                        />
                        <CitiesAround :citiesAround="citiesAround" />
                    </div>
                </div>
            </div>
        </template>
        <template v-if="structures.data.length === 0">
            <div
                class="mx-auto min-h-screen max-w-full px-2 py-12 sm:px-6 lg:px-8"
            >
                <p class="font-medium text-gray-700">
                    Dommage, il n'y a pas encore de structures inscrites à
                    <span class="font-semibold text-gray-800">{{
                        formatCityName(city.ville)
                    }}</span>
                </p>
            </div>
        </template>
    </ResultLayout>
</template>
