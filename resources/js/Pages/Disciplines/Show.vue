<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent, onMounted } from "vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import DisciplinesSimilaires from "@/Components/Disciplines/DisciplinesSimilaires.vue";
import NavLink from "@/Components/NavLink.vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";

const props = defineProps({
    discipline: Object,
    disciplinesSimilaires: Object,
    structures: Object,
    categories: Object,
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
    <Head :title="discipline.name" :description="
            'Vous souhaitez pratiquer un sport de ' +
            discipline.name +
            ' en France ? ' +
            discipline.activites_count +
            ' structures sur notre site prêts à vous accueillir.'
        " />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ discipline.name }}
                <span class="text-xs italic text-gray-600">({{ discipline.view_count }} vues)
                </span>
            </h2>

            <p class="py-2 text-base font-medium leading-relaxed text-gray-600">
                Choisissez parmi les
                <span class="font-semibold text-gray-800">
                    {{ discipline.activites_count }}
                </span>
                activités en lien avec la discipline
                <span class="font-semibold text-gray-800">
                    {{ discipline.name }}
                </span>
                en France. <br />
                Consultez la liste des
                <span class="font-semibold text-gray-800">
                    {{ discipline.activites_count }}
                </span>
                activités disponibles, comparez services, tarifs et horaires en
                2 clics ! Pratiquer un sport de
                <span class="font-semibold text-gray-800">{{
                    discipline.name
                    }}</span>
                n'a jamais été aussi simple!
            </p>
        </template>
        <template v-if="categories.length > 0">
            <TabGroup :defaultIndex="defaultTabIndex">
                <div class="mx-auto max-w-full px-2 py-4 sm:px-3 lg:px-6">
                    <div class="flex items-center justify-around space-x-4">
                        <div class="my-4 w-full">
                            <div class="mt-1">
                                <TabList
                                    class="flex w-full flex-col items-stretch justify-between divide-y divide-green-600 rounded-sm border border-gray-300 bg-white/20 px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none sm:text-base md:flex-row md:items-center md:divide-y-0">
                                    <Tab v-for="categorie in categories" :key="categorie.id" as="template"
                                        v-slot="{ selected }" class="py-2" v-model="selectedCategoryId">
                                        <button @click="
                                                selectedCategoryId =
                                                    categorie.id
                                            " :class="[
                                                'w-full px-2 py-3 text-sm font-medium leading-5 text-gray-700 ring-white ring-opacity-10 ring-offset-2 ring-offset-green-200 focus:outline-none focus:ring-2',
                                                selected
                                                    ? 'bg-green-600 text-white'
                                                    : 'text-gray-700 hover:bg-white/50 hover:text-gray-800',
                                            ]">
                                            {{ categorie.nom_categorie_client }}
                                        </button>
                                    </Tab>
                                </TabList>
                            </div>
                        </div>
                    </div>
                </div>
                <TabPanels class="mx-auto max-w-full py-6 text-gray-700">
                    <TabPanel v-for="(categorie, idx) in categories" :key="categorie.id">
                        <template v-if="filteredStructures.length > 0">
                            <div
                                class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row md:space-x-4 lg:px-8">
                                <div class="md:w-1/2">
                                    <div
                                        class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2">
                                        <StructureCard v-for="(
                                                structure, index
                                            ) in filteredStructures" :key="structure.id" :index="index"
                                            :structure="structure" @mouseover="showTooltip(structure)"
                                            @mouseout="hideTooltip()" />
                                    </div>
                                    <div class="flex justify-end p-10">
                                        <Pagination :links="structures.links" />
                                    </div>
                                </div>
                                <div class="space-y-4 md:sticky md:w-1/2">
                                    <LeafletMapMultiple class="md:top-2" :structures="filteredStructures"
                                        :hovered-structure="hoveredStructure" :zoom="11" />
                                    <DisciplinesSimilaires :disciplinesSimilaires="
                                            disciplinesSimilaires
                                        " />
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div
                                class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row lg:px-8">
                                <p class="w-full font-medium text-gray-700 md:w-2/3">
                                    Il n'y a pas encore de structures inscrites
                                    en
                                    <span class="font-semibold">{{
                                        discipline.name
                                        }}</span>
                                    pour la catégorie
                                    <span class="font-semibold">{{
                                        categorie.nom_categorie_client
                                        }}</span>.
                                </p>
                                <div class="w-full px-4 md:w-1/3">
                                    <DisciplinesSimilaires :disciplinesSimilaires="
                                            disciplinesSimilaires
                                        " />
                                </div>
                            </div>
                        </template>
                    </TabPanel>
                </TabPanels>
            </TabGroup>
        </template>
        <template v-else>
            <div class="mx-auto flex min-h-screen max-w-full flex-col px-2 py-12 sm:px-6 md:flex-row lg:px-8">
                <p class="w-full font-medium text-gray-700 md:w-2/3">
                    Il n'y a pas encore de structures inscrites en
                    <span class="font-semibold">{{ discipline.name }}</span>.
                </p>
                <div class="w-full px-4 md:w-1/3">
                    <DisciplinesSimilaires :disciplinesSimilaires="disciplinesSimilaires" />
                </div>
            </div>
        </template>
    </AppLayout>
</template>
