<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import CheckboxForm from "@/Components/Forms/CheckboxForm.vue";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import RadioForm from "@/Components/Forms/RadioForm.vue";
import RangeInputForm from "@/Components/Forms/RangeInputForm.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import OpenDaysForm from "@/Components/Forms/DayTime/OpenDaysForm.vue";
import SingleDateForm from "@/Components/Forms/DayTime/SingleDateForm.vue";
import SingleTimeForm from "@/Components/Forms/DayTime/SingleTimeForm.vue";
import OpenTimesForm from "@/Components/Forms/DayTime/OpenTimesForm.vue";
import OpenMonthsForm from "@/Components/Forms/DayTime/OpenMonthsForm.vue";
import LeafletMap from "@/Components/Maps/LeafletMap.vue";
import VueCal from "vue-cal";
import "vue-cal/dist/vuecal.css";
import {
    UserIcon,
    AtSymbolIcon,
    GlobeAltIcon,
    PhoneIcon,
    HomeIcon,
} from "@heroicons/vue/24/outline";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import ActiviteCard from "@/Components/Structures/ActiviteCard.vue";

const props = defineProps({
    structure: Object,
    familles: Object,
    listDisciplines: Object,
    requestDiscipline: Object,
    allCities: Object,
    city: Object,
    citiesAround: Object,
    requestCategory: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    structuretypeElected: Object,
    criteres: Object,
    departement: Object,
    can: Object,
});

const uniqueDisciplines = computed(() => {
    const disciplinesMap = new Map();
    props.structure.activites.forEach((activity) => {
        const discipline = activity.discipline;
        if (!disciplinesMap.has(discipline.id)) {
            disciplinesMap.set(discipline.id, discipline);
        }
    });
    return Array.from(disciplinesMap.values());
});

const selectedDiscipline = ref(
    props.requestDiscipline ? props.requestDiscipline : null
);
const selectedCategory = ref(
    props.requestCategory ? props.requestCategory : null
);

const formCriteres = ref({
    criteres: {},
    sousCriteres: {},
});

const formOtherCriteres = useForm({
    date_seule: null,
    dates: null,
    time_seule: null,
    times: null,
    months: null,
    rayon_km: 0,
});

const selectedCriteres = ref([]);
const selectedSousCriteres = ref([]);
const filteredActivitesWithCriteres = ref(props.structure.activites);

const handleDisciplineClick = (discipline) => {
    selectedDiscipline.value = discipline;
    selectedCategory.value = null;
};

const filteredCategories = computed(() => {
    if (!selectedDiscipline) return [];

    return props.structure.activites
        .filter(
            (activity) => activity.discipline_id === selectedDiscipline.value.id
        )
        .map((activity) => activity.categorie);
});

const handleCategoryClick = (category) => {
    selectedCategory.value = category;
    filterActivities();
};

const filteredCriteres = computed(() => {
    if (!selectedDiscipline.value || !selectedCategory.value) return [];

    return props.criteres.filter(
        (critere) =>
            critere.discipline_id === selectedDiscipline.value.id &&
            critere.categorie_id === selectedCategory.value.id
    );
});

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

const filterActivities = () => {
    if (!selectedDiscipline.value || !selectedCategory.value) return [];
    if (
        selectedDiscipline.value &&
        selectedCategory.value &&
        selectedCriteres.value.length === 0
    ) {
        filteredActivitesWithCriteres.value = props.structure.activites.filter(
            (activite) =>
                activite.discipline_id === selectedDiscipline.value.id &&
                activite.categorie_id === selectedCategory.value.id
        );
    } else {
        filteredActivitesWithCriteres.value = props.structure.activites
            .filter(
                (activite) =>
                    activite.discipline_id === selectedDiscipline.value.id &&
                    activite.categorie_id === selectedCategory.value.id
            )
            .filter((activite) => {
                return activite.produits.some((produit) => {
                    return (
                        selectedCriteres.value.every((selectedCritere) => {
                            if (!!selectedCritere.inclus_all === true) {
                                return true;
                            }
                            if (Array.isArray(selectedCritere)) {
                                return selectedCritere.some(
                                    (critereInArray) => {
                                        return produit.criteres.some(
                                            (produitCritere) =>
                                                produitCritere.valeur_id ===
                                                critereInArray.id
                                        );
                                    }
                                );
                            } else {
                                return produit.criteres.some(
                                    (produitCritere) => {
                                        return (
                                            produitCritere.valeur_id ===
                                                selectedCritere.id ||
                                            !!produitCritere.critere_valeur
                                                .inclus_all === true
                                        );
                                    }
                                );
                            }
                        }) &&
                        selectedSousCriteres.value.every(
                            (selectedSousCritere) => {
                                return produit.criteres.some(
                                    (produitCritere) => {
                                        return (
                                            produitCritere.sous_criteres &&
                                            produitCritere.sous_criteres.some(
                                                (sousCritere) =>
                                                    sousCritere.sous_critere_valeur_id ===
                                                    selectedSousCritere.id
                                            )
                                        );
                                    }
                                );
                            }
                        )
                    );
                });
            });
    }
};

watch(
    () => [selectedDiscipline.value, selectedCategory.value],
    ([newDiscipline, newCategory]) => {
        if (newDiscipline || newCategory) {
            filterActivities();
        }
    },
    { deep: true }
);

watch(
    () => formCriteres.value.criteres,
    (newCriteres) => {
        selectedCriteres.value = Object.values(newCriteres).filter(Boolean);
        filterActivities();
    },
    { deep: true }
);

watch(
    () => formCriteres.value.sousCriteres,
    (newSousCriteres) => {
        selectedSousCriteres.value =
            Object.values(newSousCriteres).filter(Boolean);
        filterActivities();
    },
    { deep: true }
);

function formatPhoneNumber(phoneNumber) {
    const cleanPhoneNumber = phoneNumber.replace(/\D/g, "");
    if (cleanPhoneNumber.startsWith("33")) {
        return `+33 ${cleanPhoneNumber.substr(3, 2)} ${cleanPhoneNumber.substr(
            5,
            2
        )} ${cleanPhoneNumber.substr(7, 2)} ${cleanPhoneNumber.substr(9, 2)}`;
    }
    return phoneNumber;
}

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

const getEvents = () => {
    const events = [];

    for (const activite of filteredActivitesWithCriteres.value) {
        for (const produit of activite.produits) {
            for (const planning of produit.plannings) {
                if (planning) {
                    const event = {
                        start: planning.start,
                        end: planning.end,
                        title: planning.title ?? activite.titre,
                        content: activite.description,
                        activiteId: activite.id,
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
</script>

<template>
    <Head
        :title="
            structure.name +
            ' - ' +
            structure.adresses[0].city +
            ' - ' +
            structure.structuretype.name
        "
        :description="
            'Fiche détaillée de ' + structure.name + '. Horaires et tarifs.'
        "
    />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
        :discipline="requestDiscipline"
        :city="city"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title>
                    {{ structure.name }}
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
                                v-if="requestDiscipline"
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
                                            requestDiscipline.slug
                                        )
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ requestDiscipline.name }}
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
                                            discipline: requestDiscipline.slug,
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
                                                discipline:
                                                    requestDiscipline.slug,
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
                                        route('structures.show', structure.slug)
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ structure.name }}
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
                    :discipline="requestDiscipline"
                    :all-structure-types="allStructureTypes"
                    :categories="categories"
                    :first-categories="firstCategories"
                    :categories-not-in-first="categoriesNotInFirst"
                    :category="requestCategory"
                    :structuretype-elected="structuretypeElected"
                />
            </div>
            <section
                class="mx-auto max-w-full px-0 py-2 sm:px-4 md:my-4 md:py-6 lg:px-8"
            >
                <div
                    class="flex flex-col-reverse justify-between rounded-lg bg-white px-4 shadow md:flex-row md:space-x-6 md:py-6"
                >
                    <div class="w-full space-y-12 md:w-1/3">
                        <div
                            class="my-4 flex items-center justify-center md:mb-8"
                        >
                            <h3 class="text-base font-semibold uppercase">
                                Coordonnées de la structure
                            </h3>
                        </div>
                        <div class="flex flex-col space-y-3">
                            <h3 class="text-base">
                                Localisation:
                                <span class="font-semibold">
                                    {{ structure.adresses[0].city }},
                                    {{ structure.adresses[0].zip_code }}
                                </span>
                            </h3>
                            <LeafletMap :item="structure" />
                        </div>
                        <div
                            class="flex w-full flex-col space-y-3 rounded-md bg-indigo-100 px-2 py-4 text-slate-800"
                        >
                            <h3 class="text-center text-base font-semibold">
                                Contact
                            </h3>

                            <p class="text-base font-semibold text-gray-700">
                                <UserIcon class="inline-block h-4 w-4" />
                                {{ structure.creator.name }}
                            </p>
                            <p
                                v-if="structure.website"
                                class="text-base font-medium text-gray-700"
                            >
                                <GlobeAltIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />
                                Site web:
                                <a
                                    :href="structure.website"
                                    target="_blank"
                                    class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                                >
                                    {{ structure.website }}
                                </a>
                            </p>
                            <p
                                v-if="structure.facebook"
                                class="text-base font-medium text-gray-700"
                            >
                                <GlobeAltIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />
                                Facebook:
                                <a
                                    :href="structure.facebook"
                                    target="_blank"
                                    class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                                >
                                    {{ structure.facebook }}
                                </a>
                            </p>
                            <p
                                v-if="structure.instagram"
                                class="text-base font-medium text-gray-700"
                            >
                                <GlobeAltIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />
                                Instagram:
                                <a
                                    :href="structure.instagram"
                                    target="_blank"
                                    class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                                >
                                    {{ structure.instagram }}
                                </a>
                            </p>
                            <p
                                v-if="structure.youtube"
                                class="text-base font-medium text-gray-700"
                            >
                                <GlobeAltIcon
                                    class="mr-1.5 inline-block h-4 w-4"
                                />
                                Youtube:
                                <a
                                    :href="structure.youtube"
                                    target="_blank"
                                    class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                                >
                                    {{ structure.youtube }}
                                </a>
                            </p>

                            <p
                                v-if="structure.phone1"
                                class="text-base font-medium text-gray-700"
                            >
                                <PhoneIcon class="inline-block h-4 w-4" />
                                <a
                                    :href="`tel:${structure.phone1}`"
                                    target="_blank"
                                    class="text-base font-medium text-blue-700 hover:text-blue-800 hover:underline"
                                >
                                    {{ formatPhoneNumber(structure.phone1) }}
                                </a>
                            </p>
                            <p
                                v-if="structure.email"
                                class="text-base font-medium text-gray-700"
                            >
                                <AtSymbolIcon class="inline-block h-4 w-4" />
                                {{ structure.email }}
                            </p>
                        </div>
                    </div>
                    <div class="w-full space-y-12 md:w-2/3 md:pr-10">
                        <!-- titre -->
                        <div class="relative mb-4 md:mb-6">
                            <p
                                class="mb-2 text-right text-sm font-medium uppercase tracking-wider text-gray-500 md:text-base"
                            >
                                {{ structure.structuretype.name }}
                            </p>
                            <h2
                                class="text-center text-xl font-semibold text-black sm:text-2xl sm:leading-7 md:text-3xl"
                            >
                                {{ structure.name }}
                            </h2>

                            <div v-if="structure.logo">
                                <img
                                    alt="img"
                                    :src="structure.image_url"
                                    class="h-14 w-14 shrink-0 rounded-full object-cover object-center md:h-20 md:w-20"
                                />
                            </div>
                        </div>
                        <!-- Resume -->
                        <div>
                            <p
                                v-if="structure.presentation_longue"
                                class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                            >
                                {{ structure.presentation_longue }}
                            </p>
                            <p
                                v-else
                                class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                            >
                                {{ structure.presentation_courte }}
                            </p>
                        </div>
                        <!-- Disciplines -->
                        <div>
                            <h3
                                class="my-4 text-lg font-semibold text-gray-700"
                            >
                                Les disciplines proposées:
                            </h3>
                            <div
                                class="grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3 lg:grid-cols-4 lg:gap-6"
                            >
                                <button
                                    v-for="discipline in uniqueDisciplines"
                                    :key="discipline.id"
                                    @click="handleDisciplineClick(discipline)"
                                    :class="[
                                        'rounded border border-gray-100 px-6 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500',
                                        selectedDiscipline &&
                                        selectedDiscipline.id === discipline.id
                                            ? 'border-gray-100 bg-indigo-500 text-white'
                                            : '',
                                    ]"
                                >
                                    {{ discipline.name }}
                                </button>
                            </div>
                        </div>
                        <!-- Categories -->
                        <div>
                            <h3
                                v-if="selectedDiscipline"
                                class="my-4 text-lg font-semibold text-gray-700"
                            >
                                Les catégories proposées pour
                                <span class="text-indigo-500">{{
                                    selectedDiscipline.name
                                }}</span>
                                :
                            </h3>
                            <div
                                v-if="selectedDiscipline"
                                class="grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3 lg:grid-cols-4 lg:gap-6"
                            >
                                <button
                                    v-for="categorie in filteredCategories"
                                    :key="categorie.id"
                                    @click="handleCategoryClick(categorie)"
                                    :class="[
                                        'rounded border border-gray-100 px-6 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500',
                                        selectedCategory &&
                                        selectedCategory.id === categorie.id
                                            ? 'border-gray-100 bg-indigo-500 text-white'
                                            : '',
                                    ]"
                                >
                                    {{ categorie.nom_categorie_client }}
                                </button>
                            </div>
                        </div>
                        <!-- Filters -->
                        <div>
                            <h3
                                v-if="selectedCategory"
                                class="my-4 text-lg font-semibold text-gray-700"
                            >
                                Les activités proposées pour
                                <span class="text-indigo-500">{{
                                    selectedCategory.nom_categorie_client
                                }}</span>
                                de
                                <span class="text-indigo-500">{{
                                    selectedDiscipline.name
                                }}</span
                                >:
                            </h3>

                            <div
                                v-if="
                                    selectedCategory &&
                                    filteredCriteres.length > 0
                                "
                                class="mx-auto my-6 grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3"
                            >
                                <div
                                    v-for="critere in filteredCriteres"
                                    :key="critere.id"
                                >
                                    <!-- select -->
                                    <SelectForm
                                        :classes="'block'"
                                        class="max-w-sm"
                                        v-if="
                                            critere.type_champ_form === 'select'
                                        "
                                        :name="critere.nom"
                                        v-model="
                                            formCriteres.criteres[critere.id]
                                        "
                                        :options="critere.valeurs"
                                    />

                                    <!-- checkbox -->
                                    <CheckboxForm
                                        :classes="'block'"
                                        class="max-w-sm"
                                        v-if="
                                            critere.type_champ_form ===
                                            'checkbox'
                                        "
                                        :critere="critere"
                                        :name="critere.nom"
                                        v-model="
                                            formCriteres.criteres[critere.id]
                                        "
                                        :options="critere.valeurs"
                                        :is-checkbox-selected="
                                            isCheckboxSelected
                                        "
                                        @update-selected-checkboxes="
                                            updateSelectedCheckboxes
                                        "
                                    />
                                    <!-- radio -->
                                    <RadioForm
                                        class="max-w-sm"
                                        v-if="
                                            critere.type_champ_form === 'radio'
                                        "
                                        :name="critere.nom"
                                        v-model="
                                            formCriteres.criteres[critere.id]
                                        "
                                        :options="critere.valeurs"
                                    />

                                    <!-- input text -->
                                    <div
                                        class="max-w-sm"
                                        v-if="
                                            critere.type_champ_form === 'text'
                                        "
                                    >
                                        <label
                                            :for="critere.nom"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ critere.nom }}
                                        </label>
                                        <div class="mt-1 flex rounded-md">
                                            <TextInput
                                                type="text"
                                                v-model="
                                                    formCriteres.criteres[
                                                        critere.id
                                                    ]
                                                "
                                                :name="critere.nom"
                                                :id="critere.nom"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder=""
                                                autocomplete="none"
                                            />
                                        </div>
                                    </div>

                                    <!-- number text -->
                                    <div
                                        class="max-w-sm"
                                        v-if="
                                            critere.type_champ_form === 'number'
                                        "
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
                                                v-model="
                                                    formCriteres.criteres[
                                                        critere.id
                                                    ]
                                                "
                                                :name="critere.nom"
                                                :id="critere.nom"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder=""
                                                autocomplete="none"
                                            />
                                        </div>
                                    </div>

                                    <!-- Heure seule -->
                                    <div
                                        v-if="
                                            critere.type_champ_form === 'time'
                                        "
                                        class="flex max-w-sm flex-col items-start space-y-3"
                                    >
                                        <SingleTimeForm
                                            class="w-full"
                                            v-model="
                                                formOtherCriteres.time_seule
                                            "
                                            :name="critere.nom"
                                        />
                                    </div>

                                    <!-- Heures x2 ouverture / fermeture -->
                                    <div
                                        v-if="
                                            critere.type_champ_form === 'times'
                                        "
                                        class="flex max-w-sm flex-col items-start space-y-3"
                                    >
                                        <OpenTimesForm
                                            class="w-full"
                                            v-model="formOtherCriteres.times"
                                            :name="critere.nom"
                                        />
                                    </div>

                                    <!-- Date seule -->
                                    <div
                                        v-if="
                                            critere.type_champ_form === 'date'
                                        "
                                        class="flex max-w-sm flex-col items-start space-y-3"
                                    >
                                        <SingleDateForm
                                            class="w-full"
                                            v-model="
                                                formOtherCriteres.date_seule
                                            "
                                            :name="critere.nom"
                                        />
                                    </div>

                                    <!-- Dates x 2 -->
                                    <div
                                        v-if="
                                            critere.type_champ_form === 'dates'
                                        "
                                        class="flex max-w-sm flex-col items-start space-y-3"
                                    >
                                        <OpenDaysForm
                                            class="w-full"
                                            v-model="formOtherCriteres.dates"
                                            :name="critere.nom"
                                        />
                                    </div>

                                    <!-- Mois -->
                                    <div
                                        v-if="
                                            critere.type_champ_form === 'mois'
                                        "
                                    >
                                        <div
                                            class="flex max-w-sm flex-col items-start space-y-3"
                                        >
                                            <OpenMonthsForm
                                                class="w-full"
                                                v-model="
                                                    formOtherCriteres.months
                                                "
                                                :name="critere.nom"
                                            />
                                            <div
                                                v-if="
                                                    formOtherCriteres.errors
                                                        .months
                                                "
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{
                                                    formOtherCriteres.errors
                                                        .months
                                                }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Adresse -->
                                    <!-- <div
                                        v-if="
                                            critere.type_champ_form ===
                                            'adresse'
                                        "
                                        class="flex w-full max-w-sm flex-col space-y-2"
                                    >
                                        <div v-if="!addAddress" class="flex-1">
                                            <label
                                                for="adresse"
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
                                                    v-model="
                                                        formCriteres.adresse
                                                    "
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
                                    <RangeInputForm
                                        v-if="
                                            critere.type_champ_form === 'rayon'
                                        "
                                        class="max-w-sm"
                                        v-model="formOtherCriteres.rayon_km"
                                        :min="0"
                                        :max="200"
                                        :name="critere.nom"
                                        :metric="`Km`"
                                    />

                                    <!-- sous criteres -->
                                    <div
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
                                                    formCriteres.criteres[
                                                        critere.id
                                                    ] === valeur &&
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
                                                :options="
                                                    souscritere.sous_criteres_valeurs
                                                "
                                            />

                                            <div
                                                v-if="
                                                    formCriteres.criteres[
                                                        critere.id
                                                    ] === valeur &&
                                                    souscritere.type_champ_form ===
                                                        'number' &&
                                                    souscritere.dis_cat_crit_val_id ===
                                                        valeur.id
                                                "
                                                class="mt-2 block"
                                            >
                                                <InputLabel
                                                    class="py-2"
                                                    :for="souscritere.nom"
                                                    :value="souscritere.nom"
                                                />
                                                <TextInput
                                                    class="w-full"
                                                    type="number"
                                                    :id="souscritere.nom"
                                                    :name="souscritere.nom"
                                                    v-model="
                                                        formCriteres
                                                            .sousCriteres[
                                                            souscritere.id
                                                        ]
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <TabGroup v-if="selectedCategory">
                            <TabList
                                class="flex space-x-1 rounded-xl bg-sky-700 p-1"
                            >
                                <Tab as="template" v-slot="{ selected }">
                                    <button
                                        :class="[
                                            'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-100',
                                            'ring-white ring-opacity-60 ring-offset-1 ring-offset-blue-400 focus:outline-none focus:ring-2',
                                            selected
                                                ? 'bg-white text-sky-700 shadow'
                                                : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
                                        ]"
                                    >
                                        Activités
                                    </button>
                                </Tab>
                                <Tab as="template" v-slot="{ selected }">
                                    <button
                                        :class="[
                                            'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-100',
                                            'ring-white ring-opacity-60 ring-offset-1 ring-offset-blue-400 focus:outline-none focus:ring-2',
                                            selected
                                                ? 'bg-white text-sky-700 shadow'
                                                : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
                                        ]"
                                    >
                                        Planning
                                    </button>
                                </Tab>
                            </TabList>
                            <TabPanels class="mt-2">
                                <!-- Activites Cards -->
                                <TabPanel>
                                    <div
                                        v-if="selectedCategory"
                                        class="my-6 grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3"
                                    >
                                        <ActiviteCard
                                            v-for="activite in filteredActivitesWithCriteres"
                                            :key="activite.id"
                                            :activite="activite"
                                            :link="
                                                route(
                                                    'structures.activites.show',
                                                    {
                                                        activite: activite.id,
                                                    }
                                                )
                                            "
                                        /></div
                                ></TabPanel>
                                <!-- Planning -->
                                <TabPanel>
                                    <div
                                        v-if="selectedCategory"
                                        class="mt-6 min-h-full w-full overflow-x-auto rounded-sm shadow-lg"
                                    >
                                        <vue-cal
                                            small
                                            :time-from="6 * 60"
                                            :time-to="24 * 60"
                                            :time-step="30"
                                            locale="fr"
                                            :snap-to-time="15"
                                            :disable-views="['years', 'year']"
                                            :cell-click-hold="false"
                                            :events="getEvents()"
                                        />
                                    </div>
                                </TabPanel>
                            </TabPanels>
                        </TabGroup>
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
