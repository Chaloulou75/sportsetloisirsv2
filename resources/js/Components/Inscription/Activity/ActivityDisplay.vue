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

const form = useForm({
    titre: ref(null),
    description: ref(null),
    image: ref(null),
    actif: ref(null),
});

const actifValue = computed({
    get() {
        return Boolean(form.actif);
    },
    set(value) {
        form.actif = Number(value);
    },
});

const submitForm = (id) => {
    console.log(id),
        router.post(
            `/structures/${props.structure.slug}/activites/${id}`,
            {
                _method: "put",
                titre: form.titre,
                description: form.description,
                image: form.image,
                actif: form.actif,
            },
            {
                preserveScroll: true,
                onSuccess: () => form.reset(),
                structure: props.structure.slug,
                activite: id,
            }
        );
};

async function toggleActif(id) {
    await nextTick();
    router.post(
        `/structures/${props.structure.slug}/activites/${id}/toggleactif`,
        {
            _method: "put",
            actif: form.actif,
        },
        {
            preserveScroll: true,
            structure: props.structure.slug,
            activite: id,
        }
    );
}

const isOpen = ref(false);
const currentStructureActivite = ref(null);

const openModal = (structureActivite) => {
    isOpen.value = true;
    currentStructureActivite.value = structureActivite;
};

function closeModal(structureActivite) {
    isOpen.value = false;
}
</script>
<template>
    <div
        v-for="structureActivite in structureActivites"
        :key="structureActivite.id"
        class="flex h-full w-full flex-col space-y-3 rounded border border-gray-200"
    >
        <div
            class="flex w-full items-center justify-between bg-gray-700 px-4 py-4"
        >
            <h2 class="font-semibold text-white">
                {{ structureActivite.titre }} - {{ structureActivite.id }}
            </h2>
            <button
                type="button"
                @click="openModal(structureActivite)"
                class="rounded-sm bg-white px-2 py-1 text-base text-gray-700 transition duration-100 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
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
                                        <div class="mt-2 w-full">
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
                                                            form.image =
                                                                $event.target.files[0]
                                                        "
                                                    />
                                                    <span
                                                        v-if="form.errors.image"
                                                        class="mt-2 text-xs text-red-500"
                                                        >{{
                                                            form.errors.image[0]
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
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <input
                                                            v-model="form.titre"
                                                            type="text"
                                                            name="titre"
                                                            id="titre"
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            :placeholder="
                                                                structureActivite.titre
                                                            "
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
                                                :disabled="form.processing"
                                                @click="closeModal"
                                                type="submit"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
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
        <div class="flex w-full items-start">
            <div class="relative h-56 w-56 border border-gray-100">
                <img
                    v-if="structureActivite.image"
                    alt="image"
                    :src="structureActivite.image"
                    class="absolute inset-0 h-full w-full object-cover"
                />
                <img
                    v-else
                    alt="image"
                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="absolute inset-0 h-full w-full object-cover"
                />
            </div>
            <div
                class="flex flex-1 flex-col items-start space-y-6 px-2 py-2 md:px-4"
            >
                <div class="flex items-center space-x-2">
                    <Switch
                        v-model="actifValue"
                        @click="toggleActif(structureActivite.id)"
                        :class="form.actif ? 'bg-green-600' : 'bg-gray-200'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full"
                    >
                        <span class="sr-only">Actif</span>
                        <span
                            :class="
                                form.actif ? 'translate-x-6' : 'translate-x-1'
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
                    <p class="text-lg font-semibold text-gray-600" v-else>
                        Inactif
                    </p>
                </div>
                <p class="whitespace-pre-line text-lg">
                    <span class="text-lg font-semibold">Description:</span>
                    <span v-if="structureActivite.description">
                        {{ structureActivite.description }}
                    </span>
                    <span v-else>{{
                        structureActivite.structure.presentation_courte
                    }}</span>
                </p>
            </div>
        </div>
        <Disclosure v-slot="{ open }">
            <DisclosureButton
                class="flex w-full justify-between bg-gray-200 px-4 py-4 font-semibold text-gray-800"
            >
                <span>1 Produits / déclinaisons</span>
                <ChevronUpIcon
                    :class="open ? 'rotate-180 transform' : ''"
                    class="h-5 w-5 text-gray-800"
                />
            </DisclosureButton>

            <DisclosurePanel
                as="div"
                class="grid grid-cols-6 place-items-center"
            >
                <div class="col-span-1 flex items-center">
                    <UsersIcon class="mr-1 h-6 w-6 text-gray-600" />Tous Public
                </div>
                <div class="col-span-1 flex items-center">
                    <AcademicCapIcon class="mr-1 h-6 w-6 text-gray-600" />
                    Tous Niveaux
                </div>
                <div class="col-span-1 flex items-center p-0.5">
                    <MapPinIcon class="mr-1 h-6 w-6 text-gray-600" />
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
                    <ArrowPathIcon class="mr-1 h-6 w-6 text-gray-600" />
                    <DocumentDuplicateIcon class="mr-1 h-6 w-6 text-gray-600" />
                    <TrashIcon class="mr-1 h-6 w-6 text-gray-600" />
                </div>
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>
