<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, defineAsyncComponent } from "vue";
import ButtonsActiviteEdit from "@/Components/Inscription/Activity/ButtonsActiviteEdit.vue";
import { PlusIcon } from "@heroicons/vue/24/outline";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import InscriptionNavigation from "@/Components/Navigation/InscriptionNavigation.vue";
import PathsInscriptionNavigation from "@/Components/Navigation/PathsInscriptionNavigation.vue";

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

const showSidebar = ref(false);

const selectedCategoryId = ref(props.activite.categorie_id);

const currentCategorie = ref({});
const showAddActiviteModal = ref(false);
const openAddActiviteModal = (categorie) => {
    currentCategorie.value = categorie;
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
    if (message === "Mon planning") {
        displayActivity.value = false;
        displayTarif.value = false;
        displayPlanning.value = true;
    } else if (message === "Mes tarifs") {
        displayActivity.value = false;
        displayPlanning.value = false;
        displayTarif.value = true;
    } else if (message === "Mes activites") {
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
            <h2
                class="w-full py-6 text-center text-xl font-semibold leading-tight text-gray-800"
            >
                Ajouter ou modifier votre activité
                <span class="text-blue-700"></span>
            </h2>
        </template>

        <div
            class="relative flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
        >
            <InscriptionNavigation
                :can="can"
                :displayActivity="displayActivity"
                :displayTarif="displayTarif"
                :displayPlanning="displayPlanning"
                :structure="structure"
                @eventFromChild="handleButtonEvent"
                class="hidden md:flex"
            />
            <div class="mx-auto max-w-full flex-1 lg:px-4">
                <PathsInscriptionNavigation />
                <button
                    @click="showSidebar = !showSidebar"
                    class="my-2 inline-flex w-full items-center justify-end self-end rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:text-gray-500 focus:text-gray-500 focus:outline-none md:hidden"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            :class="{
                                hidden: showSidebar,
                                'inline-flex': !showSidebar,
                            }"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{
                                hidden: !showSidebar,
                                'inline-flex': showSidebar,
                            }"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
                <InscriptionNavigation
                    v-if="showSidebar"
                    :can="can"
                    :structure="structure"
                    class="my-4 flex md:hidden"
                />
                <TabGroup :defaultIndex="defaultTabIndex">
                    <section class="space-y-4 text-gray-700">
                        <div>
                            <h2
                                class="py-4 text-center text-3xl font-bold uppercase"
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
                                                {{
                                                    categorie.nom_categorie_pro
                                                }}
                                            </button>
                                        </Tab>
                                    </TabList>
                                </div>
                            </div>
                        </div>
                    </section>

                    <TabPanels class="mx-auto max-w-full py-6 text-gray-700">
                        <TabPanel
                            v-for="(
                                categorie, idx
                            ) in categoriesListByDiscipline"
                            :key="categorie.id"
                            class="flex flex-col space-y-4"
                        >
                            <!-- <ButtonsActiviteEdit
                                :displayActivity="displayActivity"
                                :displayTarif="displayTarif"
                                :displayPlanning="displayPlanning"
                                :structure="structure"
                                @eventFromChild="handleButtonEvent"
                            /> -->
                            <div
                                class="flex w-full flex-col items-center justify-between space-y-2 px-2 py-3 md:h-20 md:flex-row md:space-y-0 md:px-0 md:py-6"
                            >
                                <div
                                    class="text-center text-lg font-semibold text-gray-700 md:text-left"
                                >
                                    {{ categorie.nom_categorie_pro }}
                                </div>
                                <button
                                    v-if="displayActivity"
                                    type="button"
                                    @click="openAddActiviteModal(categorie)"
                                    class="flex w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:flex md:w-auto"
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
                            <PlanningDisplay
                                v-if="displayPlanning"
                                :errors="errors"
                                :structure="structure"
                                :structureActivites="filteredActivites"
                            />
                            <TarifDisplay
                                v-if="displayTarif"
                                :errors="errors"
                                :structure="structure"
                                :tarif-types="tarifTypes"
                                :structureActivites="filteredActivites"
                                :activiteForTarifs="activiteForTarifs"
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
            :structureActivites="filteredActivites"
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
