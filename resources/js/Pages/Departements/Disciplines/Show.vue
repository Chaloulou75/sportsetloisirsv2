<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, defineAsyncComponent, provide } from "vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import DisciplinesSimilaires from "@/Components/Disciplines/DisciplinesSimilaires.vue";
import { useElementVisibility } from "@vueuse/core";
import { HomeIcon, ListBulletIcon, MapIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    familles: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    departement: Object,
    citiesAround: Object,
    produits: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    allCities: Object,
});

const ProduitCard = defineAsyncComponent(() =>
    import("@/Components/Produits/ProduitCard.vue")
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

const categoriesEl = ref(null);
const isCategoriesVisible = useElementVisibility(categoriesEl);
const scrollToCategories = () => {
    if (categoriesEl.value) {
        const offset = window.innerWidth >= 768 ? -60 : -60;
        const scrollY =
            window.scrollY +
            categoriesEl.value.getBoundingClientRect().top +
            offset;
        window.scroll({
            top: scrollY,
            behavior: "smooth",
        });
    }
};
provide("scrollToCategories", scrollToCategories);

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

const hoveredProduit = ref(null);

function showTooltip(produit) {
    hoveredProduit.value = produit.id;
}
function hideTooltip() {
    hoveredProduit.value = null;
}
</script>

<template>
    <Head
        :title="departement.departement"
        :description="
            'Envie de faire du ' +
            discipline.name +
            ' à ' +
            departement.departement +
            '? Choisissez parmi plus de ' +
            departement.structures_count +
            ' structures pour pratiquer une activité sportive ou de loisirs à ' +
            departement.departement
        "
    />

    <ResultLayout
        :listDisciplines="listDisciplines"
        :allCities="allCities"
        :discipline="discipline"
        :categories="props.categories"
        :is-categories-visible="isCategoriesVisible"
    >
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader :discipline="discipline">
                <template v-slot:title>
                    {{ discipline.name }}
                    <span class="lowercase">{{ departement.prefixe }}</span>
                    {{ departement.departement }}
                </template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex rounded-lg border border-gray-200 text-gray-600"
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
                                            departement.id
                                        )
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ departement.departement }}
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
                                        route('departements.disciplines.show', {
                                            departement: departement.id,
                                            discipline: discipline.slug,
                                        })
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ discipline.name }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
            <div ref="categoriesEl">
                <CategoriesResultNavigation
                    :departement="departement"
                    :discipline="discipline"
                    :allStructureTypes="allStructureTypes"
                    :categories="props.categories"
                    :firstCategories="firstCategories"
                    :categoriesNotInFirst="categoriesNotInFirst"
                />
            </div>
        </template>
        <template #default>
            <template v-if="produits.data.length > 0">
                <div
                    class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-6 sm:px-6 md:flex-row md:space-x-4 md:py-12 lg:px-8"
                >
                    <div ref="listeStructure" class="md:w-1/2">
                        <div
                            class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-2"
                        >
                            <ProduitCard
                                v-for="(produit, index) in produits.data"
                                :key="produit.id"
                                :index="index"
                                :produit="produit"
                                :discipline="discipline"
                                @mouseover="showTooltip(produit)"
                                @mouseout="hideTooltip()"
                                :link="
                                    route('structures.show', {
                                        structure: produit.structure.slug,
                                    })
                                "
                                :data="{
                                    discipline: discipline.slug,
                                    category: produit.categorie_id,
                                }"
                            />
                        </div>
                        <div class="flex justify-end p-10">
                            <Pagination :links="produits.links" />
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
                            <LeafletMapProduitMultiple
                                class="md:top-2"
                                :produits="props.produits.data"
                                :hovered-produit="hoveredProduit"
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
                        <DisciplinesSimilaires
                            v-if="disciplinesSimilaires.length > 0"
                            :disciplinesSimilaires="props.disciplinesSimilaires"
                        />
                    </div>
                </div>
            </template>
            <template v-else>
                <div
                    class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-6 sm:px-6 md:flex-row md:space-x-4 md:py-12 lg:px-8"
                >
                    <p class="w-full font-medium text-gray-700 md:w-2/3">
                        Il n'y a pas encore d'activités en
                        <span class="font-semibold">{{ discipline.name }}</span
                        >{{ departement.prefixe }}
                        <span class="font-semibold">
                            {{ departement.departement }}</span
                        >.
                    </p>
                    <div
                        v-if="disciplinesSimilaires.length > 0"
                        class="w-full px-4 md:w-1/3"
                    >
                        <DisciplinesSimilaires
                            :disciplinesSimilaires="props.disciplinesSimilaires"
                        />
                    </div>
                </div>
            </template>
        </template>
    </ResultLayout>
</template>
