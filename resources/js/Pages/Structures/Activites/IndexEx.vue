<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";
import { ChevronLeftIcon, PlusIcon } from "@heroicons/vue/24/outline";

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
    tarifTypes: Object,
    activiteForTarifs: Object,
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
    structure_id: ref(props.structure.id),
    discipline_id: ref(null),
    categories_id: ref([]),
});

const categoriesList = ref([]);
const activiteSimilairesList = ref([]);
const selectedDiscipline = ref(null);
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
);

const removeDiscipline = (disciplineId) => {
    dejaUsedDisciplinesRef.value = dejaUsedDisciplinesRef.value.filter(
        (id) => id !== disciplineId
    );
    selectedDiscipline.value = "";
};

const submit = () => {
    const url = `/structures/${props.structure.slug}/activites`;
    form.post(
        url,
        {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        },
        props.structure
    );
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
        :allReservationsCount="allReservationsCount"
        :pendingReservationsCount="pendingReservationsCount"
        :confirmedReservationsCount="confirmedReservationsCount"
    >
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                    :href="route('structures.gestion.index', structure)"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-2 py-2.5 text-center text-lg font-semibold text-indigo-700 md:px-6 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Disciplines
                </h1>
            </div>
        </template>
        <template #default>
            <MicroNavActiviteBackPro @eventFromChild="handleButtonEvent" />
            <div
                class="relative my-4 flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
            >
                <div class="mx-auto max-w-full flex-1 lg:px-4">
                    <div
                        v-if="displayTarifs"
                        class="flex w-full flex-col items-center justify-end space-y-2 px-2 py-3 md:h-20 md:flex-row md:space-y-0 md:px-0 md:py-6"
                    >
                        <button
                            type="button"
                            @click="openAddTarifModal(structure)"
                            class="w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:flex md:w-auto"
                        >
                            Ajouter un tarif
                            <PlusIcon class="ml-2 h-5 w-5" />
                        </button>
                    </div>
                    <template v-if="displayActivites">
                        <div
                            class="min-h-screen shadow sm:overflow-hidden sm:rounded-md"
                        >
                            <form @submit.prevent="submit" autocomplete="off">
                                <div
                                    class="flex w-full flex-col items-start justify-start bg-white px-4 py-5 md:flex-row md:px-6 md:py-10"
                                >
                                    <!-- discipline -->
                                    <AutocompleteActiviteFormSmall
                                        class="h-full w-full md:w-1/2"
                                        :disciplines="listDisciplines"
                                        :dejaUsedDisciplines="
                                            dejaUsedDisciplinesRef
                                        "
                                        :errors="form.errors"
                                        v-model:discipline="form.discipline_id"
                                        :selectedDiscipline="selectedDiscipline"
                                        @update:modelValue="
                                            (discipline) =>
                                                (form.discipline_id =
                                                    discipline)
                                        "
                                    />
                                </div>

                                <!-- categories -->
                                <div
                                    v-if="form.discipline_id"
                                    class="w-full px-4 py-5 sm:p-6"
                                >
                                    <label
                                        for="categories"
                                        class="mb-4 block text-lg font-medium text-gray-700"
                                    >
                                        Categories d'activité
                                        <span
                                            class="text-base italic text-gray-600"
                                            >(Selectionnez une ou plusieurs
                                            categories)</span
                                        >
                                    </label>
                                    <div class="mt-1">
                                        <ul
                                            class="flex w-full flex-col items-start justify-between rounded-md border border-gray-300 bg-white px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-base md:flex-row md:items-center"
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
                                                        v-model="
                                                            form.categories_id
                                                        "
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
                                    class="px-4 py-3 text-right md:px-6"
                                >
                                    <button
                                        :disabled="form.processing"
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        Enregistrer
                                    </button>
                                </div>
                            </form>

                            <section
                                v-if="activites.length > 0"
                                class="mx-auto my-4 max-w-full space-y-4 px-2 py-6 sm:px-4 lg:px-8"
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
                                        @open-delete-modal="
                                            handleOpenDeleteModal
                                        "
                                        @open-delete-categorie-modal="
                                            handleOpenDeleteCategorieModal
                                        "
                                    />
                                </div>
                            </section>
                        </div>

                        <!-- disciplines similaires -->
                        <section
                            v-if="activiteSimilairesList.length > 0"
                            class="mx-auto w-full py-6"
                        >
                            <h2 class="mb-4 text-lg font-medium text-gray-700">
                                Les disciplines similaires
                            </h2>
                            <div
                                class="flex w-full flex-col flex-wrap items-center gap-3 text-gray-700 md:flex-row"
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
                                    class="inline-block w-48 rounded border border-gray-600 px-4 py-3 text-center text-base font-medium text-gray-600 shadow-sm hover:border-gray-100 hover:bg-indigo-500 hover:text-white hover:shadow-lg focus:outline-none focus:ring active:bg-indigo-500"
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
                    </template>

                    <template v-if="displayTarifs">
                        <TarifDisplay
                            :errors="errors"
                            :structure="structure"
                            :tarif-types="tarifTypes"
                            :structureActivites="activites"
                            :activiteForTarifs="activiteForTarifs"
                        />
                    </template>

                    <template v-if="displayPlanning">
                        <PlanningDisplay
                            :errors="errors"
                            :structure="structure"
                            :structureActivites="activites"
                        />
                    </template>
                </div>
            </div>

            <ModalDeleteCategorie
                :structure="structure"
                :categorie="currentCategorie"
                :show="showDeleteCategorieModal"
                @close="showDeleteCategorieModal = false"
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
                :tarif-types="tarifTypes"
                :activiteForTarifs="activiteForTarifs"
                :structureActivites="activites"
                :show="showAddTarifModal"
                @close="showAddTarifModal = false"
            />
        </template>
    </ProLayout>
</template>
