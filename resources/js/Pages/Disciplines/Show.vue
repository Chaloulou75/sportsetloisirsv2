<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent, onMounted } from "vue";
import LeafletMapMultiple from "@/Components/LeafletMapMultiple.vue";
import NavLink from "@/Components/NavLink.vue";
import {
    TabGroup,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
} from "@headlessui/vue";

const props = defineProps({
    discipline: Object,
    structures: Object,
    categories: Object,
});

const selectedCategoryId = ref(props.categories[0].id);

const defaultTabIndex = computed(() => {
    return props.categories.findIndex(
        (categorie) => categorie.id === selectedCategoryId.value
    );
});

const filteredStructures = computed(() => {
  return props.structures.filter((structure) => {
    return structure.activites && structure.activites.some((activite) => {
        return activite.categorie.id === selectedCategoryId.value;
      });
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

const getUniqueActivitesDiscipline = (activites) => {
    const uniqueNames = new Set();
    return activites.filter((activite) => {
        if (!uniqueNames.has(activite.discipline.name)) {
          uniqueNames.add(activite.discipline.name);
          return true;
        }
        return false;
    });
};

const getUniqueActivitesTitre = (activites) => {
    const uniqueNames = new Set();
    return activites.filter((activite) => {
        if (!uniqueNames.has(activite.titre)) {
          uniqueNames.add(activite.titre);
          return true;
        }
        return false;
    });
};

onMounted(() => {
    selectedCategoryId.value = props.categories[0].id;
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

    <AppLayout>

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ discipline.name }}
                <span class="text-xs italic text-gray-600"
                    >({{ discipline.view_count }} vues)
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
        <TabGroup :defaultIndex="defaultTabIndex">
            <div class="px-2 py-4 mx-auto max-w-7xl sm:px-3 lg:px-6">
                <div
                    class="flex items-center justify-around space-x-4"
                >
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
            <TabPanels class="mx-auto max-w-7xl py-6 text-gray-700">
                <TabPanel
                    v-for="(
                        categorie, idx
                    ) in categories"
                    :key="categorie.id"
                >
                    <template v-if="filteredStructures.length > 0">
                        <div
                            class="mx-auto flex min-h-screen max-w-full flex-col px-2 sm:px-6 md:flex-row md:space-x-4 lg:px-8 py-12"
                        >
                            <div class="md:w-1/2">
                                <div
                                    class="grid grid-cols-1 place-content-stretch place-items-stretch gap-4 md:grid-cols-2 h-auto"
                                >
                                    <StructureCard
                                        v-for="(structure, index) in filteredStructures"
                                        :key="structure.id"
                                        :index="index"
                                        :structure="structure"
                                        @mouseover="showTooltip(structure)"
                                        @mouseout="hideTooltip()"
                                    />
                                </div>
                                <!-- <div class="flex justify-end p-10">
                                    <Pagination :links="structures.links" />
                                </div> -->
                            </div>
                            <LeafletMapMultiple
                                class="md:sticky md:top-2 md:w-1/2"
                                :structures="filteredStructures"
                                :hovered-structure="hoveredStructure"
                            />
                        </div>
                    </template>
                    <template v-else>
                        <div
                            class="mx-auto min-h-screen max-w-7xl px-2 sm:px-6 lg:px-8 py-12"
                        >
                            <p class="font-medium text-gray-700">
                                Il n'y a pas encore de structures inscrites en <span class="font-semibold">{{ discipline.name }}</span> pour la catégorie <span class="font-semibold">{{ categorie.nom_categorie_client }}</span>.
                            </p>
                        </div>
                    </template>

                </TabPanel>
            </TabPanels>
        </TabGroup>


    </AppLayout>
</template>
