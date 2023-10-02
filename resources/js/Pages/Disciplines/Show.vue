<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, defineAsyncComponent, provide } from "vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import { HomeIcon, ListBulletIcon, MapIcon } from "@heroicons/vue/24/outline";
import { TransitionRoot } from "@headlessui/vue";
import { useElementVisibility } from "@vueuse/core";

const emit = defineEmits(["mouseover", "mouseout"]);

const props = defineProps({
    familles: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    allCities: Object,
    familles: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    produits: Object,
});

const ProduitCard = defineAsyncComponent(() =>
    import("@/Components/Produits/ProduitCard.vue")
);

const LeafletMapProduitMultiple = defineAsyncComponent(() =>
    import("@/Components/LeafletMapProduitMultiple.vue")
);

const DisciplinesSimilaires = defineAsyncComponent(() =>
    import("@/Components/Disciplines/DisciplinesSimilaires.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

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

const hoveredProduit = ref(null);

const showTooltip = (produit) => {
    hoveredProduit.value = produit.id;
};
const hideTooltip = () => {
    hoveredProduit.value = null;
};
</script>

<template>
    <Head
        :title="discipline.name"
        :description="
            'Vous souhaitez pratiquer un sport de ' +
            discipline.name +
            ' en France ? ' +
            discipline.activites_count +
            ' structures sur notre site prêts à vous accueillir.'
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
                                            'disciplines.show',
                                            discipline.slug
                                        )
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
        </template>
        <template #default>
            <div
                class="sticky left-0 right-0 top-16 z-[9998] bg-transparent backdrop-blur-md"
                ref="categoriesEl"
                v-if="categories.length > 0"
            >
                <CategoriesResultNavigation
                    :discipline="discipline"
                    :allStructureTypes="allStructureTypes"
                    :categories="categories"
                    :firstCategories="firstCategories"
                    :categoriesNotInFirst="categoriesNotInFirst"
                />
            </div>
            <template v-if="produits.data.length > 0">
                <div class="mx-auto min-h-full max-w-full py-6 md:py-12">
                    <TransitionRoot
                        as="template"
                        :show="displayProduits"
                        enter="transition-opacity duration-150"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="transition-opacity duration-150"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div
                            ref="listeStructure"
                            class="w-full px-2 sm:px-6 lg:px-8"
                        >
                            <div
                                class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-4"
                            >
                                <ProduitCard
                                    v-for="(produit, index) in produits.data"
                                    :key="produit.id"
                                    :index="index"
                                    :produit="produit"
                                    :discipline="discipline"
                                    @card-hover="showTooltip(produit)"
                                    @card-out="hideTooltip"
                                    :link="
                                        route('structures.show', {
                                            structure: produit.structure.slug,
                                        })
                                    "
                                    :data="{
                                        discipline: discipline.slug,
                                        category: produit.categorie_id,
                                        produit: produit.id,
                                    }"
                                />
                            </div>
                            <div class="flex justify-end p-10">
                                <Pagination :links="produits.links" />
                            </div>
                            <DisciplinesSimilaires
                                v-if="disciplinesSimilaires.length > 0"
                                :disciplinesSimilaires="disciplinesSimilaires"
                            />
                            <button
                                v-if="displayProduits"
                                type="button"
                                class="fixed inset-x-2 bottom-4 z-[9999] mx-auto flex w-1/2 items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-white hover:bg-gray-800 md:w-1/4"
                                @click="goToMap"
                            >
                                <MapIcon class="mr-2 h-5 w-5" />
                                Voir la carte
                            </button>
                        </div>
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
                                    :zoom="12"
                                />
                                <button
                                    v-if="displayMap"
                                    type="button"
                                    class="fixed inset-x-2 bottom-4 z-[9999] mx-auto flex w-1/2 items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-white hover:bg-gray-800 md:w-1/4"
                                    @click="goToListe"
                                >
                                    <ListBulletIcon class="mr-2 h-5 w-5" />
                                    Voir la liste
                                </button>
                            </div>
                            <DisciplinesSimilaires
                                v-if="disciplinesSimilaires.length > 0"
                                :disciplinesSimilaires="disciplinesSimilaires"
                            />
                        </div>
                    </TransitionRoot>
                </div>
            </template>
            <template v-else>
                <div
                    class="mx-auto flex min-h-full max-w-full flex-col space-y-6 px-2 py-6 sm:px-6 md:py-12 lg:px-8"
                >
                    <p class="w-full font-medium text-gray-700">
                        Il n'y a pas encore d'activités en
                        <span class="font-semibold">{{ discipline.name }}</span
                        >.
                    </p>
                    <div v-if="disciplinesSimilaires.length > 0" class="w-full">
                        <DisciplinesSimilaires
                            :disciplinesSimilaires="disciplinesSimilaires"
                        />
                    </div>
                </div>
            </template>
        </template>
    </ResultLayout>
</template>
