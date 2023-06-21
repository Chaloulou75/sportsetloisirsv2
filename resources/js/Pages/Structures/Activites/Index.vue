<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";
import { TrashIcon } from "@heroicons/vue/24/outline";

const AutocompleteActiviteFormSmall = defineAsyncComponent(() =>
    import("@/Components/Inscription/AutocompleteActiviteFormSmall.vue")
);

const ModalDeleteDiscipline = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalDeleteDiscipline.vue")
);

const ModalDeleteCategorie = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalDeleteCategorie.vue")
);

const props = defineProps({
    activites: Object,
    actByDiscAndCategorie: Object,
    structure: Object,
    categories: Object,
    dejaUsedDisciplines: Array,
    listDisciplines: Object,
    can: Object,
});

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

    <AppLayout>
        <template #header>
            <div
                class="flex flex-col items-start justify-between md:flex-row md:items-center"
            >
                <div>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Ajouter, visualiser, et gérer vos activités à
                        <span class="text-blue-700">{{ structure.name }}</span>
                    </h2>
                </div>
                <div class="mt-4 w-full md:mt-0 md:w-1/4">
                    <div
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="route('structures.show', structure.slug)"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-3 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Voir la structure</Link
                        >
                        <Link
                            :href="route('structures.edit', structure.slug)"
                            v-if="can.update"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Editer la structure</Link
                        >
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <form @submit.prevent="submit" autocomplete="off">
                        <div
                            class="min-h-screen shadow-md shadow-sky-700 sm:overflow-hidden sm:rounded-md"
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
                                            (form.discipline_id = discipline)
                                    "
                                />
                                <!-- disciplines similaires -->
                                <section
                                    v-if="activiteSimilairesList.length > 0"
                                    class="mx-auto md:w-1/2"
                                >
                                    <h2
                                        class="mb-4 text-lg font-medium text-gray-700"
                                    >
                                        Les disciplines similaires
                                    </h2>
                                    <div
                                        class="grid auto-cols-auto grid-flow-col gap-4 text-gray-700"
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
                                            class="flex flex-col items-center justify-center overflow-hidden rounded px-2 py-4 text-center text-lg shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                                        >
                                            <button
                                                type="button"
                                                @click="
                                                    updateDiscipline(discipline)
                                                "
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
                                    <span class="text-base italic text-gray-600"
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
                                                    >{{ categorie.pivot.nom_categorie_pro }}</span
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
                                class="mx-auto my-4 max-w-7xl space-y-4 px-2 py-6 sm:px-4 lg:px-8"
                            >
                                <h2 class="text-xl font-bold">Les activités</h2>
                                <div
                                    v-for="(
                                        activite, index
                                    ) in actByDiscAndCategorie"
                                    :key="activite.id"
                                    :index="index"
                                    class="flex w-full flex-col justify-between space-y-4 rounded-lg border border-gray-200 px-2 py-4 text-gray-700 shadow-md md:flex-row md:space-y-0"
                                >
                                    <div
                                        class="flex w-full flex-col justify-start space-y-2"
                                    >
                                        <div
                                            v-if="activite"
                                            class="flex w-full items-center justify-between px-2 text-lg font-semibold text-gray-700"
                                        >
                                            <p>
                                                {{ activite.disciplineName }}
                                                <span
                                                    v-if="activite.count > 1"
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
                                                    openDeleteModal(activite)
                                                "
                                            >
                                                <span class="sr-only"
                                                    >supprimer discipline</span
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
                                                class="flex flex-col items-center justify-between rounded bg-white px-4 py-3 text-lg text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
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
                                                    class="flex flex-col items-center justify-center"
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
                                                        class="mr-1 h-6 w-6"
                                                    />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </form>
                </div>

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
    </AppLayout>
</template>
