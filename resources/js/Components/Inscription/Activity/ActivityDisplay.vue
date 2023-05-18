<script setup>
import { ref, nextTick } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import ModalDeleteActivite from "@/Components/Modals/ModalDeleteActivite.vue";
import dayjs from "dayjs";
import "dayjs/locale/fr"; // Import the French locale
import localeData from "dayjs/plugin/localeData"; // Import the localeData plugin
dayjs.locale("fr"); // Set the locale to French
dayjs.extend(localeData);
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
import { XCircleIcon } from "@heroicons/vue/24/outline";
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
    errors: Object,
});

const currentStructureActivite = ref(null);
const isOpen = ref(false);
const showDeleteActiviteModal = ref(false);

const formatDate = (dateTime) => {
    return dayjs(dateTime).locale("fr").format("DD MMMM YYYY");
};

const formatTime = (time) => {
    const formattedTime = time.substring(0, 5); // Extract the first 5 characters (HH:mm)
    return formattedTime;
};

const openDeleteModal = (structureActivite) => {
    showDeleteActiviteModal.value = true;
    currentStructureActivite.value = structureActivite;
};

const openModal = (structureActivite) => {
    isOpen.value = true;
    currentStructureActivite.value = structureActivite;
};

const closeModal = () => {
    isOpen.value = false;
};

const formEdit = useForm({
    titre: ref(null),
    description: ref(null),
    image: ref(null),
});

const submitForm = (id) => {
    router.post(
        `/structures/${props.structure.slug}/activites/${id}`,
        {
            _method: "put",
            titre: formEdit.titre,
            description: formEdit.description,
            image: formEdit.image,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                formEdit.reset();
                closeModal();
            },
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
            structure: props.structure.slug,
            activite: structureActivite.id,
        }
    );
}

function duplicate(structureActivite, produit) {
    router.post(
        `/structures/${props.structure.slug}/activites/${structureActivite.id}/produits/${produit.id}/duplicate`,
        {
            preserveScroll: true,
            structure: props.structure.slug,
            activite: structureActivite.id,
            produit: produit.id,
        }
    );
}

function destroy(structureActivite, produit) {
    const url = `/structures/${props.structure.slug}/activites/${structureActivite.id}/produits/${produit.id}`;
    router.delete(url, {
        preserveScroll: true,
        structure: props.structure.slug,
        activite: structureActivite.id,
        produit: produit.id,
    });
}
</script>
<template>
    <div
        v-for="structureActivite in structureActivites"
        :key="structureActivite.id"
        class="flex h-full w-full flex-col space-y-3 rounded border border-gray-200"
    >
        <div
            class="flex w-full items-center justify-between bg-gray-700 px-2 py-4"
        >
            <h2 class="font-semibold text-white">
                {{ structureActivite.titre }}
            </h2>
            <div class="flex items-center space-x-4">
                <button
                    type="button"
                    @click="openModal(structureActivite)"
                    class="rounded-sm bg-white px-2 py-1 text-base text-gray-700 transition duration-100 hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                >
                    Editer l'activité
                </button>
                <button
                    type="button"
                    @click="openDeleteModal(structureActivite)"
                >
                    <TrashIcon
                        class="mr-1 h-6 w-6 text-gray-100 hover:text-red-500"
                    />
                </button>
                <ModalDeleteActivite
                    :structure="structure"
                    :structureActivite="currentStructureActivite"
                    :show="showDeleteActiviteModal"
                    @close="showDeleteActiviteModal = false"
                />
            </div>
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
                        <div class="fixed inset-0 bg-gray-800 bg-opacity-50" />
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
                                            as="div"
                                            class="flex w-full items-center justify-between"
                                        >
                                            <h3
                                                class="text-lg font-medium leading-6 text-gray-800"
                                            >
                                                Modifier une activité
                                            </h3>
                                            <button type="button">
                                                <XCircleIcon
                                                    @click="closeModal"
                                                    class="h-6 w-6 text-gray-600 hover:text-gray-800"
                                                />
                                            </button>
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
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <input
                                                            v-model="
                                                                formEdit.titre
                                                            "
                                                            type="text"
                                                            name="titre"
                                                            id="titre"
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            :placeholder="`${structureActivite.categorie.nom_categorie} de ${structureActivite.discipline.name}`"
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                    <div
                                                        v-if="errors.titre"
                                                        class="mt-2 text-xs text-red-500"
                                                    >
                                                        {{ errors.titre }}
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
                                                            class="mt-1 block h-48 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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
                                                    <div
                                                        v-if="
                                                            formEdit.errors
                                                                .description
                                                        "
                                                        class="mt-2 text-xs text-red-500"
                                                    >
                                                        {{
                                                            formEdit.errors
                                                                .description
                                                        }}
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
                                                :disabled="formEdit.processing"
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
        <div class="flex w-full flex-col items-start md:flex-row">
            <div
                class="mx-auto h-60 w-full border border-gray-100 bg-purple-300 md:w-auto"
            >
                <img
                    v-if="structureActivite.image"
                    alt="image"
                    :src="structureActivite.image"
                    class="h-full w-full object-cover"
                />
                <img
                    v-else
                    alt="image"
                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="h-full w-full object-cover"
                />
            </div>
            <div
                class="flex flex-1 flex-col items-start space-y-3 px-2 py-2 md:space-y-6 md:px-4"
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
                        class="relative inline-flex h-6 w-11 items-center rounded-full"
                    >
                        <span class="sr-only">Actif</span>
                        <span
                            :class="
                                structureActivite.actif
                                    ? 'translate-x-6'
                                    : 'translate-x-1'
                            "
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition"
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
                        class="whitespace-pre-line break-all"
                    >
                        {{ structureActivite.description }}
                    </p>
                    <p v-else class="whitespace-pre-line break-all">
                        {{ structureActivite.structure.presentation_courte }}
                    </p>
                </div>
            </div>
        </div>
        <Disclosure v-slot="{ open }" defaultOpen>
            <DisclosureButton
                class="flex w-full justify-between bg-gray-200 px-4 py-4 font-semibold text-gray-800"
            >
                <div class="flex items-center space-x-6">
                    <span>
                        {{ structureActivite.produits.length }}
                        <span v-if="structureActivite.produits.length > 1"
                            >produits</span
                        >
                        <span v-else>produit</span>
                    </span>
                </div>

                <ChevronUpIcon
                    :class="open ? 'rotate-180 transform' : ''"
                    class="h-5 w-5 text-gray-800"
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
                        class="grid grid-flow-row grid-cols-3 place-items-center gap-2 py-4 odd:bg-white even:bg-slate-100 md:grid-cols-6"
                    >
                        <div
                            v-if="produit.criteres.length > 0"
                            class="col-span-2 grid h-full w-full grid-cols-2 place-items-center gap-2"
                        >
                            <div
                                v-for="critere in produit.criteres"
                                :key="critere.id"
                                class="col-span-1 flex items-center"
                            >
                                <UsersIcon
                                    v-if="critere.critere_id === 1"
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />
                                <AcademicCapIcon
                                    v-else-if="critere.critere_id === 2"
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />
                                <UsersIcon
                                    v-else
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />

                                <span
                                    v-if="critere.valeur"
                                    class="text-sm text-gray-600"
                                    >{{ critere.valeur }}</span
                                >
                                <span v-else class="text-sm text-gray-600"
                                    >Tous</span
                                >
                            </div>
                        </div>
                        <div
                            v-else
                            class="col-span-2 grid h-full w-full grid-cols-2 place-items-center gap-2"
                        >
                            <div class="col-span-1 flex items-center">
                                <UsersIcon class="mr-1 h-6 w-6 text-gray-600" />
                                <span class="text-sm text-gray-600"
                                    >Tous Public</span
                                >
                            </div>
                            <div class="col-span-1 flex items-center">
                                <AcademicCapIcon
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />
                                <span class="text-sm text-gray-600"
                                    >Tous Niveaux</span
                                >
                            </div>
                        </div>
                        <div class="col-span-1 flex items-center p-0.5">
                            <MapPinIcon class="mr-1 h-6 w-6 text-gray-600" />
                            <div
                                class="flex flex-col items-center text-sm text-gray-600"
                            >
                                {{ produit.adresse.address }},
                                {{ produit.adresse.zip_code }}
                                {{ produit.adresse.city }}
                            </div>
                        </div>
                        <div class="col-span-1 flex items-center">
                            <ClockIcon class="mr-1 h-6 w-6 text-gray-600" />
                            <div
                                v-if="produit.horaire_id"
                                class="flex flex-col"
                            >
                                <span class="text-sm text-gray-600"
                                    >Ouvert du
                                    {{ formatDate(produit.horaires.dayopen) }}
                                    au
                                    {{
                                        formatDate(produit.horaires.dayclose)
                                    }}</span
                                >
                                <span class="text-sm text-gray-600"
                                    >De
                                    {{ formatTime(produit.horaires.houropen) }}
                                    à
                                    {{
                                        formatTime(produit.horaires.hourclose)
                                    }}</span
                                >
                            </div>
                            <span v-else class="text-sm text-gray-600"
                                >Planning</span
                            >
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
                            <button type="button">
                                <ArrowPathIcon
                                    class="mr-1 h-6 w-6 text-gray-600 hover:text-gray-800"
                                />
                            </button>
                            <button
                                type="button"
                                @click="
                                    () => duplicate(structureActivite, produit)
                                "
                            >
                                <DocumentDuplicateIcon
                                    class="mr-1 h-6 w-6 text-gray-600 hover:text-gray-800"
                                />
                            </button>

                            <button
                                type="button"
                                @click="destroy(structureActivite, produit)"
                            >
                                <TrashIcon
                                    class="mr-1 h-6 w-6 text-gray-600 hover:text-gray-800"
                                />
                            </button>
                        </div>
                    </div>
                </DisclosurePanel>
            </transition>
        </Disclosure>
    </div>
</template>
