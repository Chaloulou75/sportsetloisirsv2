<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, reactive, computed } from "vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import ActiviteCard from "@/Components/Structures/ActiviteCard.vue";
import LeafletMap from "@/Components/LeafletMap.vue";
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
    CheckCircleIcon,
    ChevronUpDownIcon,
    ArrowUturnLeftIcon,
    UsersIcon,
    UserGroupIcon,
    ClockIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";
import { StarIcon } from "@heroicons/vue/24/solid";
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    TabGroup,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
} from "@headlessui/vue";
dayjs.locale("fr");

const props = defineProps({
    structure: Object,
    logoUrl: String,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    activite: Object,
    criteres: Object,
    activiteSimilaires: Object,
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
    const filteredProducts = props.activite.produits.filter(
        (produit) =>
            produit.discipline_id === props.activite.discipline_id &&
            produit.categorie_id === props.activite.categorie_id
    );
    if (!selectedCriteres.value || filteredProducts.length === 0) {
        return filteredProducts;
    }
    return filteredProducts.filter((produit) => {
        let isMatch = true;

        for (const critereId in selectedCriteres.value) {
            const selectedCritereValue = selectedCriteres.value[critereId];

            const hasMatchingCritere = produit.criteres.some(
                (critere) =>
                    critere.critere_id === parseInt(critereId) &&
                    critere.valeur === selectedCritereValue.valeur
            );

            if (!hasMatchingCritere) {
                isMatch = false;
                break;
            }
        }
        return isMatch;
    });
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

function formatDate(dateString) {
    const date = dayjs(dateString);
    return date.format("dddd D MMMM YYYY à H[h]mm");
}

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

const displayPlanning = ref(false);

const getEvents = () => {
    const events = [];

    for (const produit of filteredProductsWithCriteres.value) {
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
        :title="activite.titre + ' - ' + structure.name"
        :description="
            'Fiche détaillée de ' + structure.name + '. Horaires et tarifs.'
        "
    />

    <ResultLayout :listDisciplines="listDisciplines" :allCities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
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
                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('structures.activites.show', {
                                            activite: activite.id,
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

        <section class="mx-auto my-4 max-w-full px-0 py-6 sm:px-4 lg:px-8">
            <div
                class="flex flex-col-reverse justify-between rounded-lg bg-white px-4 py-6 text-slate-600 shadow md:flex-row md:items-start md:space-x-6"
            >
                <div class="w-full space-y-12 md:w-1/3">
                    <Link
                        class="my-6 flex items-center justify-center rounded border border-indigo-600 bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-transparent hover:text-indigo-600 hover:shadow focus:outline-none focus:ring active:text-indigo-500"
                        preserve-scroll
                        :href="route('structures.show', structure.slug)"
                    >
                        <ArrowUturnLeftIcon class="mr-2 h-5 w-5" />
                        Retour vers l'accueil de la structure
                        {{ structure.name }}
                    </Link>
                    <div class="my-4 flex items-center justify-center md:mb-4">
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
                            Activité proposée par: {{ structure.name }}
                        </h3>
                        <Link
                            class="my-6 flex items-center justify-center rounded border border-indigo-600 bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-white hover:text-indigo-600 hover:shadow focus:outline-none focus:ring active:text-indigo-500"
                            preserve-scroll
                            :href="route('structures.show', structure.slug)"
                        >
                            Voir la fiche
                        </Link>
                        <p class="text-base font-semibold text-gray-700">
                            <UserIcon class="inline-block h-4 w-4" />
                            {{ structure.creator.name }}
                        </p>
                        <p
                            v-if="structure.website"
                            class="text-base font-medium text-gray-700"
                        >
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
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
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
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
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
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
                            <GlobeAltIcon class="mr-1.5 inline-block h-4 w-4" />
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
                <div class="w-full md:w-2/3 md:pr-10">
                    <div class="relative space-y-12">
                        <!-- titre -->
                        <div
                            class="my-4 flex items-center justify-start space-x-4"
                        >
                            <div v-if="structure.logo">
                                <img
                                    alt="img"
                                    :src="structure.logo"
                                    class="h-14 w-14 shrink-0 rounded-full object-cover object-center md:h-20 md:w-20"
                                />
                            </div>
                            <h2
                                class="inline-block w-full text-center text-xl font-semibold sm:text-2xl sm:leading-7 md:text-3xl"
                            >
                                {{ activite.titre }}
                            </h2>
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
                                v-else-if="structure.presentation_longue"
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
                        <!-- Filters -->
                        <div>
                            <div
                                class="my-6 grid w-full grid-cols-1 gap-4 md:grid-cols-3"
                            >
                                <Listbox
                                    v-for="critere in filteredCriteres"
                                    :key="critere.id"
                                    class="w-full"
                                    v-model="selectedCriteres[critere.id]"
                                >
                                    <div class="relative mt-1">
                                        <label
                                            :for="critere.nom"
                                            class="block text-sm font-medium"
                                        >
                                            {{ critere.nom }}:
                                        </label>
                                        <ListboxButton
                                            class="relative mt-1 w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                                        >
                                            <span class="block truncate">
                                                {{
                                                    selectedCriteres[critere.id]
                                                        .valeur
                                                }}
                                            </span>
                                            <span
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                            >
                                                <ChevronUpDownIcon
                                                    class="h-5 w-5 text-gray-400"
                                                    aria-hidden="true"
                                                />
                                            </span>
                                        </ListboxButton>

                                        <transition
                                            leave-active-class="transition duration-100 ease-in"
                                            leave-from-class="opacity-100"
                                            leave-to-class="opacity-0"
                                        >
                                            <ListboxOptions
                                                class="absolute z-40 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                            >
                                                <ListboxOption
                                                    v-slot="{
                                                        active,
                                                        selected,
                                                    }"
                                                    v-for="critereValue in critere.valeurs"
                                                    :key="critereValue.id"
                                                    :value="critereValue"
                                                    as="template"
                                                >
                                                    <li
                                                        :class="[
                                                            active
                                                                ? 'bg-amber-100 text-amber-900'
                                                                : 'text-gray-900',
                                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                                        ]"
                                                    >
                                                        <span
                                                            :class="[
                                                                selected
                                                                    ? 'font-medium'
                                                                    : 'font-normal',
                                                                'block truncate',
                                                            ]"
                                                            >{{
                                                                critereValue.valeur
                                                            }}</span
                                                        >
                                                        <span
                                                            v-if="selected"
                                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                                        >
                                                            <CheckCircleIcon
                                                                class="h-5 w-5"
                                                                aria-hidden="true"
                                                            />
                                                        </span>
                                                    </li>
                                                </ListboxOption>
                                            </ListboxOptions>
                                        </transition>
                                    </div>
                                </Listbox>
                            </div>
                        </div>

                        <TabGroup
                            v-if="filteredProductsWithCriteres.length > 0"
                        >
                            <TabList
                                class="flex space-x-1 rounded-xl bg-indigo-600 p-1"
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
                                <TabPanel>
                                    <form @submit.prevent="submitReservation()">
                                        <div
                                            class="flex w-full flex-col space-y-3 divide-y divide-slate-200 text-slate-500"
                                        >
                                            <p class="font-semibold">
                                                Choisir un produit:
                                            </p>
                                            <div
                                                class="flex flex-col space-y-5 rounded border border-gray-200 px-2 py-3 odd:bg-white even:bg-slate-50"
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
                                                            :value="produit.id"
                                                            v-model="
                                                                reservationForm.produit
                                                            "
                                                            name="produit"
                                                            type="radio"
                                                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                                        />
                                                        <label
                                                            :for="
                                                                'produit_' +
                                                                produit.id
                                                            "
                                                            class="flex w-full items-center justify-between gap-x-3 text-sm font-medium leading-6"
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
                                                            class="mr-1 h-4 w-4 text-indigo-700"
                                                        />
                                                        <p
                                                            class="font-semibold"
                                                        >
                                                            {{
                                                                produit.adresse
                                                                    .city
                                                            }}
                                                            ({{
                                                                produit.adresse
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
                                                            critere.critere.nom
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
                                                            class="flex items-center gap-x-3 border-b border-gray-200 px-6 py-4 last:border-none hover:bg-gray-200"
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
                                                                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                                            />
                                                            <label
                                                                for="formule-produit"
                                                                class="flex w-full items-center justify-between gap-x-3 text-sm font-semibold leading-6"
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
                                                                            class="mr-1 h-5 w-5"
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
                                                                            class="mr-1 h-5 w-5 text-slate-500"
                                                                        />
                                                                        <UsersIcon
                                                                            v-else-if="
                                                                                [
                                                                                    4,
                                                                                ].includes(
                                                                                    info.attribut_id
                                                                                )
                                                                            "
                                                                            class="mr-1 h-5 w-5"
                                                                        />

                                                                        <UsersIcon
                                                                            v-else
                                                                            class="mr-1 h-5 w-5"
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
                                                                        Pas de
                                                                        valeur
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    v-if="
                                                                        tarif.description
                                                                    "
                                                                >
                                                                    <p
                                                                        class="line-clamp-1 w-36 overflow-hidden text-ellipsis"
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
                                                        class="my-4 inline-block rounded border border-indigo-600 bg-indigo-600 px-12 py-3 text-sm font-medium text-white hover:bg-transparent hover:text-indigo-600 focus:outline-none focus:ring active:text-indigo-500"
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
                                                    class="my-6 gap-y-6 bg-white"
                                                >
                                                    <vue-cal
                                                        class="vuecal--rounded-theme"
                                                        hide-view-selector
                                                        :time="false"
                                                        active-view="month"
                                                        locale="fr"
                                                        :events="getEvents()"
                                                        xsmall
                                                    >
                                                        <template #arrow-prev>
                                                            <ChevronLeftIcon
                                                                class="h-4 w-4"
                                                            />
                                                        </template>
                                                        <template #arrow-next>
                                                            <ChevronRightIcon
                                                                class="h-4 w-4"
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
                                                        :cell-click-hold="false"
                                                        :events="getEvents()"
                                                    />
                                                    <fieldset>
                                                        <legend
                                                            class="text-sm font-semibold leading-6"
                                                        >
                                                            Choisir une date et
                                                            un creneau horaire:
                                                        </legend>

                                                        <div
                                                            class="mt-1 space-y-6"
                                                        >
                                                            <div
                                                                v-for="planning in produit.plannings"
                                                                :key="
                                                                    planning.id
                                                                "
                                                                class="flex items-center gap-x-3 border-b border-gray-200 px-3 py-4 last:border-none hover:bg-slate-100"
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
                                                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                                                />
                                                                <label
                                                                    for="creneaux"
                                                                    class="flex w-full items-center justify-between gap-x-3 text-sm font-semibold leading-6"
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
                                                    class="my-4 inline-block self-end rounded border border-indigo-600 bg-indigo-600 px-12 py-3 text-sm font-medium text-white hover:bg-transparent hover:text-indigo-600 focus:outline-none focus:ring active:text-indigo-500"
                                                    type="submit"
                                                    :disabled="
                                                        reservationForm.processing
                                                    "
                                                >
                                                    Envoyer une demande
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </TabPanel>
                                <!-- Planning -->
                                <TabPanel>
                                    <div
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

                        <div v-else>
                            <p class="font-medium text-slate-500">
                                Il n'y a pas encore d'activité pour cette
                                requète.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-white">
            <div class="mx-auto max-w-full px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
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
        <section class="bg-white">
            <div class="mx-auto max-w-full px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
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
                                activite: activite.id,
                            })
                        "
                    />
                </div>
            </div>
        </section>
    </ResultLayout>
</template>

<style>
.course {
    @apply bg-blue-400 text-white;
}
</style>
