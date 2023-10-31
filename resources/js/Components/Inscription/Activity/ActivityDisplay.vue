<script setup>
import { ref, nextTick, defineAsyncComponent } from "vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import localeData from "dayjs/plugin/localeData";
import {
    AcademicCapIcon,
    ArrowPathIcon,
    MapPinIcon,
    ChevronUpIcon,
    DocumentDuplicateIcon,
    XCircleIcon,
    TrashIcon,
    PlusIcon,
    UsersIcon,
    UserGroupIcon,
    ClockIcon,
    InformationCircleIcon,
    PencilSquareIcon,
    CurrencyEuroIcon,
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
dayjs.locale("fr");
dayjs.extend(localeData);

const ModalAddTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalAddTarif.vue")
);

const ModalAddProduit = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalAddProduit.vue")
);

const ModalEditProduit = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditProduit.vue")
);

const ModalDeleteActivite = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalDeleteActivite.vue")
);

const ModalEditTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditTarif.vue")
);

const props = defineProps({
    structureActivite: Object,
    structure: Object,
    errors: Object,
    filteredCriteres: Object,
    latestAdresseId: Number,
    tarifTypes: Object,
    activiteForTarifs: Object,
});

const isShowing = ref(true);
const currentStructureActivite = ref(null);
const currentProduit = ref(null);
const isOpen = ref(false);
const showDeleteActiviteModal = ref(false);
const showAddProduitModal = ref(false);
const showEditProduitModal = ref(false);
const openTarif = ref(false);
const currentTarif = ref(null);
const showEditTarifModal = ref(false);
const showAddTarifModal = ref(false);

const openAddTarifModal = (structure) => {
    showAddTarifModal.value = true;
};

const formatDate = (dateTime) => {
    return dayjs(dateTime).locale("fr").format("DD MMMM YYYY");
};

const formatTime = (time) => {
    const formattedTime = time.substring(0, 5); // Extract the first 5 characters (HH:mm)
    return formattedTime;
};

const formatCurrency = (value) => {
    const numericValue = Number(value.replace(/[^0-9.-]+/g, ""));
    if (!isNaN(numericValue)) {
        if (numericValue % 1 === 0) {
            return numericValue.toLocaleString() + " €";
        } else {
            return numericValue.toFixed(2) + " €";
        }
    }
    return value;
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
};

const openEditTarifModal = (tarif) => {
    currentTarif.value = tarif;
    showEditTarifModal.value = true;
};

const openEditModal = (structureActivite) => {
    currentStructureActivite.value = structureActivite;
    isOpen.value = true;
};

const closeEditModal = () => {
    isOpen.value = false;
};

const formEdit = useForm({
    titre: ref(props.structureActivite.titre),
    description: ref(props.structureActivite.description),
    image: ref(null),
});

const submitForm = (id) => {
    router.post(
        route("structures.activites.update", {
            structure: props.structure.slug,
            activite: id,
        }),
        {
            _method: "put",
            titre: formEdit.titre,
            description: formEdit.description,
            image: formEdit.image,
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                formEdit.reset();
                closeEditModal();
            },
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
            structure: props.structure.slug,
            activite: structureActivite.id,
        },
        {
            preserveScroll: true,
        }
    );
}

const duplicate = (structureActivite, produit) => {
    router.post(
        `/structures/${props.structure.slug}/activites/${structureActivite.id}/produits/${produit.id}/duplicate`,
        {
            structure: props.structure.slug,
            activite: structureActivite.id,
            produit: produit.id,
        },
        {
            preserveScroll: true,
        }
    );
};

const destroy = (structureActivite, produit) => {
    const url = `/structures/${props.structure.slug}/activites/${structureActivite.id}/produits/${produit.id}`;
    router.delete(
        url,
        {
            structure: props.structure.slug,
            activite: structureActivite.id,
            produit: produit.id,
        },
        {
            preserveScroll: true,
        }
    );
};

const openTarifToggle = (produit) => {
    currentProduit.value = produit;
    openTarif.value = !openTarif.value;
};

const isOpenTarif = (produit) => {
    return currentProduit.value === produit && openTarif.value;
};

const duplicateTarif = (tarif, produit) => {
    router.post(
        `/structures/${props.structure.slug}/tarifs/${tarif.id}/produits/${produit.id}/duplicate`,
        {
            structure: props.structure.slug,
            tarif: tarif.id,
            produit: produit.id,
        },
        {
            preserveScroll: true,
        }
    );
};

const destroyTarif = (tarif, produit) => {
    const url = `/structures/${props.structure.slug}/tarifs/${tarif.id}/produits/${produit.id}`;
    router.delete(
        url,
        {
            structure: props.structure.slug,
            tarif: tarif.id,
            produit: produit.id,
        },
        {
            preserveScroll: true,
        }
    );
};
</script>
<template>
    <TransitionRoot
        appear
        :show="isShowing"
        enter="transition-opacity ease-linear duration-400"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-linear duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
    >
        <div class="flex w-full flex-col rounded border border-gray-200">
            <div class="flex w-full items-center justify-between bg-gray-700">
                <h2 class="px-2 font-semibold text-white">
                    {{ structureActivite.titre }}
                    <Link
                        class="text-xs italic text-gray-300 hover:text-gray-100 md:text-sm"
                        :href="
                            route(
                                'structures.activites.show',
                                structureActivite.id
                            )
                        "
                    >
                        (voir la fiche)</Link
                    >
                </h2>
                <div class="flex h-full items-center">
                    <button
                        type="button"
                        @click="openEditModal(structureActivite)"
                        class="h-full w-auto bg-blue-500 p-4 hover:bg-blue-600"
                    >
                        <PencilSquareIcon
                            class="h-6 w-6 text-gray-100 hover:text-white"
                        />
                    </button>
                    <button
                        type="button"
                        @click="openDeleteModal(structureActivite)"
                        class="h-full w-auto bg-red-500 p-4 hover:bg-red-600"
                    >
                        <TrashIcon
                            class="mr-1 h-6 w-6 text-gray-100 hover:text-white"
                        />
                    </button>
                </div>
                <TransitionRoot appear :show="isOpen" as="template">
                    <Dialog
                        as="div"
                        @close="closeEditModal"
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
                                class="fixed inset-0 bg-gray-800 bg-opacity-50"
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
                                                        @click="closeEditModal"
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
                                                            >Ajouter ou modifier
                                                            la photo ou
                                                            l'image:</label
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
                                                            v-if="errors.image"
                                                            class="mt-2 text-xs text-red-500"
                                                            >{{
                                                                errors.image[0]
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
                                                                :placeholder="`${structureActivite.categorie.nom_categorie_pro} de ${structureActivite.discipline.name}`"
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
                                                                errors.description
                                                            "
                                                            class="mt-2 text-xs text-red-500"
                                                        >
                                                            {{
                                                                errors.description
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
                                                    @click="closeEditModal"
                                                >
                                                    Annuler
                                                </button>
                                                <button
                                                    :disabled="
                                                        formEdit.processing
                                                    "
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
                            class="text-lg font-semibold"
                            :class="
                                structureActivite.actif
                                    ? 'text-green-600'
                                    : 'text-gray-600'
                            "
                        >
                            {{ structureActivite.actif ? "Actif" : "Inactif" }}
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
                            {{
                                structureActivite.structure.presentation_courte
                            }}
                        </p>
                    </div>
                </div>
            </div>
            <Disclosure v-slot="{ open }" defaultOpen>
                <DisclosureButton
                    class="flex w-full justify-between bg-gray-200 pl-4 font-semibold text-gray-800"
                >
                    <div class="flex items-center py-3">
                        <span>
                            {{ structureActivite.produits.length }}
                            <span v-if="structureActivite.produits.length > 1"
                                >déclinaisons</span
                            >
                            <span v-else>déclinaison</span>
                        </span>
                        <ChevronUpIcon
                            :class="open ? 'rotate-180 transform' : ''"
                            class="ml-6 h-5 w-5 text-gray-800"
                        />
                    </div>

                    <button
                        type="button"
                        @click.prevent="openAddProduitModal(structureActivite)"
                        class="flex h-full w-auto items-center justify-center bg-green-500 px-3 py-3 hover:bg-green-600"
                    >
                        <PlusIcon class="h-6 w-6 text-white" />
                    </button>
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
                            class="grid grid-flow-row grid-cols-2 place-items-center divide-y divide-gray-100 odd:bg-white even:bg-slate-50 md:grid-cols-6"
                        >
                            <!-- criteres -->
                            <div
                                v-if="produit.criteres.length > 0"
                                class="col-span-2 grid h-full w-full grid-cols-2 place-items-center gap-2"
                            >
                                <div
                                    v-for="critere in produit.criteres"
                                    :key="critere.id"
                                    class="col-span-1 flex items-center"
                                >
                                    <InformationCircleIcon
                                        class="mr-1 h-5 w-5 text-gray-600"
                                    />
                                    <div class="flex flex-col items-center">
                                        <span
                                            v-if="critere.critere.nom"
                                            class="text-sm text-gray-600"
                                        >
                                            {{ critere.critere.nom }}:
                                        </span>
                                        <span
                                            v-if="critere.valeur"
                                            class="text-sm font-semibold text-gray-600"
                                            >{{ critere.valeur }}</span
                                        >
                                        <span
                                            v-if="
                                                critere.critere_valeur &&
                                                critere.critere_valeur
                                                    .sous_criteres &&
                                                critere.critere_valeur
                                                    .sous_criteres.length > 0
                                            "
                                            class="text-xs font-medium text-gray-600"
                                        >
                                            <span
                                                v-for="sousCriteres in critere
                                                    .critere_valeur
                                                    .sous_criteres"
                                                :key="sousCriteres.id"
                                            >
                                                <span
                                                    v-for="sousCritValeur in sousCriteres.prod_sous_crit_valeurs"
                                                    :key="sousCritValeur.id"
                                                    class="text-xs font-semibold text-gray-600"
                                                >
                                                    <span
                                                        v-if="
                                                            sousCritValeur.produit_id ===
                                                            produit.id
                                                        "
                                                        >({{
                                                            sousCritValeur.valeur
                                                        }})</span
                                                    ></span
                                                >
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- pas de criteres -->
                            <div
                                v-else
                                class="col-span-2 grid h-full w-full grid-cols-2 place-items-center gap-2"
                            >
                                <div class="col-span-1 flex items-start">
                                    <UsersIcon
                                        class="mr-1 h-6 w-6 text-gray-600"
                                    />
                                    <span class="text-sm text-gray-600"
                                        >Tous Public</span
                                    >
                                </div>
                                <div class="col-span-1 flex items-start">
                                    <AcademicCapIcon
                                        class="mr-1 h-6 w-6 text-gray-600"
                                    />
                                    <span class="text-sm text-gray-600"
                                        >Tous Niveaux</span
                                    >
                                </div>
                            </div>
                            <!-- adresse -->
                            <div class="col-span-1 flex h-full items-center">
                                <MapPinIcon
                                    class="mr-1 h-6 w-6 text-gray-600"
                                />
                                <div
                                    class="flex flex-col items-center text-sm text-gray-600"
                                >
                                    {{ produit.adresse.address }},
                                    {{ produit.adresse.zip_code }}
                                    {{ produit.adresse.city }}
                                </div>
                            </div>
                            <!-- horaire -->
                            <div class="col-span-1 flex items-center">
                                <ClockIcon class="mr-1 h-6 w-6 text-gray-600" />
                                <div
                                    v-if="produit.horaire_id"
                                    class="flex flex-col"
                                >
                                    <span class="text-sm text-gray-600"
                                        >Ouvert du
                                        {{
                                            formatDate(produit.horaire.dayopen)
                                        }}
                                        au
                                        {{
                                            formatDate(produit.horaire.dayclose)
                                        }}</span
                                    >
                                    <span class="text-sm text-gray-600"
                                        >De
                                        {{
                                            formatTime(produit.horaire.houropen)
                                        }}
                                        à
                                        {{
                                            formatTime(
                                                produit.horaire.hourclose
                                            )
                                        }}</span
                                    >
                                </div>
                                <span v-else class="text-sm text-gray-600"
                                    >Planning</span
                                >
                            </div>
                            <!-- tarif block -->
                            <div
                                class="col-span-1 flex h-full w-full items-center justify-center"
                            >
                                <button
                                    @click="() => openTarifToggle(produit)"
                                    type="button"
                                    class="flex h-full w-1/2 items-center justify-center bg-green-600 hover:bg-green-500"
                                >
                                    <CurrencyEuroIcon
                                        class="mr-1 h-6 w-6 text-gray-50 hover:text-white"
                                    />
                                </button>
                                <button
                                    type="button"
                                    @click="
                                        openEditProduitModal(
                                            structureActivite,
                                            produit
                                        )
                                    "
                                    class="flex h-full w-1/2 items-center justify-center bg-blue-500 p-4 hover:bg-blue-600"
                                >
                                    <ArrowPathIcon
                                        class="mr-1 h-6 w-6 text-gray-50 transition-all duration-200 hover:-rotate-90 hover:text-white"
                                    />
                                </button>
                            </div>
                            <!-- gestion -->
                            <div
                                class="col-span-1 flex h-full w-full items-center justify-between"
                            >
                                <button
                                    type="button"
                                    @click="
                                        () =>
                                            duplicate(
                                                structureActivite,
                                                produit
                                            )
                                    "
                                    class="flex h-full w-1/2 items-center justify-center bg-blue-500 p-4 hover:bg-blue-600"
                                >
                                    <DocumentDuplicateIcon
                                        class="mr-1 h-6 w-6 text-gray-50 hover:text-white"
                                    />
                                </button>
                                <button
                                    type="button"
                                    @click="
                                        () =>
                                            destroy(structureActivite, produit)
                                    "
                                    class="flex h-full w-1/2 items-center justify-center bg-red-500 p-4 hover:bg-red-600"
                                >
                                    <TrashIcon
                                        class="mr-1 h-6 w-6 text-gray-100 hover:text-white"
                                    />
                                </button>
                            </div>
                            <div
                                v-show="isOpenTarif(produit)"
                                class="col-span-3 h-full w-full py-2 md:col-span-6"
                            >
                                <div
                                    class="flex w-full items-center justify-between px-2 py-2 md:px-4"
                                >
                                    <h3
                                        v-if="produit.tarifs.length > 0"
                                        class="w-full px-2 py-2 text-sm font-semibold text-gray-700 md:px-4"
                                    >
                                        Liste des tarifs pour ce produit:
                                    </h3>
                                    <h3
                                        v-else
                                        class="w-full px-2 py-2 text-sm font-semibold text-gray-700 md:px-4"
                                    >
                                        Pas encore de tarif pour ce produit.
                                    </h3>
                                    <button
                                        type="button"
                                        @click="openAddTarifModal(structure)"
                                        class="w-auto rounded bg-green-600 px-2 py-1.5 text-xs text-white shadow-lg transition duration-100 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                                    >
                                        Ajouter un tarif
                                    </button>
                                </div>
                                <div v-show="produit.tarifs.length > 0">
                                    <div
                                        v-for="tarif in produit.tarifs"
                                        :key="tarif.id"
                                        class="grid grid-flow-row grid-cols-3 place-items-center gap-2 py-4 odd:bg-white even:bg-slate-100 md:grid-cols-6"
                                    >
                                        <div
                                            v-if="
                                                tarif.structure_tarif_type_infos
                                                    .length > 0
                                            "
                                            class="col-span-2 grid h-full w-full grid-cols-2 place-items-center gap-2 py-2"
                                        >
                                            <div
                                                v-for="info in tarif.structure_tarif_type_infos"
                                                :key="info.id"
                                                class="col-span-1 flex items-center"
                                            >
                                                <div v-if="info">
                                                    <ClockIcon
                                                        v-if="
                                                            [
                                                                1, 2, 5, 7,
                                                            ].includes(
                                                                info.attribut_id
                                                            )
                                                        "
                                                        class="mr-1 h-5 w-5 text-slate-500"
                                                    />
                                                    <UserGroupIcon
                                                        v-else-if="
                                                            [3, 6].includes(
                                                                info.attribut_id
                                                            )
                                                        "
                                                        class="mr-1 h-5 w-5 text-slate-500"
                                                    />
                                                    <UsersIcon
                                                        v-else
                                                        class="mr-1 h-5 w-5 text-slate-500"
                                                    />
                                                </div>
                                                <div v-else>
                                                    <UsersIcon
                                                        class="mr-1 h-5 w-5 text-slate-500"
                                                    />
                                                </div>

                                                <div>
                                                    <span
                                                        v-if="info.valeur"
                                                        class="text-sm font-semibold text-gray-600"
                                                        >{{
                                                            info
                                                                .tarif_type_attribut
                                                                .attribut
                                                        }}: {{ info.valeur }}
                                                        <span
                                                            v-if="info.unite"
                                                            >{{
                                                                info.unite
                                                            }}</span
                                                        >
                                                    </span>
                                                    <span
                                                        v-else
                                                        class="text-sm font-semibold text-gray-600"
                                                        >Pas de valeur</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            v-else
                                            class="col-span-2 grid h-full w-full grid-cols-2 place-items-center gap-2"
                                        >
                                            <div class="col-span-1"></div>
                                            <div class="col-span-1"></div>
                                        </div>

                                        <div
                                            class="col-span-1 flex items-center font-semibold"
                                        >
                                            {{ tarif.titre }}
                                        </div>
                                        <div
                                            class="col-span-1 flex items-center font-semibold"
                                        >
                                            {{ tarif.tarif_type.type }}
                                        </div>
                                        <div
                                            class="col-span-1 flex items-center font-semibold"
                                        >
                                            {{ formatCurrency(tarif.amount) }}
                                        </div>
                                        <div
                                            class="col-span-1 flex items-center justify-between space-x-2"
                                        >
                                            <button
                                                type="button"
                                                @click="
                                                    openEditTarifModal(tarif)
                                                "
                                            >
                                                <ArrowPathIcon
                                                    class="mr-1 h-6 w-6 text-gray-600 transition-all duration-200 hover:-rotate-90 hover:text-gray-800"
                                                />
                                            </button>
                                            <button
                                                type="button"
                                                @click="
                                                    () =>
                                                        duplicateTarif(
                                                            tarif,
                                                            produit
                                                        )
                                                "
                                            >
                                                <DocumentDuplicateIcon
                                                    class="mr-1 h-6 w-6 text-gray-600 hover:text-gray-800"
                                                />
                                            </button>

                                            <button
                                                type="button"
                                                @click="
                                                    () =>
                                                        destroyTarif(
                                                            tarif,
                                                            produit
                                                        )
                                                "
                                                class="h-full w-auto bg-red-500 p-4 hover:bg-red-600"
                                            >
                                                <TrashIcon
                                                    class="mr-1 h-6 w-6 text-gray-100 hover:text-white"
                                                />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </DisclosurePanel>
                </transition>
            </Disclosure>
        </div>
    </TransitionRoot>

    <ModalDeleteActivite
        :structure="structure"
        :structureActivite="structureActivite"
        :show="showDeleteActiviteModal"
        @close="showDeleteActiviteModal = false"
    />
    <ModalAddProduit
        :errors="errors"
        :structure="structure"
        :structureActivite="structureActivite"
        :show="showAddProduitModal"
        @close="showAddProduitModal = false"
        :filteredCriteres="filteredCriteres"
        :latestAdresseId="latestAdresseId"
    />
    <ModalEditProduit
        :errors="errors"
        :structure="structure"
        :structureActivite="structureActivite"
        :produit="currentProduit"
        :show="showEditProduitModal"
        @close="showEditProduitModal = false"
        :filteredCriteres="filteredCriteres"
        :latestAdresseId="latestAdresseId"
    />
    <ModalAddTarif
        :errors="errors"
        :structure="structure"
        :tarif-types="tarifTypes"
        :activiteForTarifs="activiteForTarifs"
        :show="showAddTarifModal"
        @close="showAddTarifModal = false"
    />
    <ModalEditTarif
        :errors="errors"
        :structure="structure"
        :tarif="currentTarif"
        :tarif-types="tarifTypes"
        :activiteForTarifs="activiteForTarifs"
        :show="showEditTarifModal"
        @close="showEditTarifModal = false"
    />
</template>
