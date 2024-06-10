<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
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
    uniqueCriteresInProducts: Object,
    criteres: Object,
    // activiteForTarifs: Object,
    strCatTarifs: Object,
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

const AddButtonsDisplay = defineAsyncComponent(() =>
    import("@/Components/Inscription/Activity/AddButtonsDisplay.vue")
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

const handleOpenAddModal = (message) => {
    switch (message) {
        case "add-activite":
            openAddActiviteModal();
            break;
        case "add-planning":
            openAddPlanningModal();
            break;
        case "add-tarif":
            openAddTarifModal();
            break;
        default:
            break;
    }
};

const currentProduit = ref(null);

const showAddActiviteModal = ref(false);
const openAddActiviteModal = () => {
    showAddActiviteModal.value = true;
};

const showAddTarifModal = ref(false);
const openAddTarifModal = (produit) => {
    currentProduit.value = produit;
    showAddTarifModal.value = true;
};

const showAddPlanningModal = ref(false);
const openAddPlanningModal = (produit) => {
    currentProduit.value = produit;
    showAddPlanningModal.value = true;
};

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
                    class="hidden h-full bg-blue-600 py-2.5 hover:bg-blue-700 md:flex md:px-4 md:py-4"
                    :href="route('structures.disciplines.index', structure)"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <div
                    class="flex h-full w-full flex-col items-center md:flex-row md:justify-between"
                >
                    <Link
                        :href="
                            route('structures.disciplines.show', {
                                structure: props.structure,
                                discipline: props.discipline.slug,
                            })
                        "
                        class="shrink-0 px-6 py-2.5 text-center text-lg font-semibold text-indigo-700 hover:text-indigo-900 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                    >
                        {{ discipline.name }}
                    </Link>
                    <div
                        class="w-full space-y-1 md:flex md:flex-1 md:items-center md:justify-start md:space-x-1.5 md:space-y-0"
                    >
                        <Link
                            :href="
                                route('structures.categories.show', {
                                    structure: structure.slug,
                                    discipline: discipline.slug,
                                    categorie: category.slug,
                                })
                            "
                            v-for="category in structure.categories"
                            :key="category.id"
                            class="flex h-full w-full flex-col items-center bg-white px-6 py-2.5 text-xs text-slate-500 ring ring-green-500/80 hover:bg-gray-50 hover:text-slate-700 md:w-auto md:py-4"
                        >
                            <AcademicCapIcon
                                class="hidden h-6 w-6 md:inline-flex"
                            />
                            <div>
                                {{ category.nom_categorie_pro }}
                            </div>
                        </Link>
                    </div>

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
                                <template
                                    v-for="discipline in structure.disciplines"
                                    :key="discipline.id"
                                >
                                    <BreezeDropdownLink
                                        :href="
                                            route(
                                                'structures.categories.show',
                                                {
                                                    structure: structure.slug,
                                                    discipline: discipline.slug,
                                                    categorie:
                                                        category.pivot.slug,
                                                }
                                            )
                                        "
                                        v-for="category in discipline.categories"
                                        :key="category.id"
                                    >
                                        {{ category.pivot.nom_categorie_pro }}
                                    </BreezeDropdownLink>
                                </template>
                            </template>
                        </BreezeDropdown>
                    </div>
                </div>
            </div>
        </template>
        <template #default>
            <MicroNavActiviteBackPro
                :discipline="discipline"
                @eventFromChild="handleButtonEvent"
            />
            <div
                class="relative flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
            >
                <div
                    class="mx-auto max-w-full flex-1 space-y-8 px-1 md:space-y-16 lg:px-4"
                >
                    <AddButtonsDisplay
                        :display-activity="displayActivites"
                        :display-tarif="displayTarifs"
                        :display-planning="displayPlanning"
                        :discipline="discipline"
                        @open-add-modal="handleOpenAddModal"
                    />
                    <template v-if="displayActivites">
                        <ActivityDisplay
                            v-for="activite in structure.activites"
                            :key="activite.id"
                            :errors="errors"
                            :structure="structure"
                            :activite="activite"
                            :unique-criteres-in-products="
                                uniqueCriteresInProducts
                            "
                            :criteres="criteres"
                            :latest-adresse-id="latestAdresseId"
                            @add-tarif="openAddTarifModal"
                            @add-planning="openAddPlanningModal"
                        />
                        <div v-if="structure.activites.length === 0">
                            <p class="font-semibold italic text-gray-600">
                                Pas d'activité dans cette catégorie
                            </p>
                        </div>
                    </template>
                    <template v-if="displayPlanning">
                        <PlanningDisplay
                            :errors="errors"
                            :structure="structure"
                            @show-display="handleButtonEvent('Planning')"
                        />
                    </template>
                    <template v-if="displayTarifs">
                        <TarifDisplay
                            :errors="errors"
                            :structure="structure"
                            :discipline="discipline"
                            :str-cat-tarifs="strCatTarifs"
                            @show-display="handleButtonEvent('Mes tarifs')"
                        />
                    </template>
                </div>
            </div>
            <ModalAddActivite
                :errors="errors"
                :structure="structure"
                :discipline="discipline"
                :criteres="criteres"
                :show="showAddActiviteModal"
                @close="showAddActiviteModal = false"
            />
            <ModalAddTarif
                :errors="errors"
                :structure="structure"
                :discipline="discipline"
                :produit="currentProduit"
                :all-categories="categoriesListByDiscipline"
                :activite-for-tarifs="activiteForTarifs"
                :structure-activites="structure.activites"
                :show="showAddTarifModal"
                @close="showAddTarifModal = false"
                @show-display="handleButtonEvent('Mes tarifs')"
            />
            <ModalAddPlanning
                :errors="errors"
                :structure="structure"
                :structure-activites="structure.activites"
                :produit="currentProduit"
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
