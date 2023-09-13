<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import {
    AcademicCapIcon,
    PlusIcon,
    ChevronLeftIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    structure: Object,
    categorie: Object,
    discipline: Object,
    structureActivites: Object,
    criteres: Object,
    categoriesListByDiscipline: Object,
    categoriesWithoutStructures: Object,
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

const currentCategorie = ref({});
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
        (critere) => critere.categorie_id === props.categorie.id.value
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
                    :href="
                        route('structures.disciplines.show', {
                            structure: props.structure,
                            discipline: props.discipline.slug,
                        })
                    "
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <Link
                    :href="
                        route('structures.disciplines.show', {
                            structure: props.structure,
                            discipline: props.discipline.slug,
                        })
                    "
                    class="shrink-0 px-3 py-2.5 text-center text-lg font-semibold text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    {{ discipline.name }}
                </Link>
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
                    class="flex h-full w-full flex-col items-center border border-gray-200 py-2.5 text-xs md:py-4"
                    :class="{
                        'bg-green-500 text-white': category.id === categorie.id,
                        'bg-white text-slate-700': category.id !== categorie.id,
                    }"
                >
                    <AcademicCapIcon class="h-6 w-6" />
                    <div class="">
                        {{ category.nom_categorie_pro }}
                    </div>
                </Link>
                <BreezeDropdown align="right" width="48">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button
                                type="button"
                                class="inline-flex items-center border border-transparent bg-green-500 px-3 py-2.5 text-gray-50 transition duration-150 ease-in-out hover:bg-green-600 hover:text-white focus:outline-none md:py-4"
                            >
                                <PlusIcon class="h-10 w-8" />
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <BreezeDropdownLink
                            :href="
                                route('structures.categories.show', {
                                    structure: structure.slug,
                                    discipline: discipline.slug,
                                    categorie: category.id,
                                })
                            "
                            v-for="category in categoriesWithoutStructures"
                            :key="category.id"
                        >
                            {{ category.nom_categorie_pro }}
                        </BreezeDropdownLink>
                    </template>
                </BreezeDropdown>
            </div>
        </template>
        <template #default>
            <MicroNavActiviteBackPro
                :discipline="discipline"
                :categorie="categorie"
                @eventFromChild="handleButtonEvent"
            />
            <div
                class="relative flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
            >
                <div class="mx-auto max-w-full flex-1 space-y-8 lg:px-4">
                    <div
                        class="flex w-full flex-col items-center justify-start space-y-2 px-2 py-3 md:h-20 md:flex-row md:space-x-4 md:space-y-0 md:px-0 md:py-6"
                    >
                        <p class="text-lg font-medium leading-6 text-slate-500">
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
                            <PlusIcon class="h-6 w-6" />
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
                </div>
            </div>
            <ModalAddActivite
                :errors="errors"
                :structure="structure"
                :category="categorie"
                :categories="categoriesListByDiscipline"
                :discipline="discipline"
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