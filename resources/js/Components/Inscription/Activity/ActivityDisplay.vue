<script setup>
import { ref, nextTick, defineAsyncComponent } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import dayjs from "dayjs";
import "dayjs/locale/fr"; // Import the French locale
import localeData from "dayjs/plugin/localeData"; // Import the localeData plugin
import {
    AcademicCapIcon,
    ArrowPathIcon,
    MapPinIcon,
    UsersIcon,
    ChevronUpIcon,
    UserGroupIcon,
    ClockIcon,
} from "@heroicons/vue/24/solid";
import {
    DocumentDuplicateIcon,
    XCircleIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
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
dayjs.locale("fr"); // Set the locale to French
dayjs.extend(localeData);

const ModalAddProduit = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalAddProduit.vue")
);

const ModalEditProduit = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditProduit.vue")
);

const ModalDeleteActivite = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalDeleteActivite.vue")
);

const props = defineProps({
    structureActivites: Object,
    structure: Object,
    errors: Object,
    filteredCriteres: Object,
    latestAdresseId: Number,
});

const currentStructureActivite = ref(null);
const currentProduit = ref(null);
const isOpen = ref(false);
const openTarif = ref(false);
const showDeleteActiviteModal = ref(false);
const showAddProduitModal = ref(false);
const showEditProduitModal = ref(false);

const formatDate = (dateTime) => {
    return dayjs(dateTime).locale("fr").format("DD MMMM YYYY");
};

const formatTime = (time) => {
    const formattedTime = time.substring(0, 5); // Extract the first 5 characters (HH:mm)
    return formattedTime;
};

const openDeleteModal = (structureActivite) => {
    currentStructureActivite.value = structureActivite;
    showDeleteActiviteModal.value = true;
};

const openAddProduitModal = (structureActivite) => {
    currentStructureActivite.value = structureActivite;
    showAddProduitModal.value = true;
};

const openEditProduitModal = (structureActivite, produit) => {
    currentStructureActivite.value = structureActivite;
    currentProduit.value = produit;
    showEditProduitModal.value = true;
    console.log(currentProduit.value);
};

const openEditModal = (structureActivite) => {
    currentStructureActivite.value = structureActivite;
    isOpen.value = true;
};

const closeEditModal = () => {
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

const destroy = (structureActivite, produit) => {
    const url = `/structures/${props.structure.slug}/activites/${structureActivite.id}/produits/${produit.id}`;
    router.delete(url, {
        preserveScroll: true,
        structure: props.structure.slug,
        activite: structureActivite.id,
        produit: produit.id,
    });
}

const destroyTarif = (tarif) => {
    const url = `/structures/${props.structure.slug}/tarifs/${tarif.id}`;
    router.delete(url, {
        preserveScroll: true,
        structure: props.structure.slug,
        tarif: tarif.id,
    });
}
</script>
<template>
    <div v-for="structureActivite in structureActivites" :key="structureActivite.id"
        class="flex flex-col w-full h-full space-y-3 border border-gray-200 rounded">
        <div class="flex items-center justify-between w-full px-2 py-4 bg-gray-700">
            <h2 class="font-semibold text-white">
                {{ structureActivite.titre }}
            </h2>
            <div class="flex items-center space-x-4">
                <button type="button" @click="openEditModal(structureActivite)"
                    class="px-2 py-1 text-base text-gray-700 transition duration-100 bg-white rounded-sm hover:text-gray-800 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                    Editer l'activité
                </button>
                <button type="button" @click="openDeleteModal(structureActivite)">
                    <TrashIcon class="w-6 h-6 mr-1 text-gray-100 hover:text-red-500" />
                </button>
            </div>
            <TransitionRoot appear :show="isOpen" as="template">
                <Dialog as="div" @close="closeEditModal" class="relative z-10">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0"
                        enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                        <div class="fixed inset-0 bg-gray-800 bg-opacity-50" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex items-center justify-center min-h-full p-4 text-center">
                            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100" leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                                <DialogPanel
                                    class="w-full max-w-3xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                                    <form @submit.prevent="
                                        submitForm(
                                            currentStructureActivite.id
                                        )
                                        " enctype="multipart/form-data" autocomplete="off">
                                        <DialogTitle as="div" class="flex items-center justify-between w-full">
                                            <h3 class="text-lg font-medium leading-6 text-gray-800">
                                                Modifier une activité
                                            </h3>
                                            <button type="button">
                                                <XCircleIcon @click="closeEditModal"
                                                    class="w-6 h-6 text-gray-600 hover:text-gray-800" />
                                            </button>
                                        </DialogTitle>
                                        <div class="w-full mt-2">
                                            <div class="flex flex-col space-y-3">
                                                <div>
                                                    <label for="image"
                                                        class="block text-sm font-medium text-gray-700">Ajouter ou modifier
                                                        la
                                                        photo ou l'image:</label>
                                                    <input class="mt-1 text-sm text-gray-700 focus:outline-none" type="file"
                                                        id="image" @input="
                                                            formEdit.image =
                                                            $event.target.files[0]
                                                            " />
                                                    <span v-if="formEdit.errors
                                                        .image
                                                        " class="mt-2 text-xs text-red-500">{{
        formEdit.errors
            .image[0]
    }}</span>
                                                </div>
                                                <div>
                                                    <label for="titre" class="block text-sm font-medium text-gray-700">
                                                        Titre de l'activité
                                                    </label>
                                                    <div class="flex mt-1 rounded-md">
                                                        <input v-model="formEdit.titre
                                                                " type="text" name="titre" id="titre"
                                                            class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                            :placeholder="`${structureActivite.categorie.nom_categorie} de ${structureActivite.discipline.name}`"
                                                            autocomplete="none" />
                                                    </div>
                                                    <div v-if="errors.titre" class="mt-2 text-xs text-red-500">
                                                        {{ errors.titre }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="description"
                                                        class="block text-sm font-medium text-gray-700">
                                                        Description
                                                    </label>
                                                    <div class="mt-1">
                                                        <textarea v-model="formEdit.description
                                                            " id="description" name="description" rows="2"
                                                            class="block w-full h-48 min-h-full mt-1 placeholder-gray-400 placeholder-opacity-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                            :class="{
                                                                errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                            }" :placeholder="currentStructureActivite.description
    " autocomplete="none">{{
        currentStructureActivite.description
    }}</textarea>
                                                    </div>
                                                    <div v-if="formEdit.errors
                                                                .description
                                                            " class="mt-2 text-xs text-red-500">
                                                        {{
                                                            formEdit.errors
                                                                .description
                                                        }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between w-full mt-4">
                                            <button type="button"
                                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                                @click="closeEditModal">
                                                Annuler
                                            </button>
                                            <button :disabled="formEdit.processing" type="submit"
                                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2">
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
            <div class="w-full mx-auto bg-purple-300 border border-gray-100 h-60 md:w-auto">
                <img v-if="structureActivite.image" alt="image" :src="structureActivite.image"
                    class="object-cover w-full h-full" />
                <img v-else alt="image"
                    src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="object-cover w-full h-full" />
            </div>
            <div class="flex flex-col items-start flex-1 px-2 py-2 space-y-3 md:space-y-6 md:px-4">
                <div class="flex items-center space-x-2">
                    <Switch v-model="structureActivite.actif" @click="toggleActif(structureActivite)" :class="structureActivite.actif
                        ? 'bg-green-600'
                        : 'bg-gray-200'
                        " class="relative inline-flex items-center h-6 rounded-full w-11">
                        <span class="sr-only">Actif</span>
                        <span :class="structureActivite.actif
                            ? 'translate-x-6'
                            : 'translate-x-1'
                            " class="inline-block w-4 h-4 transition transform bg-white rounded-full" />
                    </Switch>
                    <p class="text-lg font-semibold text-green-600" v-if="structureActivite.actif">
                        Actif
                    </p>
                    <p class="text-lg font-semibold text-gray-600" v-else>
                        Inactif
                    </p>
                </div>
                <div class="text-lg">
                    <h4 class="font-semibold">Description:</h4>
                    <p v-if="structureActivite.description" class="break-all whitespace-pre-line">
                        {{ structureActivite.description }}
                    </p>
                    <p v-else class="break-all whitespace-pre-line">
                        {{ structureActivite.structure.presentation_courte }}
                    </p>
                </div>
            </div>
            <div class="self-end px-2 py-1">
                <button type="button" @click="openAddProduitModal(structureActivite)"
                    class="px-2 py-1 text-base text-white transition duration-100 bg-gray-700 border border-green-500 rounded hover:bg-gray-800 hover:text-gray-200 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                    Ajouter un produit
                </button>
            </div>
        </div>
        <Disclosure v-slot="{ open }" defaultOpen>
            <DisclosureButton class="flex justify-between w-full px-4 py-4 font-semibold text-gray-800 bg-gray-200">
                <div class="flex items-center space-x-6">
                    <span>
                        {{ structureActivite.produits.length }}
                        <span v-if="structureActivite.produits.length > 1">produits</span>
                        <span v-else>produit</span>
                    </span>
                </div>

                <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''" class="w-5 h-5 text-gray-800" />
            </DisclosureButton>

            <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform opacity-0"
                enter-to-class="transform opacity-100" leave-active-class="transition duration-75 ease-out"
                leave-from-class="transform opacity-100" leave-to-class="transform opacity-0">
                <DisclosurePanel as="div">
                    <div v-for="produit in structureActivite.produits" :key="produit.id"
                        class="grid grid-flow-row grid-cols-3 gap-2 py-2 place-items-center odd:bg-white even:bg-slate-50 md:grid-cols-6">

                        <div v-if="produit.criteres.length > 0"
                            class="grid w-full h-full grid-cols-2 col-span-2 gap-2 place-items-center">
                            <div v-for="critere in produit.criteres" :key="critere.id" class="flex items-center col-span-1">
                                <UsersIcon v-if="critere.critere_id === 1" class="w-6 h-6 mr-1 text-gray-600" />
                                <AcademicCapIcon v-else-if="critere.critere_id === 2" class="w-6 h-6 mr-1 text-gray-600" />
                                <UsersIcon v-else class="w-6 h-6 mr-1 text-gray-600" />

                                <span v-if="critere.valeur" class="text-sm text-gray-600">{{ critere.valeur }}</span>
                                <span v-else class="text-sm text-gray-600">Tous</span>
                            </div>
                        </div>
                        <div v-else class="grid w-full h-full grid-cols-2 col-span-2 gap-2 place-items-center">
                            <div class="flex items-center col-span-1">
                                <UsersIcon class="w-6 h-6 mr-1 text-gray-600" />
                                <span class="text-sm text-gray-600">Tous Public</span>
                            </div>
                            <div class="flex items-center col-span-1">
                                <AcademicCapIcon class="w-6 h-6 mr-1 text-gray-600" />
                                <span class="text-sm text-gray-600">Tous Niveaux</span>
                            </div>
                        </div>
                        <div class="col-span-1 flex items-center p-0.5">
                            <MapPinIcon class="w-6 h-6 mr-1 text-gray-600" />
                            <div class="flex flex-col items-center text-sm text-gray-600">
                                {{ produit.adresse.address }},
                                {{ produit.adresse.zip_code }}
                                {{ produit.adresse.city }}
                            </div>
                        </div>
                        <div class="flex items-center col-span-1">
                            <ClockIcon class="w-6 h-6 mr-1 text-gray-600" />
                            <div v-if="produit.horaire_id" class="flex flex-col">
                                <span class="text-sm text-gray-600">Ouvert du
                                    {{ formatDate(produit.horaire.dayopen) }}
                                    au
                                    {{
                                        formatDate(produit.horaire.dayclose)
                                    }}</span>
                                <span class="text-sm text-gray-600">De
                                    {{ formatTime(produit.horaire.houropen) }}
                                    à
                                    {{
                                        formatTime(produit.horaire.hourclose)
                                    }}</span>
                            </div>
                            <span v-else class="text-sm text-gray-600">Planning</span>
                        </div>
                        <div class="flex items-center col-span-1">
                            <button @click="openTarif = !openTarif" type="button"
                                class="flex items-center justify-between w-full px-3 py-2 text-sm text-white transition duration-100 bg-green-600 rounded shadow-lg hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm">
                                Tarifs
                            </button>
                        </div>
                        <div class="flex items-center justify-between col-span-1 space-x-2">
                            <button type="button" @click="
                                openEditProduitModal(
                                    structureActivite,
                                    produit
                                )
                                ">
                                <ArrowPathIcon
                                    class="w-6 h-6 mr-1 text-gray-600 transition-all duration-200 hover:-rotate-90 hover:text-gray-800" />
                            </button>
                            <button type="button" @click="() => duplicate(structureActivite, produit)
                                ">
                                <DocumentDuplicateIcon class="w-6 h-6 mr-1 text-gray-600 hover:text-gray-800" />
                            </button>

                            <button type="button" @click="() => destroy(structureActivite, produit)">
                                <TrashIcon class="w-6 h-6 mr-1 text-gray-600 hover:text-gray-800" />
                            </button>
                        </div>

                        <div v-show="openTarif" class="w-full h-full col-span-3 md:col-span-6">
                            <div v-for="tarif in produit.tarifs" :key="tarif.id"
                                class="grid grid-flow-row grid-cols-3 gap-2 py-4 place-items-center odd:bg-slate-100 even:bg-white md:grid-cols-6">
                                <div v-if="tarif.structure_tarif_type_infos.length > 0"
                                    class="grid w-full h-full grid-cols-2 col-span-2 gap-2 place-items-center">
                                    <div v-for="info in tarif.structure_tarif_type_infos" :key="info.id"
                                        class="flex items-center col-span-1">
                                        <ClockIcon v-if="[1, 2, 5, 7].includes(info.attribut_id)"
                                            class="w-6 h-6 mr-1 text-gray-600" />
                                        <UserGroupIcon v-else-if="[3, 6].includes(info.attribut_id)"
                                            class="w-6 h-6 mr-1 text-gray-600" />
                                        <UsersIcon v-else class="w-6 h-6 mr-1 text-gray-600" />

                                        <span v-if="info.valeur" class="text-sm text-gray-600">{{ info.valeur }}</span>
                                        <span v-else class="text-sm text-gray-600">Pas défini</span>
                                    </div>
                                </div>
                                <div v-else class="grid w-full h-full grid-cols-2 col-span-2 gap-2 place-items-center">
                                    <div class="flex items-center col-span-1">
                                        <ClockIcon class="w-6 h-6 mr-1 text-gray-600" />
                                        <span class="text-sm text-gray-600"></span>
                                    </div>
                                    <div class="flex items-center col-span-1">
                                        <UserGroupIcon class="w-6 h-6 mr-1 text-gray-600" />
                                        <span class="text-sm text-gray-600"></span>
                                    </div>
                                </div>

                                <div class="flex items-center col-span-1">{{ tarif.titre }}</div>
                                <div class="flex items-center col-span-1 font-semibold">{{ tarif.tarif_type.type }}</div>
                                <div class="flex items-center col-span-1 font-semibold"> {{ tarif.amount }} €</div>
                                <div class="flex items-center justify-between col-span-1 space-x-2">
                                    <button type="button">
                                        <ArrowPathIcon
                                            class="w-6 h-6 mr-1 text-gray-600 transition-all duration-200 hover:-rotate-90 hover:text-gray-800" />
                                    </button>
                                    <button type="button">
                                        <DocumentDuplicateIcon class="w-6 h-6 mr-1 text-gray-600 hover:text-gray-800" />
                                    </button>

                                    <button type="button" @click="() => destroyTarif(tarif)">
                                        <TrashIcon class="w-6 h-6 mr-1 text-gray-600 hover:text-gray-800" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </DisclosurePanel>
            </transition>
        </Disclosure>
    </div>
    <ModalDeleteActivite :structure="structure" :structureActivite="currentStructureActivite"
        :show="showDeleteActiviteModal" @close="showDeleteActiviteModal = false" />
    <ModalAddProduit :errors="errors" :structure="structure" :structureActivite="currentStructureActivite"
        :show="showAddProduitModal" @close="showAddProduitModal = false" :filteredCriteres="filteredCriteres"
        :latestAdresseId="latestAdresseId" />
    <ModalEditProduit :errors="errors" :structure="structure" :structureActivite="currentStructureActivite"
        :produit="currentProduit" :show="showEditProduitModal" @close="showEditProduitModal = false"
        :filteredCriteres="filteredCriteres" :latestAdresseId="latestAdresseId" />
</template>
