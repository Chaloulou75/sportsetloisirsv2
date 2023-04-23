<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";

const AutocompleteActiviteFormSmall = defineAsyncComponent(() =>
    import("@/Components/Inscription/AutocompleteActiviteFormSmall.vue")
);

const props = defineProps({
    // disciplines: Object,
    niveaux: Object,
    publictypes: Object,
    activitestypes: Object,
    structure: Object,
    categories: Object,
    listDisciplines: Object,
    can: Object,
});

const form = useForm({
    structure_id: ref(props.structure.id),
    discipline_id: ref(null),
    categories_id: ref([]),
    nivel_id: ref(1),
    publictype_id: ref(1),
});

const categoriesList = ref([]);

const activiteSimilairesList = ref([]);

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

function submit() {
    // const structureValue = props.structure.value;
    const url = `/structures/${props.structure.slug}/activites`;
    form.post(
        url,
        {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        },
        props.structure
    );
}
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
                        Ajouter, visualiser, gérer et vos activités à
                        <span class="text-blue-700">{{ structure.name }}</span>
                    </h2>
                </div>
                <div class="w-full mt-4 md:mt-0 md:w-1/4">
                    <div
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="route('structures.show', structure.slug)"
                            class="flex flex-col items-center justify-center px-4 py-2 overflow-hidden text-xs text-center text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Voir la structure</Link
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
                                class="flex items-center justify-around w-full px-4 py-5 bg-white sm:p-6"
                            >
                                <!-- discipline -->
                                <AutocompleteActiviteFormSmall
                                    class="w-full h-full"
                                    :disciplines="listDisciplines"
                                    :errors="form.errors"
                                    v-model:discipline="form.discipline_id"
                                    @update:modelValue="
                                        (discipline) =>
                                            (form.discipline_id = discipline)
                                    "
                                />
                                <button
                                    type="button"
                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Ajouter
                                </button>
                            </div>

                            <!-- categories -->
                            <div
                                v-if="form.discipline_id"
                                class="w-full px-4 py-5 sm:p-6"
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
                                    <select
                                        multiple
                                        name="categories"
                                        id="categories"
                                        v-model="form.categories_id"
                                        class="flex items-center justify-between w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-md focus:outline-nonefocus:ring-indigo-500 focus:border-indigo-500 sm:text-base"
                                    >
                                        <option
                                            class="h-full px-3 py-3 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-base"
                                            v-for="categorie in categoriesList"
                                            :key="categorie.id"
                                            :value="categorie.id"
                                        >
                                            {{ categorie.nom }}
                                        </option>
                                    </select>
                                </div>
                                <!-- <div class="mt-2 text-xs text-gray-700">
                                    Categories sélectionnées:
                                    <span
                                        v-for="categorie in form.categories_id"
                                        :key="categorie.id"
                                        class="ml-1 text-sm font-semibold text-gray-700"
                                        >{{ categorie.nom }}
                                        &bullet;
                                    </span>
                                </div> -->
                                <div
                                    v-if="form.errors.categories_id"
                                    class="mt-2 text-xs text-red-500"
                                >
                                    {{ form.errors.categories_id }}
                                </div>
                            </div>

                            <!--buttons -->
                            <div class="px-4 py-3 text-right sm:px-6">
                                <button
                                    :disabled="form.processing"
                                    type="submit"
                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Enregistrer
                                </button>
                            </div>
                            <section
                                v-if="activiteSimilairesList.length > 0"
                                class="px-2 py-6 mx-auto my-4 sm:px-4 lg:px-8"
                            >
                                <h2
                                    class="mb-4 text-lg font-medium text-gray-700"
                                >
                                    Les activités similaires
                                </h2>
                                <div
                                    class="grid grid-flow-col gap-4 px-4 py-8 border-gray-300 shadow-md auto-cols-auto"
                                >
                                    <div
                                        v-for="activite in activiteSimilairesList"
                                        :key="activite.id"
                                        :index="activite.id"
                                        class="px-2 py-4 text-gray-700 border-gray-600 rounded-lg shadow-lg"
                                    >
                                        <div class="flex flex-col items-center">
                                            <p class="text-base font-medium">
                                                {{ activite.name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section
                                v-if="structure.activites.length > 0"
                                class="px-2 py-6 mx-auto my-4 space-y-4 max-w-7xl sm:px-4 lg:px-8"
                            >
                                <h2 class="text-xl font-bold">Les activités</h2>
                                <div
                                    v-for="(
                                        activite, index
                                    ) in structure.activites"
                                    :key="activite.id"
                                    :index="index"
                                    class="flex flex-col justify-between w-full px-2 py-4 space-y-4 text-gray-800 border border-gray-500 rounded-lg shadow-md md:flex-row md:space-y-0"
                                >
                                    <div
                                        class="flex flex-col justify-start w-full md:w-1/3"
                                    >
                                        <p class="text-base font-semibold">
                                            {{ activite.discipline.name }}
                                        </p>
                                    </div>
                                    <div
                                        class="flex flex-col items-center justify-end px-4 space-y-2 md:w:1/3 md:ml-4 md:space-y-6"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'structures.activites.edit',
                                                    {
                                                        structure:
                                                            structure.slug,
                                                        activite: activite,
                                                    }
                                                )
                                            "
                                            v-if="can.update"
                                            class="flex flex-col items-center justify-center w-full px-4 py-2 overflow-hidden text-xs text-center text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                                        >
                                            Editer l'activité
                                        </Link>
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
    </AppLayout>
</template>
