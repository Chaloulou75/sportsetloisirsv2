<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ref, defineAsyncComponent, watch, onMounted, toRaw } from "vue";
import { debounce } from "lodash";
import CritereForm from "@/Components/Criteres/CritereForm.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";
import autoAnimate from "@formkit/auto-animate";
import { TransitionRoot } from "@headlessui/vue";
import {
    AdjustmentsHorizontalIcon,
    HomeIcon,
    ListBulletIcon,
    MapIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import dayjs from "dayjs";
import "dayjs/locale/fr";
dayjs.locale("fr");

const props = defineProps({
    familles: Object,
    category: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    city: Object,
    citiesAround: Object,
    produits: Object,
    structures: Object,
    discipline: Object,
    criteres: Object,
    listDisciplines: Object,
    allCities: Object,
    posts: Object,
    filters: Object,
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

const PostFeaturedCard = defineAsyncComponent(() =>
    import("@/Components/Posts/PostFeaturedCard.vue")
);

const DisciplinesSimilaires = defineAsyncComponent(() =>
    import("@/Components/Disciplines/DisciplinesSimilaires.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const mapStructure = ref(null);
const listeStructure = ref(null);

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

const showCriteres = ref(false);

const toggleCriteres = () => {
    showCriteres.value = !showCriteres.value;
};

const showCriteresLg = ref(true);

const toggleCriteresLg = () => {
    showCriteresLg.value = !showCriteresLg.value;
};

const filteredProduits = ref(props.produits.data);
const filteredStructures = ref(props.structures.data);

const onFilteredProduitsUpdate = (filtered) => {
    filteredProduits.value = filtered;
};

const onfilteredStructuresUpdate = (filteredStr) => {
    filteredStructures.value = filteredStr;
};

const formCriteres = useForm({
    criteresBase: props.filters?.crit ?? {},
    sousCriteres: props.filters?.ssCrit ?? {},
    page: props.produits.meta.current_page,
});
const debouncedFilter = debounce((newFormCriteres) => {
    router.post(
        route("villes.disciplines.categories.show", {
            city: props.city,
            discipline: props.discipline.slug,
            category: props.category.slug,
        }),
        {
            crit: newFormCriteres.criteresBase,
            ssCrit: newFormCriteres.sousCriteres,
            page: newFormCriteres.page,
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ["produits", "filters"],
            onSuccess: () => {
                filteredProduits.value = props.produits.data;
            },
        }
    );
}, 350);

watch(
    () => formCriteres,
    (newFormCriteres) => {
        debouncedFilter(newFormCriteres);
    },
    { deep: true }
);

const isEqual = (obj1, obj2) => JSON.stringify(obj1) === JSON.stringify(obj2);

watch(
    () => formCriteres.criteresBase,
    (newCritValue) => {
        // Get the raw (non-reactive) version of the new value
        const rawNewValue = toRaw(newCritValue);
        // Compare with the previous value
        if (!isEqual(rawNewValue, previousCriteresBase)) {
            Object.keys(rawNewValue).forEach((critereId) => {
                if (
                    !isEqual(
                        rawNewValue[critereId],
                        previousCriteresBase[critereId]
                    )
                ) {
                    resetSousCriteres(critereId, rawNewValue[critereId]);
                }
            });
        }
        // Update the previous value for the next comparison
        previousCriteresBase = { ...rawNewValue };
    },
    { deep: true }
);

// Initialize previousCriteresBase
let previousCriteresBase = { ...toRaw(formCriteres.criteresBase) };

const resetSousCriteres = (critereId, newValeur) => {
    const critere = props.criteres.find((c) => c.id.toString() === critereId);

    if (critere && critere.valeurs) {
        critere.valeurs.forEach((valeur) => {
            if (valeur.sous_criteres) {
                valeur.sous_criteres.forEach((sousCritere) => {
                    if (
                        formCriteres.sousCriteres[sousCritere.id] !== undefined
                    ) {
                        formCriteres.sousCriteres[sousCritere.id] = null;
                    }
                });
            }
        });
    }
};

const resetFormCriteres = () => {
    formCriteres.reset();
    formCriteres.page = 1;
    debouncedFilter(formCriteres);
};

const handlePageChange = (newPage) => {
    if (newPage === "previous") {
        formCriteres.page = Math.max(1, formCriteres.page - 1);
    } else if (newPage === "next") {
        formCriteres.page = formCriteres.page + 1;
    } else {
        formCriteres.page = newPage;
    }
    debouncedFilter(formCriteres);
};
const listToAnimate = ref();
onMounted(() => {
    if (listToAnimate.value) {
        autoAnimate(listToAnimate.value);
    }
});
</script>

<template>
    <Head
        :title="`${category.nom_categorie_client} de ${
            discipline.name
        } à ${formatCityName(city.ville)}.`"
    >
        <meta
            head-key="description"
            name="description"
            :content="`${category.nom_categorie_client} de ${
                discipline.name
            } à ${formatCityName(city.ville)}. Choisissez parmi plus de ${
                city.structures_count
            } structures pour pratiquer une activité sportive ou de loisirs à ${formatCityName(
                city.ville
            )}`"
    /></Head>
    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
        :discipline="discipline"
        :city="city"
        :categories="categories"
        :current-category="category"
    >
        <template #header>
            <ResultsHeader :discipline="discipline">
                <template v-slot:title>
                    {{ discipline.name }}
                    <span class="lowercase">à</span>
                    {{ formatCityName(city.ville) }}
                </template>
                <template v-slot:subtitle>{{
                    category.nom_categorie_client
                }}</template>

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
                                    :href="route('villes.show', city.slug)"
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
                                            city: city.slug,
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
                                            'villes.disciplines.categories.show',
                                            {
                                                city: city.slug,
                                                discipline: discipline.slug,
                                                category: category.slug,
                                            }
                                        )
                                    "
                                    class="hidden h-10 shrink-0 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900 md:flex"
                                >
                                    {{ category.nom_categorie_client }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>
        <template #default>
            <!-- Criteres -->
            <div
                ref="criteresEl"
                class="sticky left-0 right-0 top-16 z-50 bg-transparent backdrop-blur-md"
            >
                <CategoriesResultNavigation
                    :city="city"
                    :discipline="discipline"
                    :all-structure-types="allStructureTypes"
                    :category="category"
                    :categories="categories"
                    :first-categories="firstCategories"
                    :categories-not-in-first="categoriesNotInFirst"
                    :show-criteres="showCriteresLg"
                    @call-toggle-criteres="toggleCriteresLg"
                />
                <!-- Criteres -->
                <div
                    class="flex w-full items-center justify-between border-b border-gray-300 px-2 py-3 md:hidden"
                >
                    <h3 class="font-semibold">
                        {{ category.nom_categorie_client }}
                    </h3>
                    <button type="button" @click="toggleCriteres">
                        <XMarkIcon v-if="showCriteres" class="h-6 w-6" />
                        <AdjustmentsHorizontalIcon v-else class="h-6 w-6" />
                    </button>
                </div>
                <form
                    class="relative mx-auto w-full flex-col items-center justify-center gap-6 overflow-x-auto rounded bg-gray-50 px-2 py-2 backdrop-blur-md md:flex-row md:items-start md:justify-between md:px-6 md:pt-4"
                    :class="{
                        flex: showCriteres,
                        hidden: !showCriteres,
                        'md:flex': showCriteresLg,
                        'md:hidden': !showCriteresLg,
                    }"
                >
                    <CritereForm
                        v-if="criteres"
                        :criteres="criteres"
                        :filters="filters"
                        v-model:criteres-base="formCriteres.criteresBase"
                        v-model:sous-criteres="formCriteres.sousCriteres"
                        @reset-criteres="resetFormCriteres"
                    />
                </form>
            </div>

            <template v-if="filteredProduits.length > 0">
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
                                    ref="listToAnimate"
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
                                                'villes.disciplines.categories.activites.show',
                                                {
                                                    city: city,
                                                    discipline: discipline,
                                                    category: category.slug,
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
                                <div class="my-10 flex justify-end">
                                    <Pagination
                                        :links="produits.meta.links"
                                        :only="['produits', 'filters']"
                                        @page-changed="handlePageChange"
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
                                                'villes.disciplines.categories.structures.show',
                                                {
                                                    city: city,
                                                    discipline: discipline,
                                                    category: category.slug,
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
                                <div class="my-10 flex justify-end">
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
                                class="sticky top-48 hidden md:block md:w-1/2"
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
                                    class="fixed inset-x-2 bottom-4 z-[999] mx-auto flex w-3/4 max-w-xs items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-xs text-white transition duration-75 hover:scale-105 hover:bg-gray-800 hover:font-semibold md:hidden md:w-auto md:text-sm"
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
                        Il n'y a pas encore d'activités en
                        <span class="font-semibold">{{ discipline.name }}</span
                        >à
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
                    <CitiesAround
                        v-if="citiesAround.length > 0"
                        :cities-around="props.citiesAround"
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
