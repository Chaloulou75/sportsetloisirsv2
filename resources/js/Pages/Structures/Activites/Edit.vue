<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import ActivityDisplay from "@/Components/Inscription/Activity/ActivityDisplay.vue";
import { PlusIcon } from "@heroicons/vue/24/solid";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";

const props = defineProps({
    errors: Object,
    structure: Object,
    activite: Object,
    structureActivites: Object,
    categories: Object,
    categoriesListByDiscipline: Object,
    can: Object,
});

const isOpen = ref(false);

function closeModal() {
    isOpen.value = false;
}
function openModal() {
    isOpen.value = true;
}

const form = useForm({
    titre: ref(),
    description: ref(),
    image: ref(null),
    actif: ref(1),
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
                        Ajouter ou modifier votre activité
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
                                            <span
                                                class="ml-2 px-2 py-2 text-gray-700 hover:bg-gray-200"
                                            >
                                                {{ categorie.nom_categorie }}
                                            </span>
                                        </div>
                                    </li>
                                </ul>
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
                            @click="openModal"
                            class="flex w-full items-center justify-between rounded bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto"
                        >
                            Ajouter
                            <PlusIcon class="ml-2 h-5 w-5" />
                        </button>
                        <TransitionRoot appear :show="isOpen" as="template">
                            <Dialog
                                as="div"
                                @close="closeModal"
                                class="relative z-10"
                            >
                                <TransitionChild
                                    as="template"
                                    enter="duration-300 ease-out"
                                    enter-from="opacity-0"
                                    enter-to="opacity-100"
                                    leave="duration-200 ease-in"
                                    leave-from="opacity-100"
                                    leave-to="opacity-0"
                                >
                                    <div
                                        class="fixed inset-0 bg-black bg-opacity-50"
                                    />
                                </TransitionChild>

                                <div class="fixed inset-0 overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-center justify-center p-4 text-center"
                                    >
                                        <TransitionChild
                                            as="template"
                                            enter="duration-300 ease-out"
                                            enter-from="opacity-0 scale-95"
                                            enter-to="opacity-100 scale-100"
                                            leave="duration-200 ease-in"
                                            leave-from="opacity-100 scale-100"
                                            leave-to="opacity-0 scale-95"
                                        >
                                            <DialogPanel
                                                class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                                            >
                                                <DialogTitle
                                                    as="h3"
                                                    class="text-lg font-medium leading-6 text-gray-900"
                                                >
                                                    Ajouter une activité
                                                </DialogTitle>
                                                <div class="mt-2 w-full">
                                                    <form
                                                        @submit.prevent="submit"
                                                        enctype="multipart/form-data"
                                                        autocomplete="off"
                                                    >
                                                        <div
                                                            class="flex flex-col space-y-3"
                                                        >
                                                            <div>
                                                                <label
                                                                    for="image"
                                                                    class="block text-sm font-medium text-gray-700"
                                                                    >Ajouter ou
                                                                    modifier la
                                                                    photo ou
                                                                    l'image:</label
                                                                >
                                                                <input
                                                                    class="mt-1 text-sm text-gray-700 focus:outline-none"
                                                                    type="file"
                                                                    id="image"
                                                                    @input="
                                                                        form.image =
                                                                            $event.target.files[0]
                                                                    "
                                                                />
                                                                <span
                                                                    v-if="
                                                                        form
                                                                            .errors
                                                                            .image
                                                                    "
                                                                    class="mt-2 text-xs text-red-500"
                                                                    >{{
                                                                        form
                                                                            .errors
                                                                            .image[0]
                                                                    }}</span
                                                                >
                                                            </div>
                                                            <div>
                                                                <label
                                                                    for="titre"
                                                                    class="block text-sm font-medium text-gray-700"
                                                                >
                                                                    Titre de
                                                                    l'activité
                                                                </label>
                                                                <div
                                                                    class="mt-1 flex rounded-md"
                                                                >
                                                                    <input
                                                                        v-model="
                                                                            form.titre
                                                                        "
                                                                        type="text"
                                                                        name="titre"
                                                                        id="titre"
                                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                        placeholder=""
                                                                        autocomplete="none"
                                                                    />
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label
                                                                    for="description"
                                                                    class="block text-sm font-medium text-gray-700"
                                                                >
                                                                    Description
                                                                </label>
                                                                <div
                                                                    class="mt-1"
                                                                >
                                                                    <textarea
                                                                        v-model="
                                                                            form.description
                                                                        "
                                                                        id="description"
                                                                        name="description"
                                                                        rows="2"
                                                                        class="mt-1 block h-48 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                                        :class="{
                                                                            errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                                        }"
                                                                        placeholder="Presentez votre activité"
                                                                        autocomplete="none"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div
                                                    class="mt-4 flex w-full items-center justify-between"
                                                >
                                                    <button
                                                        type="button"
                                                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                                        @click="closeModal"
                                                    >
                                                        Annuler
                                                    </button>
                                                    <button
                                                        :disabled="
                                                            form.processing
                                                        "
                                                        type="submit"
                                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                                        @click="closeModal"
                                                    >
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </DialogPanel>
                                        </TransitionChild>
                                    </div>
                                </div>
                            </Dialog>
                        </TransitionRoot>
                    </div>
                    <ActivityDisplay
                        :structure="structure"
                        :structureActivites="structureActivites"
                    />
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
