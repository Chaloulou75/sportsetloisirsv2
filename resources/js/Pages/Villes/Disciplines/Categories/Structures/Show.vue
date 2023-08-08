<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, reactive, computed } from "vue";
import FamilleNavigation from "@/Components/Familles/FamilleNavigation.vue";
import CitiesAround from "@/Components/Cities/CitiesAround.vue";
import DisciplinesSimilaires from "@/Components/Disciplines/DisciplinesSimilaires.vue";
import LeafletMap from "@/Components/LeafletMap.vue";
import VueCal from "vue-cal";
import "vue-cal/dist/vuecal.css";
import {
    UserIcon,
    AtSymbolIcon,
    GlobeAltIcon,
    PhoneIcon,
    CheckCircleIcon,
    ChevronUpDownIcon,
} from "@heroicons/vue/24/outline";
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    TabGroup,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
} from "@headlessui/vue";
import ActiviteCard from "@/Components/Structures/ActiviteCard.vue";

const props = defineProps({
    familles: Object,
    category: Object,
    categories: Object,
    allStructureTypes: Object,
    city: Object,
    citiesAround: Object,
    structure: Object,
    discipline: Object,
    criteres: Object,
    disciplinesSimilaires: Object,
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

const selectedDiscipline = ref(props.discipline);
const selectedCategory = ref(props.category);
// const selectedCriteres = ref({});

const handleDisciplineClick = (discipline) => {
    selectedDiscipline.value = discipline;
    selectedCategory.value = null;
};

const filteredCategories = computed(() => {
    if (!selectedDiscipline) return [];

    return props.structure.activites
        .filter(
            (activity) =>
                activity.categorie.discipline_id === selectedDiscipline.value.id
        )
        .map((activity) => activity.categorie);
});

const handleCategoryClick = (category) => {
    selectedCategory.value = category;
};

const filteredCriteres = computed(() => {
    if (!selectedDiscipline.value || !selectedCategory.value) return [];

    return props.criteres.filter(
        (critere) =>
            critere.discipline_id === selectedDiscipline.value.id &&
            critere.categorie_id === selectedCategory.value.id
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

const filteredActivitesWithCriteres = computed(() => {
    if (!selectedDiscipline.value || !selectedCategory.value) return [];

    const filteredActivities = props.structure.activites.filter(
        (activity) =>
            activity.categorie.discipline_id === selectedDiscipline.value.id &&
            activity.categorie_id === selectedCategory.value.id
    );

    if (!selectedCriteres.value || filteredActivities.length === 0) {
        return filteredActivities;
    }

    return filteredActivities.filter((activity) => {
        let isMatch = true;
        const products = activity.produits;

        for (const critereId in selectedCriteres.value) {
            const selectedCritereValue = selectedCriteres.value[critereId];

            const product = products.find((produit) => {
                return produit.criteres.some(
                    (critere) =>
                        critere.critere_id === parseInt(critereId) &&
                        critere.valeur === selectedCritereValue.valeur
                );
            });

            if (!product) {
                isMatch = false; // Activity does not match selected criteria
                break; // Exit the loop early
            }
        }
        return isMatch; // Activity matches all selected criteria
    });
});

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

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
        :title="`${category.nom_categorie_client} de ${discipline.name} à ${city.ville}. ${structure.name}`"
        :description="`${category.nom_categorie_client} de ${discipline.name} à ${city.ville}. ${structure.name} vous propose de pratiquer une activité sportive ou de loisirs à ${city.ville}`"
    />

    <AppLayout>
        <template #header>
            <FamilleNavigation :familles="familles" />
            <div
                class="my-4 flex w-full flex-col items-center justify-center space-y-2"
            >
                <h1
                    class="text-center text-xl font-semibold uppercase leading-tight tracking-widest text-gray-800"
                >
                    {{ category.nom_categorie_client }}
                    <span class="lowercase">de</span> {{ discipline.name }}
                    <span class="lowercase">à</span>
                    {{ formatCityName(city.ville) }}
                    <span class="text-sm text-gray-600"
                        >({{ city.code_postal }})
                    </span>
                </h1>
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
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>

                                <span class="ms-1.5 text-xs font-medium">
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
                        <li class="relative flex items-center">
                            <span
                                class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                            >
                            </span>

                            <Link
                                preserve-scroll
                                :href="
                                    route('villes.disciplines.show', {
                                        city: city.id,
                                        discipline: discipline.slug,
                                    })
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
                                    route(
                                        'villes.disciplines.categories.show',
                                        {
                                            city: city.id,
                                            discipline: discipline.slug,
                                            category: category.id,
                                        }
                                    )
                                "
                                class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                            >
                                {{ category.nom_categorie_client }}
                            </Link>
                        </li>
                    </ol>
                </nav>
            </div>

            <p class="py-2 text-base font-medium leading-tight text-gray-700">
                <span class="font-semibold text-gray-800"
                    >{{ category.nom_categorie_client }}
                </span>
                de
                <span class="font-semibold text-gray-800"
                    >{{ discipline.name }}
                </span>
                à
                <span class="font-semibold text-gray-800">{{
                    formatCityName(city.ville)
                }}</span>
                en France. <br />
                Consultez la liste des
                <span
                    v-if="city.structures_count > 1"
                    class="font-semibold text-gray-800"
                    >{{ city.structures_count }}
                </span>
                structures disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer du
                <span class="font-semibold text-gray-800">{{
                    discipline.name
                }}</span>
                à
                <span class="font-semibold text-gray-800">{{
                    formatCityName(city.ville)
                }}</span>
                n'a jamais été aussi simple!
            </p>
        </template>
        <div class="mx-auto max-w-full px-2 py-4 sm:px-3 lg:px-6">
            <div class="flex items-center justify-around space-x-4">
                <div class="my-4 w-full">
                    <div class="mt-1">
                        <nav
                            class="flex w-full flex-col items-stretch justify-between divide-y divide-green-600 rounded-sm border border-gray-300 bg-white/20 px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none sm:text-base md:flex-row md:items-center md:divide-y-0"
                        >
                            <Link
                                v-for="categorie in categories"
                                :key="categorie.id"
                                :href="
                                    route(
                                        'villes.disciplines.categories.show',
                                        {
                                            city: city.id,
                                            discipline: discipline.slug,
                                            category: categorie.id,
                                        }
                                    )
                                "
                                :class="[
                                    'w-full px-2 py-3 text-center text-sm font-medium leading-5 text-gray-700 ring-white ring-opacity-10 ring-offset-2 ring-offset-green-200 focus:outline-none focus:ring-2',
                                    route().current(
                                        'villes.disciplines.categories.structures.show'
                                    ) && category.id === categorie.id
                                        ? 'bg-green-600 text-white'
                                        : 'text-gray-700 hover:bg-white/50 hover:text-gray-800',
                                ]"
                            >
                                {{ categorie.nom_categorie_client }}
                            </Link>
                            <Link
                                v-for="structureType in allStructureTypes"
                                :key="structureType.id"
                                :href="
                                    route(
                                        'villes.disciplines.structuretypes.show',
                                        {
                                            city: city.id,
                                            discipline: discipline.slug,
                                            structuretype: structureType.id,
                                        }
                                    )
                                "
                                class="w-full px-2 py-3 text-center text-sm font-medium leading-5 text-gray-700 ring-white ring-opacity-10 ring-offset-2 ring-offset-green-200 hover:bg-green-600 hover:text-white focus:bg-green-600 focus:text-white focus:outline-none focus:ring-2"
                            >
                                {{ structureType.name }}
                            </Link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="structure">
            <section
                class="mx-auto max-w-full px-0 py-2 sm:px-4 md:my-4 md:py-6 lg:px-8"
            >
                <div
                    class="flex flex-col-reverse justify-between rounded-lg bg-white px-4 py-6 shadow md:flex-row md:space-x-6"
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
                                    :class="{
                                        'rounded border border-gray-100 px-6 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500': true,
                                        'border-gray-100 bg-indigo-500 text-white':
                                            selectedDiscipline.id ===
                                            discipline.id,
                                    }"
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
                                    :class="{
                                        'rounded border border-gray-100 px-6 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500': true,
                                        'border-gray-100 bg-indigo-500 text-white':
                                            selectedCategory.id ===
                                            categorie.id,
                                    }"
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
                                v-if="selectedCategory"
                                class="my-6 grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3"
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
                                            class="block text-sm font-medium text-gray-700"
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
                                                        structure:
                                                            structure.slug,
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
                        <div class="space-y-4">
                            <CitiesAround
                                v-if="citiesAround.length > 0"
                                :citiesAround="citiesAround"
                            />
                            <DisciplinesSimilaires
                                v-if="disciplinesSimilaires.length > 0"
                                :disciplinesSimilaires="disciplinesSimilaires"
                            />
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
<style>
.course {
    @apply bg-blue-400 text-white;
}
</style>
