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
    discipline: Object,
    categorie: Object,
    structureActivites: Object,
    uniqueCriteresInProducts: Object,
    criteres: Object,
    allCategories: Object,
    categoriesListByDiscipline: Object,
    categoriesWithoutStructures: Object,
    strCatTarifs: Object,
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

const ModalAddPlanning = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalAddPlanning.vue")
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

const showAddActiviteModal = ref(false);
const openAddActiviteModal = () => {
    showAddActiviteModal.value = true;
};

const showAddTarifModal = ref(false);
const openAddTarifModal = () => {
    showAddTarifModal.value = true;
};

const showAddPlanningModal = ref(false);
const openAddPlanningModal = () => {
    showAddPlanningModal.value = true;
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
        :all-reservations-count="allReservationsCount"
        :pending-reservations-count="pendingReservationsCount"
        :confirmed-reservations-count="confirmedReservationsCount"
    >
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    class="hidden h-full bg-blue-600 py-2.5 md:flex md:px-4 md:py-4"
                    :href="
                        route('structures.disciplines.show', {
                            structure: props.structure,
                            discipline: props.discipline.slug,
                        })
                    "
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <div
                    class="flex h-full w-full flex-col items-center md:flex-row"
                >
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
                        class="flex h-full w-full flex-col items-center border border-gray-200 py-2.5 text-xs hover:bg-gray-100 hover:text-slate-700 md:py-4"
                        :class="{
                            'bg-green-500 text-white':
                                category.id === categorie.id,
                            'bg-white text-slate-500':
                                category.id !== categorie.id,
                        }"
                    >
                        <AcademicCapIcon
                            class="hidden h-6 w-6 md:inline-flex"
                        />
                        <div class="text-center">
                            {{ category.nom_categorie_pro }}
                        </div>
                    </Link>
                    <div class="w-full md:w-auto">
                        <BreezeDropdown align="right" width="48">
                            <template #trigger>
                                <span
                                    class="inline-flex w-full items-center justify-center rounded-md"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex w-full items-center justify-center border border-transparent bg-green-500 px-3 py-2.5 text-gray-50 transition duration-150 ease-in-out hover:bg-green-600 hover:text-white focus:outline-none md:py-4"
                                    >
                                        <PlusIcon
                                            class="h-3 w-3 md:h-10 md:w-8"
                                        />
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
                </div>
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
                <div class="mx-auto max-w-full flex-1 space-y-8 px-1 lg:px-4">
                    <div
                        class="flex w-full flex-col items-center justify-start space-y-2 px-2 py-3 md:h-20 md:flex-row md:space-x-4 md:space-y-0 md:px-0 md:py-6"
                    >
                        <p class="text-lg font-medium leading-6 text-slate-500">
                            Ajouter
                            <span v-if="displayActivites">une activité</span
                            ><span v-if="displayTarifs">un tarif</span
                            ><span v-if="displayPlanning">un créneau</span> à
                            <span class="text-indigo-500">{{
                                discipline.name
                            }}</span>
                        </p>
                        <button
                            v-if="displayActivites"
                            type="button"
                            @click="openAddActiviteModal()"
                            class="inline-flex w-auto items-center justify-between bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:w-auto"
                        >
                            <PlusIcon class="h-6 w-6" />
                        </button>
                        <button
                            v-if="displayPlanning"
                            type="button"
                            @click="openAddPlanningModal()"
                            class="inline-flex w-auto items-center justify-between bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:w-auto"
                        >
                            <PlusIcon class="h-6 w-6" />
                        </button>
                        <button
                            v-if="displayTarifs"
                            type="button"
                            @click="openAddTarifModal()"
                            class="inline-flex w-auto items-center justify-between bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:w-auto"
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
                            :structure-activite="structureActivite"
                            :unique-criteres-in-products="
                                uniqueCriteresInProducts
                            "
                            :criteres="criteres"
                            :latest-adresse-id="latestAdresseId"
                            :activite-for-tarifs="activiteForTarifs"
                            :all-categories="categoriesListByDiscipline"
                            @add-tarif="openAddTarifModal"
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
                            :structure-activites="structureActivites"
                        />
                    </template>
                    <template v-if="displayTarifs">
                        <TarifDisplay
                            :errors="errors"
                            :structure="structure"
                            :discipline="discipline"
                            :categorie="categorie"
                            :str-cat-tarifs="strCatTarifs"
                            :all-categories="categoriesListByDiscipline"
                            :structure-activites="structureActivites"
                            :activite-for-tarifs="activiteForTarifs"
                        />
                    </template>
                </div>
            </div>
            <ModalAddActivite
                :errors="errors"
                :structure="structure"
                :category="categorie"
                :categories="allCategories"
                :discipline="discipline"
                :criteres="criteres"
                :show="showAddActiviteModal"
                @close="showAddActiviteModal = false"
            />
            <ModalAddTarif
                :errors="errors"
                :structure="structure"
                :discipline="discipline"
                :categorie="categorie"
                :all-categories="categoriesListByDiscipline"
                :activite-for-tarifs="activiteForTarifs"
                :structure-activites="structureActivites"
                :show="showAddTarifModal"
                @close="showAddTarifModal = false"
                @show-display="handleButtonEvent('Mes tarifs')"
            />
            <ModalAddPlanning
                :errors="errors"
                :structure="structure"
                :structure-activites="structureActivites"
                :show="showAddPlanningModal"
                @close="showAddPlanningModal = false"
                @show-display="handleButtonEvent('Planning')"
            />
        </template>
    </ProLayout>
</template>

<style>
.vc-select select {
    background-image: unset;
}
</style>
