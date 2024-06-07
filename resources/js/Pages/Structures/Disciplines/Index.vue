<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";
import { ChevronLeftIcon, PlusIcon } from "@heroicons/vue/24/outline";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const AutocompleteActiviteFormSmall = defineAsyncComponent(() =>
    import("@/Components/Inscription/AutocompleteActiviteFormSmall.vue")
);

const MicroNavActiviteBackPro = defineAsyncComponent(() =>
    import("@/Components/Structures/MicroNavActiviteBackPro.vue")
);

const DisciplineCardNew = defineAsyncComponent(() =>
    import("@/Components/Structures/DisciplineCardNew.vue")
);

const TarifDisplay = defineAsyncComponent(() =>
    import("@/Components/Inscription/Activity/TarifDisplay.vue")
);

const PlanningDisplay = defineAsyncComponent(() =>
    import("@/Components/Inscription/Activity/PlanningDisplay.vue")
);

const ModalDeleteDiscipline = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalDeleteDiscipline.vue")
);

const ModalDeleteCategorie = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalDeleteCategorie.vue")
);

const ModalAddTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalAddTarif.vue")
);

const ModalAddPlanning = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalAddPlanning.vue")
);

const props = defineProps({
    errors: Object,
    strCatTarifs: Object,
    structure: Object,
    dejaUsedDisciplines: Array,
    listDisciplines: Object,
    confirmedReservationsCount: Number,
    allReservationsCount: Number,
    pendingReservationsCount: Number,
    can: Object,
});

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

const form = useForm({
    structure_id: props.structure.id,
    discipline_id: null,
    categories_id: [],
});

const categoriesList = ref([]);
const activiteSimilairesList = ref([]);
const selectedDiscipline = ref({});

const currentDiscipline = ref(null);
const showDeleteDisciplineModal = ref(false);
const handleOpenDeleteModal = (discipline) => {
    showDeleteDisciplineModal.value = true;
    currentDiscipline.value = discipline;
};

const currentCategorie = ref(null);
const showDeleteCategorieModal = ref(false);
const handleOpenDeleteCategorieModal = (categorie) => {
    showDeleteCategorieModal.value = true;
    currentCategorie.value = categorie;
};

const updateDiscipline = (discipline) => {
    form.discipline_id = discipline.id;
    selectedDiscipline.value = discipline;
};

watch(
    () => form.discipline_id,
    async (newDisciplineID) => {
        if (newDisciplineID) {
            axios
                .get("/api/listdisciplines/" + newDisciplineID)
                .then((response) => {
                    categoriesList.value = response.data;
                })
                .catch((e) => {
                    console.log(e);
                });
            axios
                .get("/api/listdisciplines_similaires/" + newDisciplineID)
                .then((response) => {
                    activiteSimilairesList.value = response.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        }
    }
);

const refreshPage = () => {
    router.reload({ only: ["structure", "dejaUsedDisciplines"] });
    selectedDiscipline.value = null;
};

const submit = () => {
    form.post(route("structures.activites.store", props.structure.slug), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const showAddTarifModal = ref(false);
const openAddTarifModal = (structure) => {
    showAddTarifModal.value = true;
};

const showAddPlanningModal = ref(false);
const openAddPlanningModal = () => {
    showAddPlanningModal.value = true;
};
</script>

<template>
    <Head title="Ajouter, visualiser et gérer vos activités" />

    <ProLayout
        :structure="structure"
        :can="can"
        :all-reservations-count="allReservationsCount"
        :pending-reservations-count="pendingReservationsCount"
        :confirmed-reservations-count="confirmedReservationsCount"
    >
        <template #header>
            <div
                class="flex h-full w-full items-center justify-center md:justify-start"
            >
                <Link
                    class="hidden h-full bg-blue-600 py-2.5 md:block md:px-4 md:py-4"
                    :href="route('structures.gestion.index', structure)"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="shrink-0 px-3 py-2.5 text-center text-lg font-semibold text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Disciplines
                </h1>
            </div>
        </template>
        <template #default>
            <MicroNavActiviteBackPro @event-from-child="handleButtonEvent" />
            <div class="relative my-4 flex flex-col md:flex-row md:space-y-0">
                <div class="mx-auto max-w-full flex-1 lg:px-4">
                    <template v-if="displayActivites">
                        <form
                            class="space-y-6 px-2 py-2 sm:px-4 md:px-6 md:py-4 lg:px-8"
                            @submit.prevent="submit"
                            autocomplete="off"
                        >
                            <!-- discipline -->
                            <AutocompleteActiviteFormSmall
                                class="w-full md:w-1/2"
                                :disciplines="listDisciplines"
                                :deja-used-disciplines="dejaUsedDisciplines"
                                :errors="form.errors"
                                v-model:discipline="form.discipline_id"
                                :selected-discipline="selectedDiscipline"
                                @update:modelValue="
                                    (discipline) =>
                                        (form.discipline_id = discipline)
                                "
                            />

                            <!-- categories -->
                            <div
                                v-if="
                                    form.discipline_id &&
                                    categoriesList.length > 0
                                "
                                class="w-full"
                            >
                                <label
                                    for="categories"
                                    class="mb-4 block text-lg font-medium text-gray-700"
                                >
                                    Categories d'activité
                                    <span class="text-base italic text-gray-600"
                                        >(Selectionnez une ou plusieurs
                                        categories)</span
                                    >
                                </label>
                                <div class="mt-1">
                                    <ul
                                        class="flex w-full flex-col items-start justify-between rounded-md border border-gray-300 bg-white px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm md:flex-row md:items-center md:space-x-2"
                                    >
                                        <li
                                            v-for="categorie in categoriesList"
                                            :key="categorie.id"
                                            class="py-2"
                                        >
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <input
                                                    name="categories"
                                                    id="categories"
                                                    v-model="form.categories_id"
                                                    type="checkbox"
                                                    multiple
                                                    :value="categorie.id"
                                                    :checked="
                                                        form.categories_id.includes(
                                                            categorie.id
                                                        )
                                                    "
                                                    class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                />
                                                <span
                                                    class="ml-2 text-gray-700"
                                                    >{{
                                                        categorie.pivot
                                                            .nom_categorie_pro
                                                    }}</span
                                                >
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div
                                    v-if="form.errors.categories_id"
                                    class="mt-2 text-xs text-red-500"
                                >
                                    {{ form.errors.categories_id }}
                                </div>
                            </div>

                            <!--buttons -->
                            <div
                                v-if="form.categories_id.length > 0"
                                class="py-3 text-right"
                            >
                                <button
                                    :disabled="form.processing"
                                    :class="{
                                        'opacity-25': form.processing,
                                    }"
                                    type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    <LoadingSVG v-if="form.processing" />
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                        <!-- disciplines similaires -->
                        <section
                            v-if="activiteSimilairesList.length > 0"
                            class="mx-auto w-full px-2 py-2 sm:px-4 md:py-4 lg:px-8"
                        >
                            <h2 class="mb-4 text-lg font-medium text-gray-700">
                                Les disciplines similaires
                            </h2>
                            <div
                                class="flex w-full flex-col flex-wrap items-stretch gap-3 text-gray-700 md:flex-row"
                            >
                                <div
                                    v-for="discipline in activiteSimilairesList"
                                    :key="discipline.id"
                                    :index="discipline.id"
                                    :class="{
                                        'pointer-events-none text-gray-400':
                                            dejaUsedDisciplines.includes(
                                                discipline.id
                                            ),
                                    }"
                                    class="inline-flex w-auto items-center justify-center rounded border border-gray-600 px-4 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                >
                                    <button
                                        type="button"
                                        @click="updateDiscipline(discipline)"
                                    >
                                        {{ discipline.name }}
                                        <span
                                            v-if="
                                                dejaUsedDisciplines.includes(
                                                    discipline.id
                                                )
                                            "
                                            class="text-xs italic text-gray-400"
                                            >(déjà sélectionné)</span
                                        >
                                    </button>
                                </div>
                            </div>
                        </section>
                        <section
                            v-if="structure.disciplines.length > 0"
                            class="mx-auto my-4 max-w-full space-y-4 px-2 sm:px-4 lg:px-8"
                        >
                            <h2 class="text-xl font-bold text-gray-700">
                                Vos activités
                            </h2>

                            <div
                                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3"
                            >
                                <DisciplineCardNew
                                    v-for="discipline in structure.disciplines"
                                    :key="discipline.id"
                                    :discipline="discipline"
                                    :structure="structure"
                                    @open-delete-modal="handleOpenDeleteModal"
                                    @open-delete-categorie-modal="
                                        handleOpenDeleteCategorieModal
                                    "
                                />
                            </div>
                        </section>
                    </template>
                    <template v-if="displayPlanning">
                        <div
                            class="mb-4 flex w-full items-center justify-center px-4 py-3 md:justify-end"
                        >
                            <button
                                type="button"
                                @click="openAddPlanningModal()"
                                class="inline-flex w-auto items-center justify-between bg-green-500 px-3 py-3 text-sm text-white shadow-lg ring-2 ring-green-500 transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:text-lg"
                            >
                                Ajouter un créneau
                                <PlusIcon class="h-5 w-5 md:ml-2" />
                            </button>
                        </div>
                        <PlanningDisplay
                            :errors="errors"
                            :structure="structure"
                        />
                    </template>
                    <template v-if="displayTarifs">
                        <div
                            class="mb-4 flex w-full items-center justify-center px-4 py-3 md:justify-end"
                        >
                            <button
                                type="button"
                                @click="openAddTarifModal(structure)"
                                class="inline-flex w-auto items-center justify-between bg-green-500 px-3 py-3 text-sm text-white shadow-lg ring-2 ring-green-500 transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 md:flex md:text-lg"
                            >
                                Ajouter un tarif
                                <PlusIcon class="h-5 w-5 md:ml-2" />
                            </button>
                        </div>
                        <TarifDisplay
                            :errors="errors"
                            :structure="structure"
                            :str-cat-tarifs="strCatTarifs"
                            @show-display="handleButtonEvent('Mes tarifs')"
                        />
                    </template>
                </div>
            </div>

            <ModalDeleteCategorie
                :structure="structure"
                :categorie="currentCategorie"
                :show="showDeleteCategorieModal"
                @close="showDeleteCategorieModal = false"
                @delete-categorie="refreshPage"
            />
            <ModalDeleteDiscipline
                :structure="structure"
                :discipline="currentDiscipline"
                :show="showDeleteDisciplineModal"
                @close="showDeleteDisciplineModal = false"
                @delete-discipline="refreshPage"
            />
            <ModalAddTarif
                :errors="errors"
                :structure="structure"
                :show="showAddTarifModal"
                @close="showAddTarifModal = false"
                @show-display="handleButtonEvent('Mes tarifs')"
            />
            <ModalAddPlanning
                :errors="errors"
                :structure="structure"
                :show="showAddPlanningModal"
                @close="showAddPlanningModal = false"
                @show-display="handleButtonEvent('Planning')"
            />
        </template>
    </ProLayout>
</template>
