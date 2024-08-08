<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";
import { TransitionRoot } from "@headlessui/vue";
import { HomeIcon, ListBulletIcon, MapIcon } from "@heroicons/vue/24/outline";
import { useElementVisibility } from "@vueuse/core";

const props = defineProps({
    familles: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    city: Object,
    citiesAround: Object,
    produits: Object,
    structures: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    allCities: Object,
    posts: Object,
});

const ProduitCard = defineAsyncComponent(() =>
    import("@/Components/Produits/ProduitCard.vue")
);

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const LeafletMapProduitMultiple = defineAsyncComponent(() =>
    import("@/Components/Maps/LeafletMapProduitMultiple.vue")
);

const DisciplinesSimilaires = defineAsyncComponent(() =>
    import("@/Components/Disciplines/DisciplinesSimilaires.vue")
);

const PostFeaturedCard = defineAsyncComponent(() =>
    import("@/Components/Posts/PostFeaturedCard.vue")
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
};

const goToListe = () => {
    displayProduits.value = !displayProduits.value;
    displayMap.value = !displayMap.value;
};

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
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

const filteredProduits = ref(props.produits.data);
const onFilteredProduitsUpdate = (filtered) => {
    filteredProduits.value = filtered;
};

const filteredStructures = ref(props.structures.data);
const onfilteredStructuresUpdate = (filteredStr) => {
    filteredStructures.value = filteredStr;
};
</script>

<template>
    <Head :title="` ${discipline.name} à ${formatCityName(city.ville)}`">
        <meta
            head-key="description"
            name="description"
            :content="
                'Envie de faire du ' +
                discipline.name +
                ' à ' +
                formatCityName(city.ville) +
                '? Choisissez parmi plus de ' +
                city.structures_count +
                ' structures pour pratiquer une activité sportive ou de loisirs à ' +
                formatCityName(city.ville)
            "
    /></Head>

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
        :discipline="discipline"
        :city="city"
        :categories="props.categories"
    >
        <template #header>
            <ResultsHeader :discipline="discipline">
                <template v-slot:title>
                    {{ discipline.name }} <span class="lowercase">à</span>
                    {{ formatCityName(city.ville) }}
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
                                    :href="route('villes.show', city.slug)"
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
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
                                            city: city.slug,
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
        </template>

        <template #default>
            <div
                class="sticky left-0 right-0 top-16 z-50 bg-transparent backdrop-blur-md"
                ref="categoriesEl"
                v-if="categories.length > 0"
            >
                <CategoriesResultNavigation
                    :city="city"
                    :discipline="discipline"
                    :all-structure-types="allStructureTypes"
                    :categories="props.categories"
                    :first-categories="firstCategories"
                    :categories-not-in-first="categoriesNotInFirst"
                />
            </div>
            <template v-if="produits.data.length > 0">
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
                                    class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:gap-8 lg:grid-cols-2"
                                >
                                    <ProduitCard
                                        v-for="(
                                            produit, index
                                        ) in filteredProduits"
                                        :key="produit.id"
                                        :index="index"
                                        :produit="produit"
                                        :discipline="discipline"
                                        @card-hover="showTooltip(produit)"
                                        @card-out="hideTooltip"
                                        :link="
                                            route(
                                                'villes.disciplines.activites.show',
                                                {
                                                    city: city,
                                                    discipline: discipline,
                                                    activite: produit.activite,
                                                    slug: produit.activite
                                                        .slug_title,
                                                    produit: produit.id,
                                                }
                                            )
                                        "
                                        :data="{}"
                                    />
                                </div>
                                <div v-if="filteredProduits.length === 0">
                                    <p
                                        class="text-lg font-medium text-gray-700"
                                    >
                                        Pas d'activité dans cette zone de la
                                        carte
                                        <span class="text-base italic"
                                            >(Utilisez la carte ou les liens de
                                            pagination ci dessous pour trouver
                                            des activités dans votre
                                            région).</span
                                        >
                                    </p>
                                </div>
                                <div class="flex justify-end p-10">
                                    <Pagination
                                        :links="produits.meta.links"
                                        :only="['produits']"
                                    />
                                </div>

                                <!-- les structures -->
                                <h2
                                    v-if="structures.data.length > 0"
                                    class="mb-4 text-center text-lg font-semibold text-gray-600 md:mb-8 md:text-2xl"
                                >
                                    Les structures
                                </h2>
                                <div
                                    class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:gap-8 lg:grid-cols-2"
                                >
                                    <StructureCard
                                        v-for="(
                                            structure, index
                                        ) in filteredStructures"
                                        :key="structure.id"
                                        :index="index"
                                        :structure="structure"
                                        @card-hover="
                                            showStructureTooltip(structure)
                                        "
                                        @card-out="hideStructureTooltip"
                                        :link="
                                            route(
                                                'villes.disciplines.structures.show',
                                                {
                                                    city: city,
                                                    discipline: discipline,
                                                    structure: structure,
                                                }
                                            )
                                        "
                                        :data="{}"
                                    />
                                </div>
                                <div v-if="filteredStructures.length === 0">
                                    <p
                                        class="text-lg font-medium text-gray-700"
                                    >
                                        Pas de structure dans cette zone de la
                                        carte
                                        <span class="text-base italic"
                                            >(Utilisez la carte ou les liens de
                                            pagination pour trouver des
                                            structures dans votre région).</span
                                        >
                                    </p>
                                </div>
                                <div class="flex justify-end p-10">
                                    <Pagination
                                        :links="structures.meta.links"
                                        :only="['structures']"
                                    />
                                </div>

                                <button
                                    v-if="displayProduits"
                                    type="button"
                                    class="fixed inset-x-2 bottom-4 z-[999] mx-auto flex w-3/4 max-w-xs items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-xs text-white transition duration-75 hover:scale-105 hover:bg-gray-800 hover:font-semibold md:hidden md:w-auto md:text-sm"
                                    @click="goToMap"
                                >
                                    <MapIcon class="mr-2 h-5 w-5" />
                                    Carte
                                </button>
                            </div>
                            <LeafletMapProduitMultiple
                                class="sticky top-32 hidden md:block md:w-1/2"
                                :produits="produits.data"
                                :hovered-produit="hoveredProduit"
                                :structures="structures.data"
                                :hovered-structure="hoveredStructure"
                                v-model:filteredProduits="filteredProduits"
                                @update:filteredProduits="
                                    onFilteredProduitsUpdate
                                "
                                v-model:filteredStructures="filteredStructures"
                                @update:filteredStructures="
                                    onfilteredStructuresUpdate
                                "
                                :zoom="11"
                            />
                        </div>
                        <!-- Blog -->
                        <div
                            v-if="posts.length > 0"
                            class="my-8 px-3 md:my-16 md:px-6 lg:px-8"
                        >
                            <h2
                                class="my-4 text-center text-lg font-semibold text-gray-600 md:my-8 md:text-2xl"
                            >
                                Les derniers articles
                            </h2>
                            <div
                                v-if="posts.length > 0"
                                class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                            >
                                <PostFeaturedCard
                                    v-for="post in posts"
                                    :key="post.id"
                                    :post="post"
                                />
                            </div>
                        </div>
                        <!-- les disciplines similaires -->
                        <DisciplinesSimilaires
                            v-if="discipline.disciplines_similaires.length > 0"
                            :disciplines-similaires="
                                discipline.disciplines_similaires
                            "
                        />
                        <CitiesAround
                            v-if="citiesAround.length > 0"
                            :cities-around="props.citiesAround"
                        />
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
                                    class="fixed inset-x-2 bottom-4 z-40 mx-auto flex w-3/4 max-w-xs items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-xs text-white transition duration-75 hover:scale-105 hover:bg-gray-800 hover:font-semibold md:w-auto md:text-sm"
                                    @click="goToListe"
                                >
                                    <ListBulletIcon class="mr-2 h-5 w-5" />
                                    Liste
                                </button>
                            </div>
                        </div>
                    </TransitionRoot>
                </div>
            </template>
            <template v-else>
                <div
                    class="mx-auto flex min-h-full max-w-full flex-col px-2 py-6 sm:px-6 md:flex-row md:space-x-4 md:py-12 lg:px-8"
                >
                    <p class="w-full font-medium text-gray-700 md:w-2/3">
                        Il n'y a pas encore d'activité en
                        <span class="font-semibold">{{ discipline.name }}</span>
                        à
                        <span class="font-semibold">{{
                            formatCityName(city.ville)
                        }}</span
                        >.
                    </p>
                </div>
                <div
                    v-if="discipline.disciplines_similaires.length > 0"
                    class="flex w-full flex-col items-start px-4"
                >
                    <DisciplinesSimilaires
                        :disciplines-similaires="
                            discipline.disciplines_similaires
                        "
                    />
                </div>
                <CitiesAround
                    v-if="citiesAround.length > 0"
                    :cities-around="props.citiesAround"
                />
                <!-- Blog -->
                <div
                    v-if="posts.length > 0"
                    class="my-8 px-3 md:my-16 md:px-6 lg:px-8"
                >
                    <h2
                        class="my-4 text-center text-lg font-semibold text-gray-600 md:my-8 md:text-2xl"
                    >
                        Les derniers articles
                    </h2>
                    <div
                        class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                    >
                        <PostFeaturedCard
                            v-for="post in posts"
                            :key="post.id"
                            :post="post"
                        />
                    </div>
                </div>
            </template>
        </template>
    </ResultLayout>
</template>
