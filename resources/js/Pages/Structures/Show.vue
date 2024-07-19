<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import {
    ref,
    watch,
    computed,
    onMounted,
    toRaw,
    defineAsyncComponent,
} from "vue";
import { debounce } from "lodash";
import CritereForm from "@/Components/Criteres/CritereForm.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import LeafletMap from "@/Components/Maps/LeafletMap.vue";
import VueCal from "vue-cal";
import "vue-cal/dist/vuecal.css";
import autoAnimate from "@formkit/auto-animate";
import {
    UserIcon,
    AtSymbolIcon,
    GlobeAltIcon,
    PhoneIcon,
    HomeIcon,
} from "@heroicons/vue/24/outline";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";

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
    filters: Object,
    currentRoute: Object,
});

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
const ActiviteCard = defineAsyncComponent(() =>
    import("@/Components/Structures/ActiviteCard.vue")
);

const structure = ref(props.structure);

const selectedDiscipline = ref(null);
const selectedCategory = ref(null);

const initializeSelections = () => {
    if (props.requestDiscipline && structure.value) {
        const discipline = structure.value.disciplines.find(
            (d) => d.id === props.requestDiscipline.id
        );
        if (discipline) {
            selectedDiscipline.value = discipline;

            if (props.requestCategory) {
                const category = discipline.str_categories.find(
                    (c) => c.id === props.requestCategory.id
                );
                if (category) {
                    selectedCategory.value = category;
                }
            }
        }
    }
};

const handleDisciplineClick = (discipline) => {
    selectedDiscipline.value = discipline;
    selectedCategory.value = null;
};

const filteredCategories = computed(() => {
    if (!selectedDiscipline.value) return [];
    return selectedDiscipline.value.str_categories || [];
});

const handleCategoryClick = (category) => {
    selectedCategory.value = category;
};

const filteredActivities = computed(() => {
    if (!selectedCategory.value || !structure.value) return [];

    const category = structure.value.disciplines
        .flatMap((d) => d.str_categories)
        .find((c) => c.id === selectedCategory.value.id);

    return category
        ? category.str_activites.filter(
              (activite) => activite.produits && activite.produits.length > 0
          )
        : [];
});

const filteredCriteres = computed(() => {
    if (!selectedDiscipline.value || !selectedCategory.value) return [];

    return props.criteres.filter(
        (critere) =>
            critere.discipline_id === selectedDiscipline.value.id &&
            critere.categorie_id === selectedCategory.value.id
    );
});

const formCriteres = useForm({
    criteresBase: props.filters?.crit ?? {},
    sousCriteres: props.filters?.ssCrit ?? {},
    page: 1,
});

const debouncedFilter = debounce((newFormCriteres) => {
    router.post(
        route(props.currentRoute.name, props.currentRoute.params),
        {
            crit: newFormCriteres.criteresBase,
            ssCrit: newFormCriteres.sousCriteres,
            page: newFormCriteres.page,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                structure.value = page.props.structure;
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

    for (const activite of filteredActivities.value) {
        for (const produit of activite.produits) {
            for (const planning of produit.plannings) {
                if (planning) {
                    const event = {
                        start: planning.start,
                        end: planning.end,
                        title: `
                        <p class="text-sm text-gray-900 uppercase">
                            ${planning.title ?? activite.titre}
                        </p>
                        `,
                        content: `
                        <ul class="text-xs font-semibold text-gray-800">
                            <li>${activite.discipline.name}</li>
                            <li>Produit n°: ${planning.produit_id}</li>
                        </ul>
                        `,
                        contentFull: `
                        <p class="text-xs text-gray-600 truncate">
                            ${activite.description}
                        </p>
                        `,
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

const listToAnimate = ref();
onMounted(() => {
    initializeSelections();
    if (listToAnimate.value) {
        autoAnimate(listToAnimate.value);
    }
});
</script>

<template>
    <Head
        :title="structure.name + ' - ' + structure.structuretype.name"
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
        :departement="departement"
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
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium normal-case transition hover:text-gray-900"
                                >
                                    {{ city.ville }}
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
                                    :href="route('structures.show', structure)"
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
                class="sticky left-0 right-0 top-16 z-[9998] bg-transparent backdrop-blur-md"
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
                        <div
                            class="flex flex-col space-y-3"
                            v-if="structure.adresses.length > 0"
                        >
                            <h3 class="text-base">
                                Localisation:
                                <span class="font-semibold">
                                    {{ structure.adresses[0].city_name }},
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
                        <div v-if="structure.disciplines.length > 0">
                            <h3
                                class="my-4 text-lg font-semibold text-gray-700"
                            >
                                Les disciplines proposées:
                            </h3>
                            <div
                                class="grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-3 md:gap-8 lg:grid-cols-4"
                            >
                                <button
                                    type="button"
                                    v-for="discipline in structure.disciplines"
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
                        <div v-if="selectedDiscipline">
                            <h3
                                class="my-4 text-lg font-semibold text-gray-700"
                            >
                                Les catégories proposées pour
                                <span class="text-indigo-500">{{
                                    selectedDiscipline.name
                                }}</span>
                                :
                            </h3>
                            <div
                                v-if="
                                    selectedDiscipline &&
                                    filteredCategories.length > 0
                                "
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
                        <div v-if="selectedCategory">
                            <h3
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
                                class="mx-auto grid w-full grid-cols-1 gap-4 bg-gray-50 p-2 shadow md:grid-cols-3"
                            >
                                <CritereForm
                                    :criteres="filteredCriteres"
                                    :filters="filters"
                                    v-model:criteres-base="
                                        formCriteres.criteresBase
                                    "
                                    v-model:sous-criteres="
                                        formCriteres.sousCriteres
                                    "
                                    @reset-criteres="resetFormCriteres"
                                />
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
                                        v-if="
                                            selectedCategory &&
                                            filteredActivities.length > 0
                                        "
                                        ref="listToAnimate"
                                        class="my-6 grid w-full grid-cols-1 gap-4 text-gray-600 md:grid-cols-2"
                                    >
                                        <ActiviteCard
                                            v-for="activite in filteredActivities"
                                            :key="activite.id"
                                            :activite="activite"
                                            :link="
                                                route(
                                                    'structures.activites.show',
                                                    {
                                                        activite: activite,
                                                        slug: activite.slug_title,
                                                    }
                                                )
                                            "
                                        />
                                    </div>
                                </TabPanel>
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
    @apply bg-green-300 text-blue-700;
}
</style>
