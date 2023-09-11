<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import {
    ChevronLeftIcon,
    PlusIcon,
    AcademicCapIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    structure: Object,
    discipline: Object,
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

const selectedCategoryId = ref(null);

const currentCategorie = ref(props.categoriesListByDiscipline[0]);
const showAddActiviteModal = ref(false);
const openAddActiviteModal = () => {
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
                    class="shrink-0 px-2 py-2.5 text-center text-lg font-semibold text-indigo-700 md:px-6 md:py-4 md:text-left md:text-3xl md:font-bold"
                >
                    {{ discipline.name }}
                </h1>
                <Link
                    :href="
                        route('structures.categories.show', {
                            structure: structure.slug,
                            discipline: discipline.slug,
                            categorie: category.id,
                        })
                    "
                    v-for="category in categoriesListByDiscipline"
                    :key="category.id"
                    :index="category.id"
                    class="flex h-full w-full flex-col items-center border border-gray-200 bg-white py-2.5 text-xs text-slate-700 md:py-4"
                >
                    <AcademicCapIcon class="h-6 w-6" />
                    <div class="">
                        {{ category.nom_categorie_pro }}
                    </div>
                </Link>
            </div>
        </template>
        <template #default>
            <MicroNavActiviteBackPro @eventFromChild="handleButtonEvent" />
            <div
                class="relative flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
            >
                <div class="mx-auto max-w-full flex-1 space-y-8 lg:px-4">
                    <div
                        class="flex w-full flex-col items-center justify-start space-y-2 px-2 py-3 md:h-20 md:flex-row md:space-x-4 md:space-y-0 md:px-0 md:py-6"
                    >
                        <p class="text-lg font-medium leading-6 text-gray-800">
                            Ajouter
                            <span v-if="displayActivites">une activité</span
                            ><span v-if="displayTarifs">un tarif</span
                            ><span v-if="displayPlanning">un planning</span> à
                            <span class="text-indigo-500">{{
                                discipline.name
                            }}</span>
                        </p>
                        <button
                            v-if="displayActivites"
                            type="button"
                            @click="openAddActiviteModal()"
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
                :discipline="discipline"
                :categories="categoriesListByDiscipline"
                :criteres="criteres"
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
