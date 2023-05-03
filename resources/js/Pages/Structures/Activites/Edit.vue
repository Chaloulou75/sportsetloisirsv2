<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import {
    AcademicCapIcon,
    ArrowPathIcon,
    ClockIcon,
    DocumentDuplicateIcon,
    MapPinIcon,
    PlusIcon,
    TrashIcon,
    UsersIcon,
} from "@heroicons/vue/24/solid";
import { Switch } from "@headlessui/vue";

const enabledActif = computed(() => {
    return form.actif.value === 1 ? true : false;
});

const props = defineProps({
    structure: Object,
    activite: Object,
    structureActivite: Object,
    categories: Object,
    categoriesListByDiscipline: Object,
    can: Object,
});

const form = useForm({
    categorie_id: ref(props.activite.categorie_id),
    actif: ref(props.structureActivite.actif),
});

// function submit() {
//     // const structureValue = props.structure.value;
//     const url = `/structures/${props.structure.slug}/activites/${props.activite.id}`;
//     form.patch(
//         url,
//         {
//             preserveScroll: true,
//             onSuccess: () => form.reset(),
//         },
//         { structure: props.structure, activite: props.activite }
//     );
// }
</script>

<template>
    <Head title="Modifier une activité" />

    <AppLayout>
        <template #header>
            <div
                class="flex flex-col items-start justify-between md:flex-row md:items-center"
            >
                <div>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Modifier votre activité
                        <span class="text-blue-700"></span>
                    </h2>
                </div>
                <div class="mt-4 w-full md:mt-0 md:w-1/4">
                    <div
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="
                                route('structures.activites.index', structure)
                            "
                            v-if="can.update"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Mes activités</Link
                        >
                        <Link
                            :href="route('structures.show', structure.slug)"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Voir la structure</Link
                        >
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <section
                    class="mx-auto max-w-7xl space-y-4 px-2 py-6 text-gray-700"
                >
                    <div>
                        <h2 class="text-3xl font-bold uppercase">
                            {{ activite.discipline.name }}
                        </h2>
                        <!-- categories -->
                        <div class="mt-4 w-full">
                            <label
                                for="categorie_id"
                                class="mb-4 block text-sm font-medium text-gray-700"
                            >
                                Categories
                            </label>
                            <div class="mt-1">
                                <ul
                                    class="flex w-full flex-col items-start justify-between rounded-md border border-gray-300 bg-white px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-base md:flex-row md:items-center"
                                >
                                    <li
                                        v-for="categorie in categoriesListByDiscipline"
                                        :key="categorie.id"
                                        class="py-2"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <input
                                                name="categorie_id"
                                                id="categorie_id"
                                                type="radio"
                                                v-model="form.categorie_id"
                                                :value="categorie.id"
                                                class="form-radio h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                            />
                                            <span class="ml-2 text-gray-700">
                                                {{ categorie.nom_categorie }}
                                            </span>
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
                    </div>

                    <div
                        v-if="activite"
                        class="text-lg font-medium text-gray-500"
                    >
                        <h3>
                            {{ activite.categorie.nom_categorie }}
                        </h3>
                    </div>
                    <!-- buttons -->
                    <div
                        class="flex flex-col items-start justify-start space-x-0 space-y-3 md:flex-row md:space-y-0 md:space-x-4"
                    >
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
                        >
                            Ajouter {{ activite.categorie.nom_categorie }}
                            <PlusIcon class="ml-2 h-5 w-5" />
                        </button>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
                        >
                            Voir le planning
                        </button>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
                        >
                            Ajouter un tarif
                        </button>
                    </div>
                </section>

                <section
                    class="mx-auto flex max-w-7xl flex-col space-y-4 px-2 py-6 text-gray-700"
                >
                    <div
                        class="flex w-full items-center justify-between md:px-6"
                    >
                        <div class="text-lg font-semibold text-gray-700">
                            {{ activite.categorie.nom_categorie }}
                        </div>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
                        >
                            Ajouter
                            <PlusIcon class="ml-2 h-5 w-5" />
                        </button>
                    </div>
                    <div
                        class="flex h-full w-full flex-col space-y-3 rounded border border-gray-200"
                    >
                        <h2
                            class="bg-gray-700 px-4 py-4 font-semibold text-white"
                        >
                            {{ activite.categorie.nom_categorie }} de
                            {{ activite.discipline.name }}
                        </h2>
                        <div class="flex w-full items-start">
                            <div
                                class="relative h-56 w-56 border border-gray-100"
                            >
                                <img
                                    alt="logo"
                                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                                    class="absolute inset-0 h-full w-full object-cover"
                                />
                            </div>
                            <div
                                class="flex flex-1 flex-col items-start space-y-6 px-2 py-2 md:px-4"
                            >
                                <div class="flex items-center space-x-2">
                                    <Switch
                                        v-model="form.actif"
                                        :class="
                                            form.actif
                                                ? 'bg-green-600'
                                                : 'bg-gray-200'
                                        "
                                        class="relative inline-flex h-6 w-11 items-center rounded-full"
                                    >
                                        <span class="sr-only">Actif</span>
                                        <span
                                            :class="
                                                form.actif
                                                    ? 'translate-x-6'
                                                    : 'translate-x-1'
                                            "
                                            class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                                        />
                                    </Switch>
                                    <p
                                        class="text-lg font-semibold text-green-600"
                                        v-if="form.actif"
                                    >
                                        Actif
                                    </p>
                                    <p
                                        class="text-lg font-semibold text-gray-600"
                                        v-else
                                    >
                                        Inactif
                                    </p>
                                </div>
                                <p class="whitespace-pre-line text-lg">
                                    <span class="text-lg font-semibold"
                                        >Description:</span
                                    >
                                    {{ activite.structure.presentation_courte }}
                                </p>
                            </div>
                        </div>
                        <h3
                            class="bg-gray-700 px-4 py-4 font-semibold text-white"
                        >
                            XX Produits / declinaisons
                        </h3>

                        <div class="grid grid-cols-6 place-items-center">
                            <div class="col-span-1 flex items-center">
                                <UsersIcon
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />Tous Public
                            </div>
                            <div class="col-span-1 flex items-center">
                                <AcademicCapIcon
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />
                                Tous Niveaux
                            </div>
                            <div class="col-span-1 flex items-center p-0.5">
                                <MapPinIcon
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />
                                <div class="flex flex-col items-center">
                                    {{ structure.adresse.address }},
                                    {{ structure.adresse.zip_code }}
                                    {{ structure.adresse.city }}
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <ClockIcon class="mr-1 h-6 w-6 text-gray-600" />
                                Planning
                            </div>
                            <div class="col-span-1 flex items-center">
                                <button
                                    type="button"
                                    class="flex w-full items-center justify-between rounded bg-green-600 px-3 py-2 text-sm text-white shadow-lg transition duration-100 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm"
                                >
                                    Tarifs
                                </button>
                            </div>
                            <div
                                class="col-span-1 flex items-center justify-between space-x-2"
                            >
                                <ArrowPathIcon
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />
                                <DocumentDuplicateIcon
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />
                                <TrashIcon class="mr-1 h-6 w-6 text-gray-600" />
                            </div>
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
