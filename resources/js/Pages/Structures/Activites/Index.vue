<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";
import { TrashIcon } from "@heroicons/vue/24/outline";

const AutocompleteActiviteFormSmall = defineAsyncComponent(() =>
    import("@/Components/Inscription/AutocompleteActiviteFormSmall.vue")
);

const MicroNavActiviteBackPro = defineAsyncComponent(() =>
    import("@/Components/Structures/MicroNavActiviteBackPro.vue")
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
const openDeleteModal = (activite) => {
    showDeleteDisciplineModal.value = true;
    currentActivite.value = activite;
};

const currentCategorie = ref(null);
const showDeleteCategorieModal = ref(false);
const openDeleteCategorieModal = (categorie) => {
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
            <h1
                class="text-center text-lg font-semibold text-indigo-700 md:text-left md:text-2xl md:font-bold"
            >
                Activités
            </h1>
        </template>
        <template #default>
            <MicroNavActiviteBackPro @eventFromChild="handleButtonEvent" />
            <div
                class="relative my-4 flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
            >
                <div class="mx-auto max-w-full flex-1 lg:px-4">
                    <template v-if="displayActivites">
                        <form @submit.prevent="submit" autocomplete="off">
                            <div
                                class="min-h-screen shadow sm:overflow-hidden sm:rounded-md"
                            >
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

                                <section
                                    v-if="activites.length > 0"
                                    class="mx-auto my-4 max-w-full space-y-4 px-2 py-6 sm:px-4 lg:px-8"
                                >
                                    <h2 class="text-xl font-bold text-gray-700">
                                        Vos activités
                                    </h2>
                                    <div
                                        v-for="(
                                            activite, index
                                        ) in actByDiscAndCategorie"
                                        :key="activite.id"
                                        :index="index"
                                        class="flex w-full flex-col justify-between space-y-4 rounded-lg border border-gray-200 bg-gray-50 px-2 py-4 text-gray-700 shadow-md md:flex-row md:space-y-0"
                                    >
                                        <div
                                            class="flex w-full flex-col justify-start space-y-2"
                                        >
                                            <div
                                                v-if="activite"
                                                class="flex w-full items-center justify-between px-2 text-lg font-semibold text-gray-700"
                                            >
                                                <p>
                                                    {{
                                                        activite.disciplineName
                                                    }}
                                                    <span
                                                        v-if="
                                                            activite.count > 1
                                                        "
                                                        class="text-sm text-gray-600"
                                                        >({{ activite.count }}
                                                        activités liées)
                                                    </span>
                                                    <span
                                                        v-else
                                                        class="text-sm text-gray-600"
                                                        >({{
                                                            activite.count
                                                        }}
                                                        activité liée)</span
                                                    >
                                                </p>
                                                <button
                                                    type="button"
                                                    @click="
                                                        openDeleteModal(
                                                            activite
                                                        )
                                                    "
                                                >
                                                    <span class="sr-only"
                                                        >supprimer
                                                        discipline</span
                                                    >
                                                    <TrashIcon
                                                        class="mr-1 h-6 w-6 text-gray-600 hover:text-red-600"
                                                    />
                                                </button>
                                            </div>
                                            <div
                                                v-if="activite.categories"
                                                class="grid grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 lg:grid-cols-4"
                                            >
                                                <div
                                                    class="flex flex-col items-center justify-between rounded bg-white px-4 py-2 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-indigo-700 hover:text-white hover:ring-2 hover:ring-green-400 sm:rounded-md"
                                                    v-for="(
                                                        categorie, index
                                                    ) in activite.categories"
                                                    :key="categorie.id"
                                                >
                                                    <Link
                                                        :href="
                                                            route(
                                                                'structures.activites.edit',
                                                                {
                                                                    structure:
                                                                        structure.slug,
                                                                    activite:
                                                                        categorie.categorie_id,
                                                                }
                                                            )
                                                        "
                                                        class="flex flex-col items-center justify-center text-center"
                                                    >
                                                        {{ categorie.name }}

                                                        <span class="text-sm">
                                                            ({{
                                                                categorie.count
                                                            }})</span
                                                        >
                                                    </Link>
                                                    <button
                                                        type="button"
                                                        @click="
                                                            openDeleteCategorieModal(
                                                                categorie
                                                            )
                                                        "
                                                        class="self-end text-gray-600 hover:text-white"
                                                    >
                                                        <span class="sr-only"
                                                            >supprimer
                                                            categorie</span
                                                        >
                                                        <TrashIcon
                                                            class="mr-1 h-5 w-5"
                                                        />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </form>
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

                    <div class="hidden sm:block" aria-hidden="true">
                        <div class="py-5">
                            <div class="border-t border-gray-200" />
                        </div>
                    </div>
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
        </template>
    </ProLayout>
</template>
