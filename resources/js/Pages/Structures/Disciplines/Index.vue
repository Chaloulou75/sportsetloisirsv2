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

const DisciplineCard = defineAsyncComponent(() =>
    import("@/Components/Structures/DisciplineCard.vue")
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

const props = defineProps({
    errors: Object,
    activites: Object,
    categoriesListByDiscipline: Object,
    activiteForTarifs: Object,
    strCatTarifs: Object,
    actByDiscAndCategorie: Object,
    structure: Object,
    categories: Object,
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
const dejaUsedDisciplinesRef = ref(props.dejaUsedDisciplines);

const currentActivite = ref(null);
const showDeleteDisciplineModal = ref(false);
const handleOpenDeleteModal = (activite) => {
    showDeleteDisciplineModal.value = true;
    currentActivite.value = activite;
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
                    categoriesList.value = response.data.data;
                })
                .catch((e) => {
                    console.log(e);
                });
            axios
                .get("/api/listdisciplines_similaires/" + newDisciplineID)
                .then((response) => {
                    activiteSimilairesList.value = response.data.data;
                })
                .catch((e) => {
                    console.log(e);
                });
        }
    }
);

const removeDiscipline = (disciplineId) => {
    dejaUsedDisciplinesRef.value = dejaUsedDisciplinesRef.value.filter(
        (id) => id !== disciplineId
    );
    selectedDiscipline.value = null;
};

const refreshPage = () => {
    router.reload({ only: [props.dejaUsedDisciplines] });
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
            <div class="flex items-center justify-start h-full">
                <Link
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                    :href="route('structures.gestion.index', structure)"
                >
                    <ChevronLeftIcon class="w-10 h-10 text-white" />
                </Link>
                <h1
                    class="shrink-0 px-3 py-2.5 text-center text-lg font-semibold text-indigo-700 md:px-12 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Disciplines
                </h1>
            </div>
        </template>
        <template #default>
            <MicroNavActiviteBackPro @eventFromChild="handleButtonEvent" />
            <div class="relative flex flex-col my-4 md:flex-row md:space-y-0">
                <div class="flex-1 max-w-full mx-auto lg:px-4">
                    <template v-if="displayActivites">
                        <form
                            class="px-2 py-2 space-y-6 sm:px-4 md:px-6 md:py-4 lg:px-8"
                            @submit.prevent="submit"
                            autocomplete="off"
                        >
                            <!-- discipline -->
                            <AutocompleteActiviteFormSmall
                                class="w-full md:w-1/2"
                                :disciplines="listDisciplines"
                                :deja-used-disciplines="dejaUsedDisciplinesRef"
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
                                    class="block mb-4 text-lg font-medium text-gray-700"
                                >
                                    Categories d'activité
                                    <span class="text-base italic text-gray-600"
                                        >(Selectionnez une ou plusieurs
                                        categories)</span
                                    >
                                </label>
                                <div class="mt-1">
                                    <ul
                                        class="flex flex-col items-start justify-between w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-md focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-base md:flex-row md:items-center"
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
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded form-checkbox focus:ring-blue-500"
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
                                    type="submit"
                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    <LoadingSVG v-if="form.processing" />
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                        <!-- disciplines similaires -->
                        <section
                            v-if="activiteSimilairesList.length > 0"
                            class="w-full px-2 py-2 mx-auto sm:px-4 md:py-4 lg:px-8"
                        >
                            <h2 class="mb-4 text-lg font-medium text-gray-700">
                                Les disciplines similaires
                            </h2>
                            <div
                                class="flex flex-col flex-wrap items-stretch w-full gap-3 text-gray-700 md:flex-row"
                            >
                                <div
                                    v-for="discipline in activiteSimilairesList"
                                    :key="discipline.id"
                                    :index="discipline.id"
                                    :class="{
                                        'pointer-events-none text-gray-400':
                                            dejaUsedDisciplinesRef.includes(
                                                discipline.id
                                            ),
                                    }"
                                    class="inline-flex items-center justify-center w-auto px-4 py-3 text-base font-medium text-center text-gray-600 border border-gray-600 rounded shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
                                >
                                    <button
                                        type="button"
                                        @click="updateDiscipline(discipline)"
                                    >
                                        {{ discipline.name }}
                                        <span
                                            v-if="
                                                dejaUsedDisciplinesRef.includes(
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
                            v-if="activites.length > 0"
                            class="max-w-full px-2 mx-auto my-4 space-y-4 sm:px-4 lg:px-8"
                        >
                            <h2 class="text-xl font-bold text-gray-700">
                                Vos activités
                            </h2>

                            <div
                                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3"
                            >
                                <DisciplineCard
                                    v-for="(
                                        activite, index
                                    ) in actByDiscAndCategorie"
                                    :key="activite.id"
                                    :activite="activite"
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
                        <PlanningDisplay
                            :errors="errors"
                            :structure="structure"
                            :structure-activites="activites"
                        />
                    </template>
                    <template v-if="displayTarifs">
                        <div
                            class="flex flex-col items-center justify-end w-full px-2 py-3 space-y-2 md:my-4 md:h-20 md:flex-row md:space-y-0 md:px-0 md:py-6"
                        >
                            <button
                                type="button"
                                @click="openAddTarifModal(structure)"
                                class="inline-flex items-center justify-between w-auto px-4 py-3 text-lg text-white bg-green-600 rounded-sm hover:bg-green-700 md:flex"
                            >
                                <span class="hidden md:block"
                                    >Ajouter un tarif</span
                                >
                                <PlusIcon class="w-5 h-5 md:ml-2" />
                            </button>
                        </div>
                        <TarifDisplay
                            :errors="errors"
                            :structure="structure"
                            :all-categories="categoriesListByDiscipline"
                            :str-cat-tarifs="strCatTarifs"
                            :structure-activites="activites"
                            :activite-for-tarifs="activiteForTarifs"
                        />
                    </template>
                </div>
            </div>

            <ModalDeleteCategorie
                :structure="structure"
                :categorie="currentCategorie"
                :show="showDeleteCategorieModal"
                @close="showDeleteCategorieModal = false"
                @deleteCategorie="refreshPage"
            />
            <ModalDeleteDiscipline
                :structure="structure"
                :activite="currentActivite"
                :show="showDeleteDisciplineModal"
                @close="showDeleteDisciplineModal = false"
                @deleteDiscipline="removeDiscipline"
            />
            <ModalAddTarif
                :errors="errors"
                :structure="structure"
                :all-categories="categoriesListByDiscipline"
                :activite-for-tarifs="activiteForTarifs"
                :structure-activites="activites"
                :show="showAddTarifModal"
                @close="showAddTarifModal = false"
            />
        </template>
    </ProLayout>
</template>
