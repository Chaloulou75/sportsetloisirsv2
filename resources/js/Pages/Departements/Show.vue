<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import DisciplineSmallCard from "@/Components/Disciplines/DisciplineSmallCard.vue";
import { TransitionRoot } from "@headlessui/vue";
import { HomeIcon, ListBulletIcon, MapIcon } from "@heroicons/vue/24/outline";
import { useElementVisibility } from "@vueuse/core";

const LeafletMapProduitMultiple = defineAsyncComponent(() =>
    import("@/Components/LeafletMapProduitMultiple.vue")
);

const ProduitCard = defineAsyncComponent(() =>
    import("@/Components/Produits/ProduitCard.vue")
);

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    departement: Object,
    produits: Object,
    structures: Object,
    flattenedDisciplines: Object,
    filters: Object,
});

const mapStructure = ref(null);
const mapIsVisible = useElementVisibility(mapStructure);
const listeStructure = ref(null);
const listeIsVisible = useElementVisibility(listeStructure);

const displayProduits = ref(true);
const displayMap = ref(false);

const goToMap = () => {
    displayProduits.value = !displayProduits.value;
    displayMap.value = !displayMap.value;
    // mapStructure.value.scrollIntoView({ behavior: "smooth" });
};

const goToListe = () => {
    displayProduits.value = !displayProduits.value;
    displayMap.value = !displayMap.value;
    // listeStructure.value.scrollIntoView({ behavior: "smooth" });
};

const hoveredProduit = ref(null);
const hoveredStructure = ref(null);

const showTooltip = (produit) => {
    hoveredProduit.value = produit.id;
};
const hideTooltip = () => {
    hoveredProduit.value = null;
};

const showStructureTooltip = (structure) => {
    hoveredStructure.value = structure.id;
};

const hideStructureTooltip = () => {
    hoveredStructure.value = null;
};
</script>

<template>
    <Head
        :title="departement.departement"
        :description="
            'Envie de faire du sport ' +
            departement.prefixe +
            departement.departement +
            '? Choisissez parmi plus de ' +
            departement.structures_count +
            ' structures pour pratiquer une activité sportive ou de loisirs à ' +
            departement.prefixe +
            departement.departement
        "
    />

    <ResultLayout :listDisciplines="listDisciplines" :allCities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title>
                    {{ departement.departement }}
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
                                    :href="
                                        route(
                                            'departements.show',
                                            departement.slug
                                        )
                                    "
                                    class="flex h-10 shrink-0 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ departement.departement }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>
        <template #default>
            <div
                v-if="produits.data.length > 0"
                class="mx-auto max-w-full px-2 py-6 sm:px-6 md:space-x-4 md:py-12 lg:px-8"
            >
                <h3
                    class="mb-4 text-center text-lg font-semibold text-gray-600 md:mb-8 md:text-2xl"
                >
                    Les disciplines pratiquées {{ departement.prefixe }}
                    <span class="text-indigo-700">{{
                        departement.departement
                    }}</span>
                </h3>
                <div
                    class="flex w-full flex-col flex-wrap items-stretch justify-center gap-3 text-gray-700 md:flex-row"
                >
                    <DisciplineSmallCard
                        v-for="discipline in flattenedDisciplines"
                        :key="discipline.id"
                        :discipline="discipline"
                        :link="
                            route('departements.disciplines.show', {
                                departement: departement.slug,
                                discipline: discipline.slug,
                            })
                        "
                    />
                </div>
            </div>
            <template v-if="produits.data.length > 0">
                <h2
                    class="text-center text-lg font-semibold text-gray-600 md:text-2xl"
                >
                    Les activités disponibles
                    <span class="text-indigo-700">
                        {{ departement.prefixe }}
                        {{ departement.departement }}
                    </span>
                </h2>
                <div class="mx-auto py-6 md:py-12">
                    <TransitionRoot
                        as="div"
                        :show="displayProduits"
                        enter="transition-opacity duration-150"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="transition-opacity duration-150"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <h2
                            class="mb-4 w-full text-center text-lg font-semibold text-gray-600 md:mb-8 md:w-1/2 md:text-2xl"
                        >
                            Les activités
                        </h2>
                        <div
                            class="mx-auto flex w-full flex-col px-2 md:flex-row md:space-x-4"
                        >
                            <div
                                ref="listeStructure"
                                class="w-full px-2 md:w-1/2"
                            >
                                <div
                                    class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-2"
                                >
                                    <ProduitCard
                                        v-for="(
                                            produit, index
                                        ) in produits.data"
                                        :key="produit.id"
                                        :index="index"
                                        :produit="produit"
                                        :discipline="produit.discipline"
                                        @card-hover="showTooltip(produit)"
                                        @card-out="hideTooltip"
                                        :link="
                                            route(
                                                'departements.activites.show',
                                                {
                                                    departement:
                                                        departement.slug,
                                                    activite:
                                                        produit.activite.id,
                                                    produit: produit.id,
                                                }
                                            )
                                        "
                                        :data="{}"
                                    />
                                </div>
                                <div class="flex justify-end p-10">
                                    <Pagination :links="produits.links" />
                                </div>

                                <!-- les structures -->
                                <h2
                                    v-if="structures.data.length > 0"
                                    class="mb-4 text-center text-lg font-semibold text-gray-600 md:mb-8 md:text-2xl"
                                >
                                    Les structures
                                </h2>
                                <div
                                    class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-2"
                                >
                                    <StructureCard
                                        v-for="(
                                            structure, index
                                        ) in structures.data"
                                        :key="structure.id"
                                        :index="index"
                                        :structure="structure"
                                        @card-hover="
                                            showStructureTooltip(structure)
                                        "
                                        @card-out="hideStructureTooltip"
                                        :link="
                                            route(
                                                'departements.structures.show',
                                                {
                                                    departement:
                                                        departement.slug,
                                                    structure: structure.slug,
                                                }
                                            )
                                        "
                                        :data="{}"
                                    />
                                </div>
                                <div class="flex justify-end p-10">
                                    <Pagination :links="structures.links" />
                                </div>

                                <button
                                    v-if="displayProduits"
                                    type="button"
                                    class="fixed inset-x-2 bottom-4 z-[999] mx-auto flex w-3/4 max-w-xs items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-xs text-white transition duration-75 hover:scale-105 hover:bg-gray-800 hover:font-semibold md:hidden md:w-auto md:text-sm"
                                    @click="goToMap"
                                >
                                    <MapIcon class="mr-2 h-5 w-5" />
                                    Afficher la carte
                                </button>
                            </div>
                            <LeafletMapProduitMultiple
                                class="sticky top-32 hidden md:block md:w-1/2"
                                :produits="produits.data"
                                :hovered-produit="hoveredProduit"
                                :structures="structures.data"
                                :hovered-structure="hoveredStructure"
                                :zoom="11"
                            />
                        </div>
                        <!-- Blog -->
                        <h2
                            class="my-4 text-center text-lg font-semibold text-gray-600 md:my-8 md:text-2xl"
                        >
                            Les derniers articles
                        </h2>
                    </TransitionRoot>

                    <TransitionRoot
                        as="template"
                        :show="displayMap"
                        enter="transition-opacity duration-150"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="transition-opacity duration-150"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="mx-auto flex w-full flex-col space-y-4">
                            <div ref="mapStructure" class="w-full">
                                <LeafletMapProduitMultiple
                                    class="md:top-2"
                                    :produits="produits.data"
                                    :hovered-produit="hoveredProduit"
                                    :structures="structures.data"
                                    :hovered-structure="hoveredStructure"
                                    :zoom="12"
                                />
                                <button
                                    v-if="displayMap"
                                    type="button"
                                    class="fixed inset-x-2 bottom-4 z-[1199] mx-auto flex w-3/4 max-w-xs items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-xs text-white transition duration-75 hover:scale-105 hover:bg-gray-800 hover:font-semibold md:w-auto md:text-sm"
                                    @click="goToListe"
                                >
                                    <ListBulletIcon class="mr-2 h-5 w-5" />
                                    Afficher la liste
                                </button>
                            </div>
                        </div>
                    </TransitionRoot>
                </div>
            </template>

            <template v-else>
                <div class="py-6 md:py-12">
                    <div
                        class="mx-auto min-h-screen max-w-full px-2 sm:px-6 lg:px-8"
                    >
                        <p class="font-medium text-gray-700">
                            Dommage, il n'y a pas encore d'activité inscrite
                            {{ departement.prefixe }}
                            <span class="font-semibold text-gray-800">{{
                                departement.departement
                            }}</span>
                        </p>
                    </div>
                </div>
            </template>
        </template>
    </ResultLayout>
</template>
