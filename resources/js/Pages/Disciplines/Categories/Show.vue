<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    ref,
    computed,
    defineAsyncComponent,
    provide,
    watch,
    onMounted,
} from "vue";
import CheckboxForm from "@/Components/Forms/CheckboxForm.vue";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import RangeInputForm from "@/Components/Forms/RangeInputForm.vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import { TransitionRoot } from "@headlessui/vue";
import {
    AdjustmentsHorizontalIcon,
    ArrowPathIcon,
    HomeIcon,
    ListBulletIcon,
    MapIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { useElementVisibility } from "@vueuse/core";

const props = defineProps({
    familles: Object,
    category: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    criteres: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
    listDisciplines: Object,
    allCities: Object,
    produits: Object,
    structures: Object,
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

const criteresEl = ref(null);
const isCriteresVisible = useElementVisibility(criteresEl);
const scrollToCriteres = () => {
    if (criteresEl.value) {
        const offset = window.innerWidth >= 768 ? -125 : -135;
        const scrollY =
            window.scrollY +
            criteresEl.value.getBoundingClientRect().top +
            offset;
        window.scroll({
            top: scrollY,
            behavior: "smooth",
        });
    }
};

provide("scrollToCriteres", scrollToCriteres);

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

const formCriteres = ref({
    criteres: {},
    sousCriteres: {},
});
const selectedCriteres = ref([]);
const selectedSousCriteres = ref([]);
const filteredProduits = ref(props.produits.data);
const filteredStructures = ref(props.structures.data);

const onFilteredProduitsUpdate = (filtered) => {
    filteredProduits.value = filtered;
};

const onfilteredStructuresUpdate = (filteredStr) => {
    filteredStructures.value = filteredStr;
};

const updateSelectedCheckboxes = (critereId, optionValue, checked) => {
    if (checked) {
        if (!formCriteres.value.criteres[critereId]) {
            formCriteres.value.criteres[critereId] = [optionValue];
        } else {
            formCriteres.value.criteres[critereId].push(optionValue);
        }
    } else {
        const selectedCritere = formCriteres.value.criteres[critereId];
        if (selectedCritere) {
            const index = selectedCritere.indexOf(optionValue);
            if (index !== -1) {
                selectedCritere.splice(index, 1);
            }
            if (selectedCritere.length === 0) {
                delete formCriteres.value.criteres[critereId];
            }
        }
    }
};

const isCheckboxSelected = computed(() => {
    return (critereId, optionValue) => {
        return (
            formCriteres.value.criteres[critereId] &&
            formCriteres.value.criteres[critereId].includes(optionValue)
        );
    };
});

const filterProducts = () => {
    if (selectedCriteres.value.length === 0) {
        filteredProduits.value = props.produits.data;
    } else {
        filteredProduits.value = props.produits.data.filter((produit) => {
            return (
                selectedCriteres.value.every((selectedCritere) => {
                    if (!!selectedCritere.inclus_all === true) {
                        return true; // Do not apply the filter
                    } else if (Array.isArray(selectedCritere)) {
                        return selectedCritere.some((critereInArray) => {
                            return produit.criteres.some(
                                (produitCritere) =>
                                    produitCritere.valeur_id ===
                                    critereInArray.id
                            );
                        });
                    } else {
                        return produit.criteres.some((produitCritere) => {
                            return (
                                produitCritere.valeur_id ===
                                    selectedCritere.id ||
                                !!produitCritere.critere_valeur.inclus_all ===
                                    true
                            );
                        });
                    }
                }) &&
                selectedSousCriteres.value.every((selectedSousCritere) => {
                    return produit.criteres.some((produitCritere) => {
                        return (
                            produitCritere.sous_criteres &&
                            produitCritere.sous_criteres.some(
                                (sousCritere) =>
                                    sousCritere.sous_critere_valeur_id ===
                                    selectedSousCritere.id
                            )
                        );
                    });
                })
            );
        });
    }
};

watch(
    () => formCriteres.value.criteres,
    (newCriteres) => {
        selectedCriteres.value = Object.values(newCriteres).filter(Boolean);
        filterProducts();
    },
    { deep: true }
);

watch(
    () => formCriteres.value.sousCriteres,
    (newSousCriteres) => {
        selectedSousCriteres.value =
            Object.values(newSousCriteres).filter(Boolean);
        filterProducts();
    },
    { deep: true }
);

const resetFormCriteres = () => {
    formCriteres.value.criteres = {};
    formCriteres.value.sousCriteres = {};
    selectedCriteres.value = [];
    filterProducts();
};

onMounted(() => {
    filterProducts();
});
</script>

<template>
    <Head
        :title="`${category.nom_categorie_client} de ${discipline.name}`"
        :description="`${category.nom_categorie_client} de ${discipline.name}.`"
    />

    <ResultLayout
        :listDisciplines="listDisciplines"
        :allCities="allCities"
        :discipline="discipline"
        :current-category="category"
        :is-criteres-visible="isCriteresVisible"
    >
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader :discipline="discipline">
                <template v-slot:title>
                    {{ discipline.name }}
                </template>
                <template v-slot:subtitle>
                    {{ category.nom_categorie_client }}
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
                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('disciplines.categories.show', {
                                            discipline: discipline.slug,
                                            category: category.slug,
                                        })
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
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
            <div
                ref="criteresEl"
                class="sticky left-0 right-0 top-16 z-[1199] bg-transparent backdrop-blur-md"
            >
                <CategoriesResultNavigation
                    :category="category"
                    :discipline="discipline"
                    :allStructureTypes="allStructureTypes"
                    :categories="categories"
                    :firstCategories="firstCategories"
                    :categoriesNotInFirst="categoriesNotInFirst"
                    :showCriteres="showCriteresLg"
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

                <div
                    v-if="criteres"
                    class="mx-auto w-full flex-col items-start justify-center space-x-0 space-y-2 rounded bg-transparent px-2 py-2 backdrop-blur-md md:flex-row md:items-center md:space-x-6 md:space-y-0 md:px-6"
                    :class="{
                        flex: showCriteres,
                        hidden: !showCriteres,
                        'md:flex': showCriteresLg,
                        'md:hidden': !showCriteresLg,
                    }"
                >
                    <div
                        v-for="critere in criteres"
                        :key="critere.id"
                        class="w-full max-w-full md:w-auto"
                    >
                        <!-- select -->
                        <SelectForm
                            :classes="'flex items-center space-x-4'"
                            class="max-w-sm"
                            v-if="critere.type_champ_form === 'select'"
                            :name="critere.nom"
                            v-model="formCriteres.criteres[critere.id]"
                            :options="critere.valeurs"
                        />

                        <!-- checkbox -->
                        <CheckboxForm
                            :classes="'flex items-center space-x-4'"
                            class="max-w-sm"
                            v-if="critere.type_champ_form === 'checkbox'"
                            :critere="critere"
                            :name="critere.nom"
                            v-model="formCriteres.criteres[critere.id]"
                            :options="critere.valeurs"
                            :is-checkbox-selected="isCheckboxSelected"
                            @update-selected-checkboxes="
                                updateSelectedCheckboxes
                            "
                        />
                        <!-- radio -->
                        <div v-if="critere.type_champ_form === 'radio'">
                            <div class="flex items-center space-x-4">
                                <label
                                    :for="critere.nom"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ critere.nom }}
                                </label>

                                <div class="flex rounded-md">
                                    <div>
                                        <label
                                            class="inline-flex items-center"
                                            v-for="(
                                                option, index
                                            ) in critere.valeurs"
                                            :key="option.id"
                                        >
                                            <input
                                                v-model="
                                                    formCriteres.criteres[
                                                        critere.id
                                                    ]
                                                "
                                                type="radio"
                                                class="form-radio"
                                                :name="option.valeur"
                                                :value="option.valeur"
                                                checked
                                            />
                                            <span class="ml-2">{{
                                                option.valeur
                                            }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- input text -->
                        <div v-if="critere.type_champ_form === 'text'">
                            <div class="flex items-center space-x-4">
                                <label
                                    :for="critere.nom"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ critere.nom }}
                                </label>
                                <div class="flex rounded-md">
                                    <input
                                        type="text"
                                        v-model="
                                            formCriteres.criteres[critere.id]
                                        "
                                        :name="critere.nom"
                                        :id="critere.nom"
                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                        placeholder=""
                                        autocomplete="none"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            class="max-w-sm"
                            v-if="critere.type_champ_form === 'number'"
                        >
                            <label
                                :for="critere.nom"
                                class="block text-sm font-medium text-gray-700"
                            >
                                {{ critere.nom }}
                            </label>
                            <div class="mt-1 flex rounded-md">
                                <TextInput
                                    type="number"
                                    min="1"
                                    max="59"
                                    v-model="formCriteres.criteres[critere.id]"
                                    :name="critere.nom"
                                    :id="critere.nom"
                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                    placeholder=""
                                    autocomplete="none"
                                />
                            </div>
                        </div>

                        <!-- Dates x 2 -->
                        <!-- <div
                            v-if="critere.type_champ_form === 'dates'"
                            class="flex max-w-sm flex-col items-start space-y-3"
                        >
                            <div class="flex items-center">
                                <input
                                    v-model="addDatesOpened"
                                    id="addDatesOpened"
                                    type="checkbox"
                                    class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                />
                                <label
                                    for="addDatesOpened"
                                    class="ml-2 text-sm font-medium text-gray-700"
                                    >Ajouter vos dates d'ouvertures
                                </label>
                            </div>
                            <OpenDaysForm
                                v-if="addDatesOpened"
                                class="w-full"
                                v-model="form.date"
                                :name="`Dates d'ouvertures`"
                            />
                        </div> -->

                        <!-- Heures ouverture / fermeture -->
                        <!-- <div
                            v-if="critere.type_champ_form === 'times'"
                            class="flex max-w-sm flex-col items-start space-y-3"
                        >
                            <div class="flex items-center">
                                <input
                                    v-model="addHoursOpened"
                                    id="addHoursOpened"
                                    type="checkbox"
                                    class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                />
                                <label
                                    for="addHoursOpened"
                                    class="ml-2 text-sm font-medium text-gray-700"
                                    >Ajouter vos horaires d'ouvertures
                                </label>
                            </div>
                            <OpenTimesForm
                                v-if="addHoursOpened"
                                class="w-full"
                                v-model="form.time"
                                :name="`Horaires (ouverture /
                                                    fermeture)`"
                            />
                        </div> -->

                        <!-- Date seule -->
                        <!-- <div
                            v-if="critere.type_champ_form === 'date'"
                            class="flex max-w-sm flex-col items-start space-y-3"
                        >
                            <div class="flex items-center">
                                <input
                                    v-model="addDateOpen"
                                    id="addDateOpen"
                                    type="checkbox"
                                    class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                />
                                <label
                                    for="addDateOpen"
                                    class="ml-2 text-sm font-medium text-gray-700"
                                    >Ajouter une date de début
                                </label>
                            </div>
                            <SingleDateForm
                                v-if="addDateOpen"
                                class="w-full"
                                v-model="form.date_debut"
                                :name="`Date de début`"
                            />
                        </div> -->

                        <!-- Heure seule -->
                        <!-- <div
                            v-if="critere.type_champ_form === 'time'"
                            class="flex max-w-sm flex-col items-start space-y-3"
                        >
                            <div class="flex items-center">
                                <input
                                    v-model="addHourOpen"
                                    id="addHourOpen"
                                    type="checkbox"
                                    class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                />
                                <label
                                    for="addHourOpen"
                                    class="ml-2 text-sm font-medium text-gray-700"
                                    >Ajouter une heure d'ouverture
                                </label>
                            </div>
                            <SingleTimeForm
                                v-if="addHourOpen"
                                class="w-full"
                                v-model="form.time_debut"
                                :name="`Horaire de début`"
                            />
                        </div> -->

                        <!-- Mois -->
                        <!-- <div v-if="critere.type_champ_form === 'mois'">
                            <div
                                class="flex max-w-sm flex-col items-start space-y-3"
                            >
                                <div class="flex items-center">
                                    <input
                                        v-model="addMonthsOpen"
                                        id="addMonthsOpen"
                                        type="checkbox"
                                        class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label
                                        for="addMonthsOpen"
                                        class="ml-2 text-sm font-medium text-gray-700"
                                        >Ajouter vos mois d'ouvertures
                                    </label>
                                </div>
                                <OpenMonthsForm
                                    v-if="addMonthsOpen"
                                    class="w-full"
                                    v-model="form.months"
                                    :name="`Mois d'ouverture`"
                                />
                            </div>
                        </div> -->

                        <!-- Adresse -->
                        <!-- <div
                            v-if="critere.type_champ_form === 'adresse'"
                            class="flex w-full max-w-sm flex-col space-y-2"
                        >
                            <div v-if="!addAddress" class="flex-1">
                                <label
                                    for="
                                                            adresse
                                                        "
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Adresse
                                </label>
                                <div class="mt-1 flex rounded-md">
                                    <select
                                        name="
                                                                adresse
                                                            "
                                        id="
                                                                adresse
                                                            "
                                        v-model="form.adresse"
                                        class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                    >
                                        <option
                                            v-for="adresse in structure.adresses"
                                            :key="adresse.id"
                                            :value="adresse.id"
                                        >
                                            {{ adresse.address }}
                                            -
                                            {{ adresse.zip_code }},
                                            {{ adresse.city }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input
                                    v-model="addAddress"
                                    id="addAddress"
                                    type="checkbox"
                                    class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                />
                                <label
                                    for="addAddress"
                                    class="ml-2 text-sm font-medium text-gray-700"
                                    >Ajouter une adresse</label
                                >
                            </div>
                        </div> -->

                        <!-- Range km  -->
                        <div
                            v-if="critere.type_champ_form === 'rayon'"
                            class="flex w-full max-w-sm flex-col items-start space-y-3"
                        >
                            <RangeInputForm
                                class="w-full max-w-sm"
                                v-model="formCriteres.criteres[critere.id]"
                                :min="0"
                                :max="200"
                                :name="`Rayon de déplacement (en km)`"
                                :metric="`Km`"
                            />
                        </div>
                        <!-- sous criteres -->
                        <div v-for="valeur in critere.valeurs" :key="valeur.id">
                            <div
                                v-for="souscritere in valeur.sous_criteres"
                                :key="souscritere.id"
                                class=""
                            >
                                <SelectForm
                                    :classes="'flex items-center space-x-4'"
                                    class="max-w-sm py-2"
                                    v-if="
                                        formCriteres.criteres[critere.id] ===
                                            valeur &&
                                        souscritere.type_champ_form ===
                                            'select' &&
                                        souscritere.dis_cat_crit_val_id ===
                                            valeur.id
                                    "
                                    :name="souscritere.nom"
                                    v-model="
                                        formCriteres.sousCriteres[
                                            souscritere.id
                                        ]
                                    "
                                    :options="souscritere.sous_criteres_valeurs"
                                />

                                <div
                                    v-if="
                                        formCriteres.criteres[critere.id] ===
                                            valeur &&
                                        souscritere.type_champ_form ===
                                            'number' &&
                                        souscritere.dis_cat_crit_val_id ===
                                            valeur.id
                                    "
                                    class="mt-2 flex items-center space-x-4"
                                >
                                    <InputLabel
                                        class="py-2"
                                        for="Quantité"
                                        value="Quantité"
                                    />
                                    <TextInput
                                        class="w-full"
                                        type="number"
                                        id="Nombre"
                                        name="Nombre"
                                        v-model="
                                            formCriteres.sousCriteres[
                                                souscritere.id
                                            ]
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <button
                        class="flex w-full justify-center md:w-auto"
                        type="button"
                        @click="resetFormCriteres"
                    >
                        <ArrowPathIcon
                            class="h-6 w-6 text-gray-500 transition duration-200 hover:-rotate-90 hover:text-gray-700 md:h-8 md:w-8"
                        />
                    </button>
                </div>
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
                                    class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-2"
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
                                                'disciplines.categories.activites.show',
                                                {
                                                    discipline: discipline.slug,
                                                    category: category.slug,
                                                    activite:
                                                        produit.activite.id,
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
                                        carte, ou avec ces critères
                                        <span class="text-base italic"
                                            >(Utilisez la carte ou les liens de
                                            pagination ci dessous pour trouver
                                            des activités dans votre
                                            région).</span
                                        >
                                    </p>
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
                                                'disciplines.categories.structures.show',
                                                {
                                                    discipline: discipline.slug,
                                                    category: category.slug,
                                                    structure: structure.slug,
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
                        <h2
                            class="my-4 text-center text-lg font-semibold text-gray-600 md:my-8 md:text-2xl"
                        >
                            Les derniers articles
                        </h2>
                        <!-- les disciplines similaires -->
                        <DisciplinesSimilaires
                            v-if="disciplinesSimilaires.length > 0"
                            :disciplinesSimilaires="disciplinesSimilaires"
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
                                    Afficher la liste
                                </button>
                            </div>
                        </div>
                    </TransitionRoot>
                </div>
            </template>
            <template v-else>
                <div
                    class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-6 sm:px-6 md:flex-row md:space-x-4 md:py-12 lg:px-8"
                >
                    <p class="w-full font-medium text-gray-700 md:w-2/3">
                        Il n'y a pas encore d'activités en
                        <span class="font-semibold">{{ discipline.name }}</span
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
