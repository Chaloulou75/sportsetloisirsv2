<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, defineAsyncComponent } from "vue";

const AutocompleteActiviteFormSmall = defineAsyncComponent(() =>
    import("@/Components/Inscription/AutocompleteActiviteFormSmall.vue")
);

const props = defineProps({
    disciplines: Object,
    niveaux: Object,
    publictypes: Object,
    activitestypes: Object,
    structure: Object,
    categories: Object,
    can: Object,
});

const form = useForm({
    structure_id: ref(props.structure.id),
    discipline_id: ref(null),
    activitetype_id: ref(null),
    // nivel_id: ref(null),
    // name: ref(null),
    // address: ref(null),
    // city: ref(null),
    // zip_code: ref(null),
    // country: ref(null),
    // address_lat: ref(null),
    // address_lng: ref(null),
    // description: ref(null),
    // publictype_id: ref(null),
});

function showCategorie(id) {
    console.log(id);
}

const categoriesList = ref([]);

watch(
    () => form.discipline_id,
    async (newDisciplineID) => {
        axios
            .get("/api/disciplines/" + newDisciplineID + "/categories")
            .then((response) => {
                categoriesList.value = response.data.data;
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
    <Head title="Ajouter une activité" />

    <AppLayout>
        <template #header>
            <div
                class="flex flex-col items-start justify-between md:flex-row md:items-center"
            >
                <div>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Ajouter une activité à
                        <span class="text-blue-700">{{ structure.name }}</span>
                    </h2>
                </div>
                <!-- <div class="w-full mt-4 md:mt-0 md:w-1/4">
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
                </div> -->
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <form @submit.prevent="submit" autocomplete="off">
                        <div
                            class="shadow-lg shadow-sky-700 sm:overflow-hidden sm:rounded-md"
                        >
                            <div
                                class="flex items-center justify-around w-full px-4 py-5 bg-white sm:p-6"
                            >
                                <!-- discipline -->

                                <AutocompleteActiviteFormSmall
                                    class="w-full"
                                    :disciplines="disciplines"
                                    :errors="form.errors"
                                    v-model:discipline="form.discipline_id"
                                    @update:modelValue="
                                        (discipline) =>
                                            (form.discipline_id = discipline)
                                    "
                                />
                                <button
                                    @click="showCategorie(form.discipline_id)"
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
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Categories d'activité
                                    <span class="text-xs text-gray-600"
                                        >(Selectionnez un ou plusieurs
                                        categories)</span
                                    >
                                </label>
                                <div class="mt-1">
                                    <select
                                        name="categories"
                                        id="categories"
                                        v-model="form.categories"
                                        class="block w-full h-64 text-sm text-gray-800 border-gray-300 rounded-lg shadow-sm"
                                        multiple
                                    >
                                        <option
                                            v-for="categorie in categoriesList"
                                            :key="categorie.id"
                                            :value="categorie"
                                        >
                                            {{ categorie.name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="mt-2 text-xs text-gray-700">
                                    Sports sélectionnés:
                                    <span
                                        v-for="categorie in form.sports"
                                        :key="categorie.id"
                                        class="ml-1 text-sm font-semibold text-gray-700"
                                        >{{ categorie.name }}
                                        &bullet;
                                    </span>
                                </div>
                                <div
                                    v-if="form.errors.categories"
                                    class="mt-2 text-xs text-red-500"
                                >
                                    {{ form.errors.categories }}
                                </div>
                            </div>

                            <!--buttons -->
                            <div
                                class="px-4 py-3 text-right bg-gray-50 sm:px-6"
                            >
                                <button
                                    :disabled="form.processing"
                                    type="submit"
                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <section
                    v-if="structure.activites.length > 0"
                    class="px-2 py-6 mx-auto my-4 space-y-4 max-w-7xl sm:px-4 lg:px-8"
                >
                    <h2 class="text-xl font-bold">Nos activités</h2>
                    <div
                        v-for="(activite, index) in structure.activites"
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
                                    route('structures.activites.edit', {
                                        structure: structure.slug,
                                        activite: activite,
                                    })
                                "
                                v-if="can.update"
                                class="flex flex-col items-center justify-center w-full px-4 py-2 overflow-hidden text-xs text-center text-gray-600 transition duration-150 bg-white rounded shadow-lg hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                            >
                                Editer l'activité
                            </Link>
                        </div>
                    </div>
                </section>

                <div class="hidden sm:block" aria-hidden="true">
                    <div class="py-5">
                        <div class="border-t border-gray-200" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
