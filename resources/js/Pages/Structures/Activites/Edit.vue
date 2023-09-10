<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import { ChevronLeftIcon, AcademicCapIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    structure: Object,
    activite: Object,
    structureActivites: Object,
    criteres: Object,
    categoriesListByDiscipline: Object,
    tarifTypes: Object,
    activiteForTarifs: Object,
    confirmedReservationsCount: Number,
    allReservationsCount: Number,
    pendingReservationsCount: Number,
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

const MicroNavActiviteBackPro = defineAsyncComponent(() =>
    import("@/Components/Structures/MicroNavActiviteBackPro.vue")
);

const displayActivites = ref(true);
const displayTarifs = ref(false);
const displayPlanning = ref(false);

const handleButtonEvent = (message) => {
    if (message === "Mes activites") {
        displayActivites.value = true;
        displayTarifs.value = false;
        displayPlanning.value = false;
    } else if (message === "Mes tarifs") {
        displayActivites.value = false;
        displayTarifs.value = true;
        displayPlanning.value = false;
    } else if (message === "Planning") {
        displayActivites.value = false;
        displayTarifs.value = false;
        displayPlanning.value = true;
    }
};

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
    return null;
});
</script>

<template>
    <Head title="Modifier vos activités" />

    <ProLayout
        :structure="structure"
        :can="can"
        :allReservationsCount="allReservationsCount"
        :pendingReservationsCount="pendingReservationsCount"
        :confirmedReservationsCount="confirmedReservationsCount"
    >
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                    :href="route('structures.disciplines.index', structure)"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-2 py-2.5 text-center text-lg font-semibold text-indigo-700 md:px-6 md:py-4 md:text-left md:text-3xl md:font-bold"
                >
                    {{ activite.discipline.name }}
                </h1>
                <Link
                    :href="
                        route('structures.disciplines.show', {
                            structure: structure,
                            discipline: activite.discipline.slug,
                            categorie: categorie.id,
                        })
                    "
                    v-for="categorie in categoriesListByDiscipline"
                    :key="categorie.id"
                    :index="categorie.id"
                    class="flex h-full w-full flex-col items-center justify-between border-r border-blue-600 bg-white px-2 py-4 text-xs text-gray-800 hover:bg-gray-50"
                >
                    <AcademicCapIcon class="h-8 w-8" />
                    <div class="">
                        {{ categorie.nom_categorie_pro }}
                    </div>
                </Link>
            </div>
        </template>
        <template #default>
            <MicroNavActiviteBackPro
                :activite="activite"
                @eventFromChild="handleButtonEvent"
            />
            <div
                class="relative flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
            >
                <div class="mx-auto max-w-full flex-1 space-y-8 lg:px-4">
                    <!-- <TabGroup :defaultIndex="defaultTabIndex">
                        <section class="space-y-4 text-gray-700">
                            <div class="my-4 w-full">
                                <div class="mt-1">
                                    <TabList
                                        class="flex w-full flex-col items-stretch justify-between divide-y divide-green-600 rounded-sm border border-gray-300 bg-white/20 px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none sm:text-base md:flex-row md:items-center md:divide-y-0"
                                    >
                                        <Tab
                                            v-for="categorie in categoriesListByDiscipline"
                                            :key="categorie.id"
                                            :index="categorie.id"
                                            as="template"
                                            v-slot="{ selected }"
                                            class="py-2"
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
                        </section>

                        <TabPanels
                            class="mx-auto max-w-full py-6 text-gray-700"
                        >
                            <TabPanel
                                v-for="(
                                    categorie, idx
                                ) in categoriesListByDiscipline"
                                :key="categorie.id"
                                class="flex flex-col space-y-4"
                            >
                                <div
                                    class="flex w-full flex-col items-center justify-start space-y-2 px-2 py-3 md:h-20 md:flex-row md:space-x-4 md:space-y-0 md:px-0 md:py-6"
                                >
                                    <div
                                        class="text-center text-xl font-semibold text-indigo-600 md:text-left md:text-3xl"
                                    >
                                        {{ categorie.nom_categorie_pro }}
                                    </div>
                                    <button
                                        v-if="displayActivites"
                                        type="button"
                                        @click="openAddActiviteModal(categorie)"
                                        class="flex w-full items-center justify-between bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:w-auto"
                                    >
                                        <PlusIcon class="h-6 w-6" />
                                    </button>
                                    <button
                                        v-if="displayTarifs"
                                        type="button"
                                        @click="openAddTarifModal(structure)"
                                        class="w-full items-center justify-between bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:w-auto"
                                    >
                                        <PlusIcon class="h-5 w-5" />
                                    </button>
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </TabGroup> -->
                    <template v-if="displayActivites">
                        <ActivityDisplay
                            v-for="structureActivite in structureActivites"
                            :key="structureActivite.id"
                            :errors="errors"
                            :structure="structure"
                            :structureActivite="structureActivite"
                            :filteredCriteres="filteredCriteres"
                            :latestAdresseId="latestAdresseId"
                            :tarif-types="tarifTypes"
                            :activiteForTarifs="activiteForTarifs"
                        />
                        <div v-if="structureActivites.length === 0">
                            <p class="font-semibold italic text-gray-600">
                                Pas d'activité dans cette catégorie
                            </p>
                        </div>
                    </template>
                    <template v-if="displayPlanning">
                        <PlanningDisplay
                            :errors="errors"
                            :structure="structure"
                            :structureActivites="structureActivites"
                        />
                    </template>
                    <template v-if="displayTarifs">
                        <TarifDisplay
                            :errors="errors"
                            :structure="structure"
                            :tarif-types="tarifTypes"
                            :structureActivites="structureActivites"
                            :activiteForTarifs="activiteForTarifs"
                        />
                    </template>

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
                :structureActivites="structureActivites"
                :show="showAddTarifModal"
                @close="showAddTarifModal = false"
            />
        </template>
    </ProLayout>
</template>

<style>
.vc-select select {
    background-image: unset;
}
</style>
