<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, defineAsyncComponent } from "vue";
import ButtonsActiviteEdit from "@/Components/Inscription/Activity/ButtonsActiviteEdit.vue";
import { PlusIcon } from "@heroicons/vue/24/outline";
import {
    TabGroup,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
} from "@headlessui/vue";

const props = defineProps({
    errors: Object,
    structure: Object,
    activite: Object,
    structureActivites: Object,
    criteres: Object,
    categoriesListByDiscipline: Object,
    tarifTypes: Object,
    activiteForTarifs: Object,
    can: Object,
});

const ModalAddActivite = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalAddActivite.vue")
);

const ModalAddTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalAddTarif.vue")
);

const ActivityDisplay = defineAsyncComponent(() =>
    import("@/Components/Inscription/Activity/ActivityDisplay.vue")
);

const TarifDisplay = defineAsyncComponent(() =>
    import("@/Components/Inscription/Activity/TarifDisplay.vue")
);

const PlanningDisplay = defineAsyncComponent(() =>
    import("@/Components/Inscription/Activity/PlanningDisplay.vue")
);

const selectedCategoryId = ref(props.activite.categorie_id);

const currentCategorie = ref({});
const showAddActiviteModal = ref(false);
const openAddActiviteModal = (categorie) => {
    currentCategorie.value = categorie;
    console.log(currentCategorie.value);
    showAddActiviteModal.value = true;
};

const showAddTarifModal = ref(false);
const openAddTarifModal = (structure) => {
    showAddTarifModal.value = true;
};

const displayActivity = ref(true);
const displayTarif = ref(false);
const displayPlanning = ref(false);

const handleButtonEvent = (message) => {
  if (message === 'Mon planning') {
    displayActivity.value = false;
    displayTarif.value = false;
    displayPlanning.value = true;
  } else if (message === 'Mes tarifs') {
    displayActivity.value = false;
    displayPlanning.value = false;
    displayTarif.value = true;
  }else if (message === 'Mes activites') {
    displayActivity.value = true;
    displayPlanning.value = false;
    displayTarif.value = false;
  }
};

const defaultTabIndex = computed(() => {
    return props.categoriesListByDiscipline.findIndex(
        (categorie) => categorie.id === selectedCategoryId.value
    );
});

const filteredActivites = computed(() => {
    if (!selectedCategoryId.value) {
        return [];
    }
    return props.structureActivites.filter(
        (activity) => activity.categorie_id === selectedCategoryId.value
    );
});

const filteredCriteres = computed(() => {
    return props.criteres.filter(
        (critere) => critere.categorie_id === selectedCategoryId.value
    );
});

const latestAdresseId = computed(() => {
    if (props.structure.adresses.length > 0) {
        const latestAdresse = props.structure.adresses[0];
        return latestAdresse.id;
    }
    return null; // Return a default value if there are no adresses
});

onMounted(() => {
    selectedCategoryId.value = props.activite.categorie_id;
});
</script>

<template>
    <Head title="Modifier vos activités" />

    <AppLayout>
        <template #header>
            <div
                class="flex flex-col items-start justify-between md:flex-row md:items-center"
            >
                <div>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Ajouter ou modifier votre activité
                        <span class="text-blue-700"></span>
                    </h2>
                </div>
                <div class="mt-4 w-full md:mt-0 md:w-1/4">
                    <div
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="
                                route('structures.activites.index', structure)
                            "
                            v-if="can.update"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Mes activités</Link
                        >
                        <Link
                            :href="route('structures.show', structure.slug)"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Voir la structure</Link
                        >
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <TabGroup :defaultIndex="defaultTabIndex">
                    <section class="space-y-4 text-gray-700">
                        <div>
                            <h2
                                class="text-center text-3xl font-bold uppercase md:text-left"
                            >
                                {{ activite.discipline.name }}
                            </h2>
                            <!-- categories -->
                            <div class="my-4 w-full">
                                <div class="mt-1">
                                    <TabList
                                        class="flex w-full flex-col items-stretch justify-between divide-y divide-green-600 rounded-sm border border-gray-300 bg-white/20 px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none sm:text-base md:flex-row md:items-center md:divide-y-0"
                                    >
                                        <Tab
                                            v-for="categorie in categoriesListByDiscipline"
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
                                                {{ categorie.nom_categorie }}
                                            </button>
                                        </Tab>
                                    </TabList>
                                </div>
                            </div>
                        </div>
                    </section>

                    <TabPanels class="mx-auto max-w-7xl py-6 text-gray-700">
                        <TabPanel
                            v-for="(
                                categorie, idx
                            ) in categoriesListByDiscipline"
                            :key="categorie.id"
                            class="flex flex-col space-y-4"
                        >
                            <ButtonsActiviteEdit :structure="structure" @eventFromChild="handleButtonEvent"/>
                            <div
                                class="flex w-full flex-col items-center justify-between space-y-2 px-2 py-6 md:flex-row md:space-y-0 md:px-0"
                            >
                                <div
                                    class="text-center text-lg font-semibold text-gray-700 md:text-left"
                                >
                                    {{ categorie.nom_categorie }}
                                </div>
                                <button
                                    v-if="displayActivity"
                                    type="button"
                                    @click="openAddActiviteModal(categorie)"
                                    class="w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:flex md:w-auto"
                                >
                                    Ajouter une activité
                                    <PlusIcon class="ml-2 h-5 w-5" />
                                </button>
                                <button
                                    v-if="displayTarif"
                                    type="button"
                                    @click="openAddTarifModal(structure)"
                                    class="w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:flex md:w-auto"
                                >
                                    Ajouter un tarif
                                    <PlusIcon class="ml-2 h-5 w-5" />
                                </button>
                            </div>
                            <ActivityDisplay
                                v-if="displayActivity"
                                :errors="errors"
                                :structure="structure"
                                :structureActivites="filteredActivites"
                                :filteredCriteres="filteredCriteres"
                                :latestAdresseId="latestAdresseId"
                                :tarif-types="tarifTypes"
                                :activiteForTarifs="activiteForTarifs"
                            />
                            <TarifDisplay
                                v-if="displayTarif"
                                :errors="errors"
                                :structure="structure"
                                :tarif-types="tarifTypes"
                                :activiteForTarifs="activiteForTarifs"
                            />
                            <PlanningDisplay
                                v-if="displayPlanning"
                                :errors="errors"
                                :structure="structure"
                                :structureActivites="filteredActivites"
                            />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>

                <div class="hidden sm:block" aria-hidden="true">
                    <div class="py-5">
                        <div class="border-t border-gray-200" />
                    </div>
                </div>
            </div>
        </div>
        <ModalAddActivite
            :errors="errors"
            :structure="structure"
            :activite="activite"
            :criteres="criteres"
            :categorie="currentCategorie"
            :show="showAddActiviteModal"
            @close="showAddActiviteModal = false"
        />
        <ModalAddTarif
            :errors="errors"
            :structure="structure"
            :tarif-types="tarifTypes"
            :activiteForTarifs="activiteForTarifs"
            :show="showAddTarifModal"
            @close="showAddTarifModal = false"
        />
    </AppLayout>
</template>

<style>
.vc-select select {
    background-image: unset;
}
</style>
