<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, reactive, computed } from "vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import ActiviteCard from "@/Components/Structures/ActiviteCard.vue";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import CheckboxForm from "@/Components/Forms/CheckboxForm.vue";
import RadioForm from "@/Components/Forms/RadioForm.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import LeafletMap from "@/Components/Maps/LeafletMap.vue";
import VueCal from "vue-cal";
import "vue-cal/dist/vuecal.css";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import {
    MapPinIcon,
    UserIcon,
    AtSymbolIcon,
    GlobeAltIcon,
    PhoneIcon,
    HomeIcon,
    ArrowUturnLeftIcon,
    UsersIcon,
    UserGroupIcon,
    ClockIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";
import { StarIcon } from "@heroicons/vue/24/solid";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
dayjs.locale("fr");

const props = defineProps({
    departement: Object,
    city: Object,
    citiesAround: Object,
    allCities: Object,
    familles: Object,
    listDisciplines: Object,
    discipline: Object,
    activite: Object,
    criteres: Object,
    activiteSimilaires: Object,
    selectedProduit: Object,
    requestCategory: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    structuretypeElected: Object,
    produits: Object,
});

const filteredCriteres = computed(() => {
    return props.criteres.filter(
        (critere) =>
            critere.discipline_id === props.activite.discipline_id &&
            critere.categorie_id === props.activite.categorie_id
    );
});

const selectedCriteres = computed(() => {
    if (!filteredCriteres.value || filteredCriteres.value.length === 0) {
        return {};
    }
    const initialSelectedCriteres = {};
    for (const critere of filteredCriteres.value) {
        if (critere.valeurs.length > 0) {
            initialSelectedCriteres[critere.id] = critere.valeurs[0];
        } else {
            initialSelectedCriteres[critere.id] = "";
        }
    }
    return reactive(initialSelectedCriteres);
});

const filteredProductsWithCriteres = computed(() => {
    const filteredProducts = props.produits;
    // if (!selectedCriteres.value || filteredProducts.length === 0) {
    //     return filteredProducts;
    // }
    return filteredProducts;

    // return filteredProducts.filter((produit) => {
    //     let isMatch = true;

    //     for (const critereId in selectedCriteres.value) {
    //         const selectedCritereValue = selectedCriteres.value[critereId];
    //         const hasMatchingCritere = produit.criteres.some(
    //             (critere) =>
    //                 critere.critere_id === parseInt(critereId) &&
    //                 critere.critere_valeur.valeur ===
    //                     selectedCritereValue.valeur
    //         );

    //         if (!hasMatchingCritere) {
    //             isMatch = false;
    //             break;
    //         }
    //     }
    //     return isMatch;
    // });
});

const updateSelectedCheckboxes = (critereId, optionValue, checked) => {
    if (checked) {
        if (!selectedCriteres[critereId]) {
            // If the critereId doesn't exist in the form object, create a new array with the optionValue
            selectedCriteres[critereId] = [optionValue];
        } else {
            // If the critereId exists, push the optionValue to the existing array
            selectedCriteres[critereId].push(optionValue);
        }
    } else {
        // Remove the optionValue from the array
        const index = selectedCriteres[critereId].indexOf(optionValue);
        if (index !== -1) {
            selectedCriteres[critereId].splice(index, 1);
        }
    }
};

const isCheckboxSelected = computed(() => {
    return (critereId, optionValue) => {
        return (
            selectedCriteres[critereId] &&
            selectedCriteres[critereId].includes(optionValue)
        );
    };
});

const formatCurrency = (value) => {
    // Remove the non-numeric characters from the currency value
    const numericValue = Number(value.replace(/[^0-9.-]+/g, ""));
    // Check if the numeric value is a valid number
    if (!isNaN(numericValue)) {
        // Check if the numeric value has decimal places
        if (numericValue % 1 === 0) {
            // No decimal places, return as integer
            return numericValue.toLocaleString() + " €";
        } else {
            // Decimal places present, format with two decimal places
            return numericValue.toFixed(2) + " €";
        }
    }
    // Return the original value if conversion failed
    return value;
};

const formatDate = (dateString) => {
    const date = dayjs(dateString);
    return date.format("dddd D MMMM YYYY à H[h]mm");
};

const formatPhoneNumber = (phoneNumber) => {
    const cleanPhoneNumber = phoneNumber.replace(/\D/g, "");
    if (cleanPhoneNumber.startsWith("33")) {
        return `+33 ${cleanPhoneNumber.substr(3, 2)} ${cleanPhoneNumber.substr(
            5,
            2
        )} ${cleanPhoneNumber.substr(7, 2)} ${cleanPhoneNumber.substr(9, 2)}`;
    }
    return phoneNumber;
};

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

const displayPlanning = ref(false);

const getEvents = () => {
    const events = [];

    for (const produit of filteredProductsWithCriteres.value) {
        if (produit.plannings.length > 0) {
            for (const planning of produit.plannings) {
                if (planning) {
                    const event = {
                        start: planning.start,
                        end: planning.end,
                        title: planning.title ?? props.activite.titre,
                        content: props.activite.description,
                        activiteId: props.activite.id,
                        produitId: planning.produit_id,
                        planningId: planning.id,
                        class: "course",
                    };

                    events.push(event);
                }
            }
        }
    }

    return events;
};

const events = getEvents();

const reservationForm = useForm({
    produit: null,
    formule: null,
    planning: null,
    remember: false,
});

const submitReservation = () => {
    reservationForm.post("/product_reservations", {
        preserveScroll: true,
        onSuccess: () => {
            reservationForm.reset();
            displayPlanning.value = false;
        },
    });
};
</script>

<template>
    <Head
        :title="activite.titre + ' - ' + activite.structure.name"
        :description="
            'Fiche détaillée de ' + activite.titre + '. Horaires et tarifs.'
        "
    />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
        :departement="departement"
        :city="city"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> {{ activite.titre }} </template>
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

                            <li v-if="city" class="relative flex items-center">
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

                            <li
                                v-if="departement"
                                class="relative flex items-center"
                            >
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
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ departement.departement }}
                                </Link>
                            </li>

                            <li
                                v-if="discipline"
                                class="relative flex items-center"
                            >
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

                            <li
                                v-if="requestCategory"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('disciplines.categories.show', {
                                            discipline: discipline.slug,
                                            category: requestCategory.slug,
                                        })
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ requestCategory.nom_categorie_client }}
                                </Link>
                            </li>

                            <li
                                v-if="structuretypeElected"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route(
                                            'disciplines.structuretypes.show',
                                            {
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

                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('structures.activites.show', {
                                            activite: activite,
                                        })
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ activite.titre }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>
        <template #default>
            <div
                class="sticky left-0 right-0 top-16 z-[1199] bg-transparent backdrop-blur-md"
                ref="categoriesEl"
                v-if="categories && categories.length > 0"
            >
                <CategoriesResultNavigation
                    :city="city"
                    :departement="departement"
                    :discipline="discipline"
                    :all-structure-types="allStructureTypes"
                    :categories="categories"
                    :first-categories="firstCategories"
                    :categories-not-in-first="categoriesNotInFirst"
                    :category="requestCategory"
                    :structuretype-elected="structuretypeElected"
                />
            </div>

            <section class="mx-auto my-4 max-w-full px-0 py-6 sm:px-4 lg:px-8">
                <div
                    class="flex flex-col-reverse justify-between rounded-lg bg-white px-4 py-6 text-slate-600 shadow md:flex-row md:items-start md:space-x-6"
                >
                    <div class="w-full">
                        <div class="relative space-y-12">
                            <!-- titre -->
                            <div
                                class="my-4 flex items-center justify-start space-x-4"
                            >
                                <h1
                                    class="inline-block w-full text-center text-xl font-semibold sm:text-2xl sm:leading-7 md:text-3xl"
                                >
                                    Page à refaire totalement:
                                    {{ activite.titre }}
                                </h1>
                            </div>
                            <!-- Resume -->
                            <div>
                                <p
                                    v-if="activite.description"
                                    class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                                >
                                    {{ activite.description }}
                                </p>
                                <p
                                    v-else-if="
                                        activite.structure.presentation_longue
                                    "
                                    class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                                >
                                    {{ activite.structure.presentation_longue }}
                                </p>
                                <p
                                    v-else
                                    class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                                >
                                    {{ activite.structure.presentation_courte }}
                                </p>
                            </div>
                            <!-- instructeurs -->
                            <div v-if="activite.instructeurs.length > 0">
                                <p class="text-base font-medium text-gray-700">
                                    Vos instructeurs:
                                </p>
                                <ul>
                                    <li
                                        v-for="instructeur in activite.instructeurs"
                                        class="list-inside list-disc text-base font-semibold text-gray-600"
                                    >
                                        {{ instructeur.pivot.contact }} -
                                        {{ instructeur.pivot.email }}
                                    </li>
                                </ul>
                            </div>
                            <!-- Filters -->
                            <!-- <div v-if="filteredCriteres.length > 0">
                                <div
                                    class="grid w-full grid-cols-1 gap-4 my-6 md:grid-cols-3"
                                >
                                    <div
                                        v-for="critere in filteredCriteres"
                                        :key="critere.id"
                                        class="col-span-1"
                                    > -->
                            <!-- select -->
                            <!-- <SelectForm
                                            :classes="'block'"
                                            class="max-w-sm"
                                            v-if="
                                                critere.type_champ_form ===
                                                'select'
                                            "
                                            :name="critere.nom"
                                            v-model="
                                                selectedCriteres[critere.id]
                                            "
                                            :options="critere.valeurs"
                                        /> -->

                            <!-- checkbox -->
                            <!-- <CheckboxForm
                                            class="max-w-sm"
                                            v-if="
                                                critere.type_champ_form ===
                                                'checkbox'
                                            "
                                            :critere="critere"
                                            :name="critere.nom"
                                            v-model="
                                                selectedCriteres[critere.id]
                                            "
                                            :options="critere.valeurs"
                                            :is-checkbox-selected="
                                                isCheckboxSelected
                                            "
                                            @update-selected-checkboxes="
                                                updateSelectedCheckboxes
                                            "
                                        /> -->

                            <!-- radio -->
                            <!-- <RadioForm
                                            class="max-w-sm"
                                            v-if="
                                                critere.type_champ_form ===
                                                'radio'
                                            "
                                            :name="critere.nom"
                                            v-model="
                                                selectedCriteres[critere.id]
                                            "
                                            :options="critere.valeurs"
                                        /> -->

                            <!-- sous criteres -->
                            <!-- <div
                                            v-for="valeur in critere.valeurs"
                                            :key="valeur.id"
                                        >
                                            <div
                                                v-for="souscritere in valeur.sous_criteres"
                                                :key="souscritere.id"
                                                class=""
                                            >
                                                <SelectForm
                                                    :classes="'block'"
                                                    class="max-w-sm py-2"
                                                    v-if="
                                                        selectedCriteres[
                                                            critere.id
                                                        ] === valeur &&
                                                        souscritere.type_champ_form ===
                                                            'select' &&
                                                        souscritere.dis_cat_crit_val_id ===
                                                            valeur.id
                                                    "
                                                    :name="souscritere.nom"
                                                    v-model="
                                                        selectedCriteres[
                                                            souscritere.id
                                                        ]
                                                    "
                                                    :options="
                                                        souscritere.sous_criteres_valeurs
                                                    "
                                                />

                                                <InputLabel
                                                    class="py-2"
                                                    for="
                                                                Quantité
                                                            "
                                                    value="
                                                                Quantité
                                                            "
                                                    v-if="
                                                        selectedCriteres[
                                                            critere.id
                                                        ] === valeur &&
                                                        souscritere.type_champ_form ===
                                                            'number' &&
                                                        souscritere.dis_cat_crit_val_id ===
                                                            valeur.id
                                                    "
                                                />
                                                <TextInput
                                                    class="w-full"
                                                    type="number"
                                                    id="
                                                                Nombre
                                                            "
                                                    name="
                                                                Nombre
                                                            "
                                                    v-if="
                                                        selectedCriteres[
                                                            critere.id
                                                        ] === valeur &&
                                                        souscritere.type_champ_form ===
                                                            'number' &&
                                                        souscritere.dis_cat_crit_val_id ===
                                                            valeur.id
                                                    "
                                                    v-model="
                                                        selectedCriteres[
                                                            souscritere.id
                                                        ]
                                                    "
                                                />
                                            </div>
                                        </div> -->
                            <!-- </div>
                                </div>
                            </div> -->

                            <!-- <TabGroup
                                v-if="filteredProductsWithCriteres.length > 0"
                            >
                                <TabList
                                    class="flex p-1 space-x-1 bg-indigo-600 rounded-xl"
                                >
                                    <Tab as="template" v-slot="{ selected }">
                                        <button
                                            :class="[
                                                'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-100',
                                                'ring-white ring-opacity-60 ring-offset-1 ring-offset-blue-400 focus:outline-none focus:ring-2',
                                                selected
                                                    ? 'bg-white text-indigo-700 shadow'
                                                    : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
                                            ]"
                                        >
                                            Formules
                                        </button>
                                    </Tab>
                                    <Tab as="template" v-slot="{ selected }">
                                        <button
                                            :class="[
                                                'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-100',
                                                'ring-white ring-opacity-60 ring-offset-1 ring-offset-blue-400 focus:outline-none focus:ring-2',
                                                selected
                                                    ? 'bg-white text-indigo-700 shadow'
                                                    : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
                                            ]"
                                        >
                                            Planning
                                        </button>
                                    </Tab>
                                </TabList>
                                <TabPanels class="mt-2">
                                    <TabPanel
                                        v-if="
                                            filteredProductsWithCriteres.length >
                                            0
                                        "
                                    >
                                        <form
                                            @submit.prevent="
                                                submitReservation()
                                            "
                                        >
                                            <div
                                                class="flex flex-col w-full space-y-3 divide-y divide-slate-200 text-slate-500"
                                            >
                                                <p class="font-semibold">
                                                    Choisir un produit:
                                                </p>
                                                <div
                                                    class="flex flex-col px-2 py-3 space-y-5 border border-gray-200 rounded odd:bg-white even:bg-slate-50"
                                                    v-for="produit in filteredProductsWithCriteres"
                                                    :key="produit.id"
                                                >
                                                    <div
                                                        class="flex items-center justify-between px-2 py-1"
                                                    >
                                                        <div
                                                            class="flex items-center gap-x-3"
                                                        >
                                                            <input
                                                                :id="
                                                                    'produit_' +
                                                                    produit.id
                                                                "
                                                                :value="
                                                                    produit.id
                                                                "
                                                                v-model="
                                                                    reservationForm.produit
                                                                "
                                                                :checked="
                                                                    selectedProduit &&
                                                                    produit.id ===
                                                                        selectedProduit.id
                                                                "
                                                                name="produit"
                                                                type="radio"
                                                                class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-600"
                                                            />
                                                            <label
                                                                :for="
                                                                    'produit_' +
                                                                    produit.id
                                                                "
                                                                class="flex items-center justify-between w-full text-sm font-medium leading-6 gap-x-3"
                                                                >Produit n°
                                                                {{
                                                                    produit.id
                                                                }}</label
                                                            >
                                                        </div>

                                                        <div
                                                            class="flex items-center py-1.5 text-sm"
                                                        >
                                                            <dt class="sr-only">
                                                                Ville
                                                            </dt>
                                                            <MapPinIcon
                                                                class="w-4 h-4 mr-1 text-indigo-700"
                                                            />
                                                            <p
                                                                class="font-semibold"
                                                            >
                                                                {{
                                                                    produit
                                                                        .adresse
                                                                        .address
                                                                }},
                                                                {{
                                                                    produit
                                                                        .adresse
                                                                        .city
                                                                }}
                                                                ({{
                                                                    produit
                                                                        .adresse
                                                                        .zip_code
                                                                }})
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="flex items-center justify-between px-2"
                                                    >
                                                        <p
                                                            class="text-sm"
                                                            v-for="critere in produit.criteres"
                                                            :key="critere.id"
                                                        >
                                                            {{
                                                                critere.critere
                                                                    .nom
                                                            }}:
                                                            <span
                                                                class="font-semibold"
                                                                >{{
                                                                    critere.valeur
                                                                }}</span
                                                            >
                                                        </p>
                                                    </div>
                                                    <fieldset
                                                        v-if="
                                                            reservationForm.produit
                                                        "
                                                    >
                                                        <legend
                                                            class="px-6 text-sm font-semibold leading-6"
                                                        >
                                                            Choisir une formule:
                                                        </legend>

                                                        <div class="gap-y-6">
                                                            <div
                                                                v-for="tarif in produit.tarifs"
                                                                :key="tarif.id"
                                                                class="flex items-center px-6 py-4 border-b border-gray-200 gap-x-3 last:border-none hover:bg-gray-200"
                                                            >
                                                                <input
                                                                    :id="
                                                                        'formule-produit_' +
                                                                        tarif.id
                                                                    "
                                                                    :value="
                                                                        tarif.id
                                                                    "
                                                                    v-model="
                                                                        reservationForm.formule
                                                                    "
                                                                    type="radio"
                                                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-600"
                                                                />
                                                                <label
                                                                    for="formule-produit"
                                                                    class="flex items-center justify-between w-full text-sm font-semibold leading-6 gap-x-3"
                                                                >
                                                                    <div
                                                                        v-if="
                                                                            tarif
                                                                                .tarif_type
                                                                                .type
                                                                        "
                                                                    >
                                                                        {{
                                                                            tarif
                                                                                .tarif_type
                                                                                .type
                                                                        }}
                                                                    </div>
                                                                    <div
                                                                        v-if="
                                                                            tarif.titre
                                                                        "
                                                                    >
                                                                        {{
                                                                            tarif.titre
                                                                        }}
                                                                    </div>
                                                                    <div
                                                                        v-for="info in tarif.structure_tarif_type_infos"
                                                                        :key="
                                                                            info.id
                                                                        "
                                                                        class="flex items-start justify-center"
                                                                    >
                                                                        <div>
                                                                            <ClockIcon
                                                                                v-if="
                                                                                    [
                                                                                        1,
                                                                                        2,
                                                                                        5,
                                                                                        7,
                                                                                    ].includes(
                                                                                        info.attribut_id
                                                                                    )
                                                                                "
                                                                                class="w-5 h-5 mr-1"
                                                                            />
                                                                            <UserGroupIcon
                                                                                v-else-if="
                                                                                    [
                                                                                        3,
                                                                                        6,
                                                                                    ].includes(
                                                                                        info.attribut_id
                                                                                    )
                                                                                "
                                                                                class="w-5 h-5 mr-1 text-slate-500"
                                                                            />
                                                                            <UsersIcon
                                                                                v-else-if="
                                                                                    [
                                                                                        4,
                                                                                    ].includes(
                                                                                        info.attribut_id
                                                                                    )
                                                                                "
                                                                                class="w-5 h-5 mr-1"
                                                                            />

                                                                            <UsersIcon
                                                                                v-else
                                                                                class="w-5 h-5 mr-1"
                                                                            />
                                                                        </div>

                                                                        <div
                                                                            v-if="
                                                                                info.valeur
                                                                            "
                                                                            class=""
                                                                        >
                                                                            {{
                                                                                info
                                                                                    .tarif_type_attribut
                                                                                    .attribut
                                                                            }}:
                                                                            {{
                                                                                info.valeur
                                                                            }}
                                                                            <span
                                                                                v-if="
                                                                                    info.unite
                                                                                "
                                                                                >{{
                                                                                    info.unite
                                                                                }}</span
                                                                            >
                                                                        </div>
                                                                        <div
                                                                            v-else
                                                                            class="text-sm font-thin"
                                                                        >
                                                                            Pas
                                                                            de
                                                                            valeur
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        v-if="
                                                                            tarif.description
                                                                        "
                                                                    >
                                                                        <p
                                                                            class="overflow-hidden line-clamp-1 w-36 text-ellipsis"
                                                                        >
                                                                            {{
                                                                                tarif.description
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                    <div
                                                                        v-if="
                                                                            tarif.amount
                                                                        "
                                                                    >
                                                                        {{
                                                                            formatCurrency(
                                                                                tarif.amount
                                                                            )
                                                                        }}
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <div
                                                        v-if="
                                                            reservationForm.formule
                                                        "
                                                        class="self-end"
                                                    >
                                                        <button
                                                            class="inline-block px-12 py-3 my-4 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 rounded hover:bg-transparent hover:text-indigo-600 focus:outline-none focus:ring active:text-indigo-500"
                                                            type="button"
                                                            @click="
                                                                displayPlanning = true
                                                            "
                                                        >
                                                            Choisir une date
                                                        </button>
                                                    </div>

                                                    <template
                                                        v-if="displayPlanning"
                                                        class="my-6 bg-white gap-y-6"
                                                    >
                                                        <vue-cal
                                                            class="vuecal--rounded-theme"
                                                            hide-view-selector
                                                            :time="false"
                                                            active-view="month"
                                                            locale="fr"
                                                            :events="
                                                                getEvents()
                                                            "
                                                            xsmall
                                                        >
                                                            <template
                                                                #arrow-prev
                                                            >
                                                                <ChevronLeftIcon
                                                                    class="w-4 h-4"
                                                                />
                                                            </template>
                                                            <template
                                                                #arrow-next
                                                            >
                                                                <ChevronRightIcon
                                                                    class="w-4 h-4"
                                                                />
                                                            </template>
                                                        </vue-cal>
                                                        <vue-cal
                                                            xsmall
                                                            :time-from="6 * 60"
                                                            :time-to="24 * 60"
                                                            :time-step="60"
                                                            locale="fr"
                                                            :snap-to-time="15"
                                                            :disable-views="[
                                                                'years',
                                                                'year',
                                                            ]"
                                                            :cell-click-hold="
                                                                false
                                                            "
                                                            :events="
                                                                getEvents()
                                                            "
                                                        />
                                                        <fieldset>
                                                            <legend
                                                                class="text-sm font-semibold leading-6"
                                                            >
                                                                Choisir une date
                                                                et un creneau
                                                                horaire:
                                                            </legend>

                                                            <div
                                                                class="mt-1 space-y-6"
                                                            >
                                                                <div
                                                                    v-for="planning in produit.plannings"
                                                                    :key="
                                                                        planning.id
                                                                    "
                                                                    class="flex items-center px-3 py-4 border-b border-gray-200 gap-x-3 last:border-none hover:bg-slate-100"
                                                                >
                                                                    <input
                                                                        :id="
                                                                            'creneaux_' +
                                                                            planning.id
                                                                        "
                                                                        :value="
                                                                            planning.id
                                                                        "
                                                                        v-model="
                                                                            reservationForm.planning
                                                                        "
                                                                        type="radio"
                                                                        class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-600"
                                                                    />
                                                                    <label
                                                                        for="creneaux"
                                                                        class="flex items-center justify-between w-full text-sm font-semibold leading-6 gap-x-3"
                                                                    >
                                                                        {{
                                                                            planning.title
                                                                        }}<span
                                                                            class="font-normal"
                                                                        >
                                                                            du </span
                                                                        >{{
                                                                            formatDate(
                                                                                planning.start
                                                                            )
                                                                        }}<span
                                                                            class="font-normal"
                                                                        >
                                                                            au </span
                                                                        >{{
                                                                            formatDate(
                                                                                planning.end
                                                                            )
                                                                        }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </template>
                                                    <button
                                                        v-if="
                                                            reservationForm.formule
                                                        "
                                                        class="self-end inline-block px-12 py-3 my-4 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 rounded hover:bg-transparent hover:text-indigo-600 focus:outline-none focus:ring active:text-indigo-500"
                                                        type="submit"
                                                        :disabled="
                                                            reservationForm.processing
                                                        "
                                                    >
                                                        <LoadingSVG
                                                            v-if="
                                                                reservationForm.processing
                                                            "
                                                        />
                                                        Envoyer une demande
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </TabPanel>
                                     Planning -->
                            <!-- <TabPanel>
                                        <div
                                            class="w-full min-h-full mt-6 overflow-x-auto rounded-sm shadow-lg"
                                        >
                                            <vue-cal
                                                small
                                                :time-from="6 * 60"
                                                :time-to="24 * 60"
                                                :time-step="30"
                                                locale="fr"
                                                :snap-to-time="15"
                                                :disable-views="[
                                                    'years',
                                                    'year',
                                                ]"
                                                :cell-click-hold="false"
                                                :events="getEvents()"
                                            />
                                        </div>
                                    </TabPanel>
                                </TabPanels>
                            </TabGroup> -->

                            <!-- <div v-else>
                                <p class="font-medium text-slate-500">
                                    Il n'y a pas encore d'activité pour cette
                                    requète.
                                </p>
                            </div> -->
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-white">
                <div
                    class="mx-auto max-w-full px-4 py-16 sm:px-6 sm:py-24 lg:px-8"
                >
                    <h2
                        class="text-center text-2xl font-semibold tracking-tight text-gray-700 sm:text-3xl"
                    >
                        Les derniers avis sur cette activité
                    </h2>

                    <div
                        class="mt-12 grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-8"
                    >
                        <blockquote class="rounded-lg bg-gray-100 p-8">
                            <div class="flex items-center gap-4">
                                <img
                                    alt="Man"
                                    src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
                                    class="h-16 w-16 rounded-full object-cover"
                                />

                                <div>
                                    <div
                                        class="flex justify-center gap-0.5 text-yellow-500"
                                    >
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                    </div>

                                    <p
                                        class="mt-1 text-lg font-medium text-gray-700"
                                    >
                                        Paul Jacquemimou
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-4 line-clamp-2 text-gray-500 sm:line-clamp-none"
                            >
                                Très mauvaise expérience! A fuir!
                            </p>
                        </blockquote>

                        <blockquote class="rounded-lg bg-gray-100 p-8">
                            <div class="flex items-center gap-4">
                                <img
                                    alt="Man"
                                    src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
                                    class="h-16 w-16 rounded-full object-cover"
                                />

                                <div>
                                    <div
                                        class="flex justify-center gap-0.5 text-yellow-500"
                                    >
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                    </div>

                                    <p
                                        class="mt-1 text-lg font-medium text-gray-700"
                                    >
                                        Jacques Miteux
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-4 line-clamp-2 text-gray-500 sm:line-clamp-none"
                            >
                                C'était à chier, mais je mets 5 étoiles pour le
                                sourire de Roberta.
                            </p>
                        </blockquote>

                        <blockquote class="rounded-lg bg-gray-100 p-8">
                            <div class="flex items-center gap-4">
                                <img
                                    alt="Man"
                                    src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
                                    class="h-16 w-16 rounded-full object-cover"
                                />

                                <div>
                                    <div
                                        class="flex justify-center gap-0.5 text-yellow-500"
                                    >
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                    </div>

                                    <p
                                        class="mt-1 text-lg font-medium text-gray-700"
                                    >
                                        Antoine Boss
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-4 line-clamp-2 text-gray-500 sm:line-clamp-none"
                            >
                                C'était vraiment sensationnel.
                            </p>
                        </blockquote>
                    </div>
                </div>
            </section>
            <section v-if="activiteSimilaires.length > 0" class="bg-white">
                <div
                    class="mx-auto max-w-full px-4 py-16 sm:px-6 sm:py-24 lg:px-8"
                >
                    <h2
                        class="text-center text-2xl font-semibold tracking-tight text-gray-700 sm:text-3xl"
                    >
                        Les activités similaires
                    </h2>
                    <div
                        class="mt-12 grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-8"
                    >
                        <ActiviteCard
                            v-for="activite in activiteSimilaires"
                            :key="activite.id"
                            :activite="activite"
                            :link="
                                route('structures.activites.show', {
                                    activite: activite,
                                })
                            "
                        />
                    </div>
                </div>
            </section>
        </template>
    </ResultLayout>
</template>

<style>
.course {
    @apply bg-blue-400 text-white;
}
</style>
