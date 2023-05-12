<script setup>
import { ref, computed, nextTick } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import {
    AcademicCapIcon,
    ArrowPathIcon,
    ClockIcon,
    DocumentDuplicateIcon,
    MapPinIcon,
    TrashIcon,
    UsersIcon,
    ChevronUpIcon,
} from "@heroicons/vue/24/solid";
import {
    Switch,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";

const props = defineProps({
    structureActivites: Object,
    structure: Object,
});

const formEdit = useForm({
    titre: ref(null),
    description: ref(null),
    image: ref(null),
    // actif: ref(null),
});

const submitForm = (id) => {
    router.post(
        `/structures/${props.structure.slug}/activites/${id}`,
        {
            _method: "put",
            titre: formEdit.titre,
            description: formEdit.description,
            image: formEdit.image,
            // actif: formEdit.actif,
        },
        {
            preserveScroll: true,
            onSuccess: () => formEdit.reset(),
            structure: props.structure.slug,
            activite: id,
        }
    );
};

async function toggleActif(structureActivite) {
    await nextTick();
    router.post(
        `/structures/${props.structure.slug}/activites/${structureActivite.id}/toggleactif`,
        {
            _method: "put",
            actif: structureActivite.actif,
        },
        {
            preserveScroll: true,
            onSuccess: () => formEdit.reset(),
            structure: props.structure.slug,
            activite: structureActivite.id,
        }
    );
}

const isOpen = ref(false);
const currentStructureActivite = ref(null);

const openModal = (structureActivite) => {
    isOpen.value = true;
    currentStructureActivite.value = structureActivite;
};

const closeModal = () => {
    isOpen.value = false;
};
</script>
<template>
    <div
        v-for="structureActivite in structureActivites"
        :key="structureActivite.id"
        class="flex flex-col w-full h-full space-y-3 border border-gray-200 rounded"
    >
        <div
            class="flex items-center justify-between w-full px-2 py-4 bg-gray-700"
        >
            <h2 class="font-semibold text-white">
                {{ structureActivite.titre }}
            </h2>
            <button
                type="button"
                @click="openModal(structureActivite)"
                class="px-2 py-1 text-base text-gray-700 transition duration-100 bg-white rounded-sm hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
            >
                Editer l'activité
            </button>
            <TransitionRoot appear :show="isOpen" as="template">
                <Dialog as="div" @close="closeModal" class="relative z-10">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black bg-opacity-50" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div
                            class="flex items-center justify-center min-h-full p-4 text-center"
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
                                    class="w-full max-w-3xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
                                >
                                    <form
                                        @submit.prevent="
                                            submitForm(
                                                currentStructureActivite.id
                                            )
                                        "
                                        enctype="multipart/form-data"
                                        autocomplete="off"
                                    >
                                        <DialogTitle
                                            as="h3"
                                            class="text-lg font-medium leading-6 text-gray-900"
                                        >
                                            Modifier une activité
                                        </DialogTitle>
                                        <div class="w-full mt-2">
                                            <div
                                                class="flex flex-col space-y-3"
                                            >
                                                <div>
                                                    <label
                                                        for="image"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Ajouter ou modifier la
                                                        photo ou l'image:</label
                                                    >
                                                    <input
                                                        class="mt-1 text-sm text-gray-700 focus:outline-none"
                                                        type="file"
                                                        id="image"
                                                        @input="
                                                            formEdit.image =
                                                                $event.target.files[0]
                                                        "
                                                    />
                                                    <span
                                                        v-if="
                                                            formEdit.errors
                                                                .image
                                                        "
                                                        class="mt-2 text-xs text-red-500"
                                                        >{{
                                                            formEdit.errors
                                                                .image[0]
                                                        }}</span
                                                    >
                                                </div>
                                                <div>
                                                    <label
                                                        for="titre"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Titre de l'activité
                                                    </label>
                                                    <div
                                                        class="flex mt-1 rounded-md"
                                                    >
                                                        <input
                                                            v-model="
                                                                formEdit.titre
                                                            "
                                                            type="text"
                                                            name="titre"
                                                            id="titre"
                                                            class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                            :placeholder="`${structureActivite.categorie.nom_categorie} de ${structureActivite.discipline.name}`"
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
                                                    <div class="mt-1">
                                                        <textarea
                                                            v-model="
                                                                formEdit.description
                                                            "
                                                            id="description"
                                                            name="description"
                                                            rows="2"
                                                            class="block w-full h-48 min-h-full mt-1 placeholder-gray-400 placeholder-opacity-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                            :class="{
                                                                errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                            }"
                                                            :placeholder="
                                                                currentStructureActivite.description
                                                            "
                                                            autocomplete="none"
                                                            >{{
                                                                currentStructureActivite.description
                                                            }}</textarea
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-center justify-between w-full mt-4"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                                @click="closeModal"
                                            >
                                                Annuler
                                            </button>
                                            <button
                                                :disabled="formEdit.processing"
                                                @click="closeModal"
                                                type="submit"
                                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                            >
                                                Enregistrer
                                            </button>
                                        </div>
                                    </form>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </div>
        <div class="flex flex-col items-start w-full md:flex-row">
            <div
                class="w-full mx-auto bg-purple-300 border border-gray-100 h-60 md:w-auto"
            >
                <img
                    v-if="structureActivite.image"
                    alt="image"
                    :src="structureActivite.image"
                    class="object-cover w-full h-full"
                />
                <img
                    v-else
                    alt="image"
                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="object-cover w-full h-full"
                />
            </div>
            <div
                class="flex flex-col items-start flex-1 px-2 py-2 space-y-3 md:space-y-6 md:px-4"
            >
                <div class="flex items-center space-x-2">
                    <Switch
                        v-model="structureActivite.actif"
                        @click="toggleActif(structureActivite)"
                        :class="
                            structureActivite.actif
                                ? 'bg-green-600'
                                : 'bg-gray-200'
                        "
                        class="relative inline-flex items-center h-6 rounded-full w-11"
                    >
                        <span class="sr-only">Actif</span>
                        <span
                            :class="
                                structureActivite.actif
                                    ? 'translate-x-6'
                                    : 'translate-x-1'
                            "
                            class="inline-block w-4 h-4 transition transform bg-white rounded-full"
                        />
                    </Switch>
                    <p
                        class="text-lg font-semibold text-green-600"
                        v-if="structureActivite.actif"
                    >
                        Actif
                    </p>
                    <p class="text-lg font-semibold text-gray-600" v-else>
                        Inactif
                    </p>
                </div>
                <div class="text-lg">
                    <h4 class="font-semibold">Description:</h4>
                    <p
                        v-if="structureActivite.description"
                        class="break-all whitespace-pre-line"
                    >
                        {{ structureActivite.description }}
                    </p>
                    <p v-else class="break-all whitespace-pre-line">
                        {{ structureActivite.structure.presentation_courte }}
                    </p>
                </div>
            </div>
        </div>
        <Disclosure v-slot="{ open }" defaultOpen>
            <DisclosureButton
                class="flex justify-between w-full px-4 py-4 font-semibold text-gray-800 bg-gray-200"
            >
                <span>
                    {{ structureActivite.produits.length }}
                    <span v-if="structureActivite.produits.length > 1"
                        >produits</span
                    >
                    <span v-else>produit</span>
                </span>
                <ChevronUpIcon
                    :class="open ? 'rotate-180 transform' : ''"
                    class="w-5 h-5 text-gray-800"
                />
            </DisclosureButton>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform opacity-0"
                enter-to-class="transform opacity-100"
                leave-active-class="transition duration-75 ease-out"
                leave-from-class="transform opacity-100"
                leave-to-class="transform opacity-0"
            >
                <DisclosurePanel as="div">
                    <div
                        v-for="produit in structureActivite.produits"
                        :key="produit.id"
                        class="grid grid-cols-6 gap-2 py-4 place-items-center odd:bg-white even:bg-slate-100"
                    >
                        <div class="flex items-center col-span-1">
                            <UsersIcon class="w-6 h-6 mr-1 text-gray-600" />
                            <span class="text-sm text-gray-600"
                                >Tous public</span
                            >
                        </div>
                        <div class="flex items-center col-span-1">
                            <AcademicCapIcon
                                class="w-6 h-6 mr-1 text-gray-600"
                            />
                            <span class="text-sm text-gray-600"
                                >Tous Niveaux</span
                            >
                        </div>
                        <div class="col-span-1 flex items-center p-0.5">
                            <MapPinIcon class="w-6 h-6 mr-1 text-gray-600" />
                            <div
                                class="flex flex-col items-center text-sm text-gray-600"
                            >
                                {{ produit.adresse.address }},
                                {{ produit.adresse.zip_code }}
                                {{ produit.adresse.city }}
                            </div>
                        </div>
                        <div class="flex items-center col-span-1">
                            <ClockIcon class="w-6 h-6 mr-1 text-gray-600" />
                            <span class="text-sm text-gray-600">Planning</span>
                        </div>
                        <div class="flex items-center col-span-1">
                            <button
                                type="button"
                                class="flex items-center justify-between w-full px-3 py-2 text-sm text-white transition duration-100 bg-green-600 rounded shadow-lg hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm"
                            >
                                Tarifs
                            </button>
                        </div>
                        <div
                            class="flex items-center justify-between col-span-1 space-x-2"
                        >
                            <button type="button">
                                <ArrowPathIcon
                                    class="w-6 h-6 mr-1 text-gray-600 hover:text-gray-800"
                                />
                            </button>
                            <button type="button" @click="duplicate(produit)">
                                <DocumentDuplicateIcon
                                    class="w-6 h-6 mr-1 text-gray-600 hover:text-gray-800"
                                />
                            </button>

                            <button type="button">
                                <TrashIcon
                                    class="w-6 h-6 mr-1 text-gray-600 hover:text-gray-800"
                                />
                            </button>
                        </div>
                    </div>
                </DisclosurePanel>
            </transition>
        </Disclosure>
    </div>
</template>
