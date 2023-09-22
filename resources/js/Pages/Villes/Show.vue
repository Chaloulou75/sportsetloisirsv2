<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";
import { HomeIcon, ListBulletIcon, MapIcon } from "@heroicons/vue/24/outline";
import { useElementVisibility } from "@vueuse/core";

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

    <ResultLayout
        :listDisciplines="listDisciplines"
        :allCities="allCities"
        :city="city"
    >
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title>
                    {{ formatCityName(city.ville) }}
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
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ formatCityName(city.ville) }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
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
                    class="relative mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-8 lg:px-8"
                >
                    <div class="md:w-1/2" ref="listeStructure">
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
                                :link="
                                    route('structures.show', {
                                        structure: structure.slug,
                                    })
                                "
                                :data="{
                                    city: city.id,
                                }"
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
