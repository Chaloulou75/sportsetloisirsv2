<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent, onMounted } from "vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import DisciplinesSimilaires from "@/Components/Disciplines/DisciplinesSimilaires.vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";

const props = defineProps({
    familles: Object,
    discipline: Object,
    disciplinesSimilaires: Object,
    structures: Object,
    categories: Object,
    listDisciplines: Object,
    allCities: Object,
});

const selectedCategoryId = ref(null);

const defaultTabIndex = computed(() => {
    return props.categories.findIndex(
        (categorie) => categorie.id === selectedCategoryId.value
    );
});

const filteredStructures = computed(() => {
    return props.structures.data.filter((structure) => {
        return (
            structure.activites &&
            structure.activites.some((activite) => {
                return activite.categorie.id === selectedCategoryId.value;
            })
        );
    });
});

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const hoveredStructure = ref(null);

function showTooltip(structure) {
    hoveredStructure.value = structure.id;
}
function hideTooltip() {
    hoveredStructure.value = null;
}

onMounted(() => {
    if (props.categories && props.categories.length > 0) {
        selectedCategoryId.value = props.categories[0].id;
    }
});
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
    >
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <div class="mx-auto my-6 max-w-full px-2 py-4 md:px-4 lg:px-6">
                <div
                    class="mx-auto my-2 flex w-full flex-col items-center justify-center space-y-2 bg-slate-100/60 px-2 py-2 md:w-1/4"
                >
                    <h1
                        class="border-b-2 border-slate-400 text-2xl font-bold leading-tight tracking-widest text-gray-800 md:text-4xl"
                    >
                        {{ discipline.name }}
                    </h1>
                </div>
                <div
                    class="mx-auto hidden w-2/3 flex-col items-center justify-center space-y-2 bg-slate-100/60 px-2 py-2 md:flex md:w-1/4"
                >
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
                </div>
            </div>
        </template>
        <template v-if="categories.length > 0">
            <TabGroup :defaultIndex="defaultTabIndex">
                <div class="mx-auto max-w-full px-2 py-4 sm:px-3 lg:px-6">
                    <div class="flex items-center justify-around space-x-4">
                        <div class="my-4 w-full">
                            <div class="mt-1">
                                <TabList
                                    class="flex w-full flex-col items-stretch justify-between divide-y divide-green-600 rounded-sm border border-gray-300 bg-white/20 px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none sm:text-base md:flex-row md:items-center md:divide-y-0"
                                >
                                    <Tab
                                        v-for="categorie in categories"
                                        :key="categorie.id"
                                        as="template"
                                        v-slot="{ selected }"
                                        class="py-2"
                                        v-model="selectedCategoryId"
                                    >
                                        <button
                                            @click="
                                                selectedCategoryId =
                                                    categorie.id
                                            "
                                            :class="[
                                                'w-full px-2 py-3 text-sm font-medium leading-5 text-gray-700 ring-white ring-opacity-10 ring-offset-2 ring-offset-green-200 focus:outline-none focus:ring-2',
                                                selected
                                                    ? 'bg-green-600 text-white'
                                                    : 'text-gray-700 hover:bg-white/50 hover:text-gray-800',
                                            ]"
                                        >
                                            {{ categorie.nom_categorie_client }}
                                        </button>
                                    </Tab>
                                </TabList>
                            </div>
                        </div>
                    </div>
                </div>
                <TabPanels class="mx-auto max-w-full py-6 text-gray-700">
                    <TabPanel
                        v-for="(categorie, idx) in categories"
                        :key="categorie.id"
                    >
                        <template v-if="filteredStructures.length > 0">
                            <div
                                class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-4 lg:px-8"
                            >
                                <div class="md:w-1/2">
                                    <div
                                        class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2"
                                    >
                                        <StructureCard
                                            v-for="(
                                                structure, index
                                            ) in filteredStructures"
                                            :key="structure.id"
                                            :index="index"
                                            :structure="structure"
                                            @mouseover="showTooltip(structure)"
                                            @mouseout="hideTooltip()"
                                        />
                                    </div>
                                    <div class="flex justify-end p-10">
                                        <Pagination :links="structures.links" />
                                    </div>
                                </div>
                                <div class="space-y-4 md:sticky md:w-1/2">
                                    <LeafletMapMultiple
                                        class="md:top-2"
                                        :structures="filteredStructures"
                                        :hovered-structure="hoveredStructure"
                                        :zoom="11"
                                    />
                                    <DisciplinesSimilaires
                                        :disciplinesSimilaires="
                                            disciplinesSimilaires
                                        "
                                    />
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div
                                class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row lg:px-8"
                            >
                                <p
                                    class="w-full font-medium text-gray-700 md:w-2/3"
                                >
                                    Il n'y a pas encore de structures inscrites
                                    en
                                    <span class="font-semibold">{{
                                        discipline.name
                                    }}</span>
                                    pour la catégorie
                                    <span class="font-semibold">{{
                                        categorie.nom_categorie_client
                                    }}</span
                                    >.
                                </p>
                                <div class="w-full px-4 md:w-1/3">
                                    <DisciplinesSimilaires
                                        :disciplinesSimilaires="
                                            disciplinesSimilaires
                                        "
                                    />
                                </div>
                            </div>
                        </template>
                    </TabPanel>
                </TabPanels>
            </TabGroup>
        </template>
        <template v-else>
            <div
                class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row lg:px-8"
            >
                <p class="w-full font-medium text-gray-700 md:w-2/3">
                    Il n'y a pas encore de structures inscrites en
                    <span class="font-semibold">{{ discipline.name }}</span
                    >.
                </p>
                <div class="w-full px-4 md:w-1/3">
                    <DisciplinesSimilaires
                        :disciplinesSimilaires="disciplinesSimilaires"
                    />
                </div>
            </div>
        </template>
    </ResultLayout>
</template>
