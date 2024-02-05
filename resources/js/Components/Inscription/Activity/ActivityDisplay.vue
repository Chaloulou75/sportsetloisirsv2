<script setup>
import { classMapping } from "@/Utils/classMapping.js";
import { ref, computed, nextTick, defineAsyncComponent } from "vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import localeData from "dayjs/plugin/localeData";
import {
    ArrowPathIcon,
    MapPinIcon,
    ChevronUpIcon,
    DocumentDuplicateIcon,
    XCircleIcon,
    TrashIcon,
    PlusIcon,
    PencilSquareIcon,
    CurrencyEuroIcon,
} from "@heroicons/vue/24/outline";
import {
    Switch,
    SwitchGroup,
    SwitchLabel,
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

const emit = defineEmits(["addTarif"]);
const openAddTarifModal = () => {
    emit("addTarif");
};

const ModalAddProduit = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalAddProduit.vue")
);

const ModalEditProduit = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditProduit.vue")
);

const ModalDeleteActivite = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalDeleteActivite.vue")
);

const ModalEditCatTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditCatTarif.vue")
);

const props = defineProps({
    structureActivite: Object,
    structure: Object,
    errors: Object,
    uniqueCriteresInProducts: Object,
    criteres: Object,
    latestAdresseId: Number,
    tarifTypes: Object,
    activiteForTarifs: Object,
    allCategories: Object,
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

const headerClass = computed(() => {
    const defaultClass = "bg-la-base";
    const image = props.structureActivite.image;
    const disciplineId = props.structureActivite.discipline_id;

    if (props.structureActivite && image) {
        return "";
    }

    if (props.structureActivite && disciplineId && classMapping[disciplineId]) {
        return classMapping[disciplineId];
    }

    return defaultClass;
});

const uniqueCriteresByCategorie = computed(() => {
    return props.uniqueCriteresInProducts.filter((critere) => {
        return critere.categorie_id === props.structureActivite.categorie_id;
    });
});

const groupCriteres = (criteres) => {
    return criteres.reduce((grouped, critere) => {
        const critereId = critere.critere_id;
        if (!grouped[critereId]) {
            grouped[critereId] = [];
        }
        grouped[critereId].push(critere);
        return grouped;
    }, {});
};

const formatDate = (dateTime) => {
    return dayjs(dateTime).locale("fr").format("DD MMMM YYYY");
};

const formatTime = (time) => {
    const hours = time.substring(0, 2);
    const minutes = time.substring(3, 5);
    let formattedTime = `${hours}h${minutes}`;
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
    titre: props.structureActivite.titre,
    description: props.structureActivite.description,
    image: null,
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
        },
        {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                formEdit.reset();
                closeEditModal();
            },
        }
    );
};

const convertedActif = ref(!!props.structureActivite.actif);

async function toggleActif(structureActivite) {
    await nextTick();
    router.patch(
        route("structures.activites.toggleactif", {
            structure: props.structure.slug,
            activite: props.structureActivite.id,
        }),
        {
            actif: convertedActif.value,
        },
        {
            preserveScroll: true,
        }
    );
}

const duplicate = (structureActivite, produit) => {
    router.post(
        route("produits.duplicate", {
            structure: props.structure.slug,
            activite: structureActivite.id,
            produit: produit.id,
        }),
        {
            preserveScroll: true,
        }
    );
};

const destroy = (structureActivite, produit) => {
    router.delete(
        route("structures.activites.produits.destroy", {
            structure: props.structure.slug,
            activite: structureActivite.id,
            produit: produit.id,
        }),
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
        route("tarifs.duplicate", {
            structure: props.structure.slug,
            tarif: tarif.id,
            produit: produit.id,
        }),
        {
            preserveScroll: true,
        }
    );
};

const destroyTarif = (tarif) => {
    router.delete(
        route("structures.cat.tarifs.destroy", {
            structure: props.structure.slug,
            tarif: tarif,
        }),
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
        <div class="flex flex-col w-full border border-gray-200 rounded">
            <div class="flex items-center justify-between w-full bg-gray-700">
                <h2 class="px-2 font-semibold text-white uppercase">
                    {{ structureActivite.titre }}
                    <Link
                        class="text-xs italic text-gray-300 lowercase hover:text-gray-100 md:text-sm"
                        :href="
                            route('structures.activites.show', {
                                activite: structureActivite,
                            })
                        "
                    >
                        (voir la fiche)</Link
                    >
                </h2>
                <div class="flex items-center h-full">
                    <button
                        type="button"
                        @click="openEditModal(structureActivite)"
                        class="w-auto h-full p-4 bg-blue-500 hover:bg-blue-600"
                    >
                        <PencilSquareIcon
                            class="w-6 h-6 text-gray-100 hover:text-white"
                        />
                    </button>
                    <button
                        type="button"
                        @click="openDeleteModal(structureActivite)"
                        class="w-auto h-full p-4 bg-red-500 hover:bg-red-600"
                    >
                        <TrashIcon
                            class="w-6 h-6 mr-1 text-gray-100 hover:text-white"
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
                                                as="div"
                                                class="flex items-center justify-between w-full"
                                            >
                                                <h3
                                                    class="text-lg font-medium leading-6 text-gray-800"
                                                >
                                                    Modifier une activité
                                                </h3>
                                                <button type="button">
                                                    <XCircleIcon
                                                        @click="closeEditModal"
                                                        class="w-6 h-6 text-gray-600 hover:text-gray-800"
                                                    />
                                                </button>
                                            </DialogTitle>
                                            <div class="w-full mt-2">
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
                                                                errors.image
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
                                                class="flex items-center justify-between w-full mt-4"
                                            >
                                                <button
                                                    type="button"
                                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                                    @click="closeEditModal"
                                                >
                                                    Annuler
                                                </button>
                                                <button
                                                    :disabled="
                                                        formEdit.processing
                                                    "
                                                    type="submit"
                                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                                >
                                                    <LoadingSVG
                                                        v-if="
                                                            formEdit.processing
                                                        "
                                                    />
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
                    class="w-full h-56 max-w-sm bg-center bg-no-repeat bg-cover bg-slate-100/20 bg-blend-soft-light"
                    :class="headerClass"
                >
                    <img
                        v-if="structureActivite.image"
                        alt="image"
                        :src="structureActivite.image_url"
                        class="object-cover w-auto h-56 max-w-xs"
                    />
                    <!-- <img
                        v-else
                        alt="image"
                        src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                        class="object-cover w-full h-full"
                    />-->
                </div>
                <div
                    class="flex flex-col items-start flex-1 px-2 py-2 space-y-3 md:space-y-6 md:px-4"
                >
                    <SwitchGroup>
                        <div class="flex items-center space-x-2">
                            <Switch
                                v-model="convertedActif"
                                @click="toggleActif(structureActivite)"
                                :class="
                                    convertedActif
                                        ? 'bg-green-600'
                                        : 'bg-gray-200'
                                "
                                class="relative inline-flex items-center h-6 rounded-full w-11"
                            >
                                <span
                                    :class="
                                        convertedActif
                                            ? 'translate-x-6'
                                            : 'translate-x-1'
                                    "
                                    class="inline-block w-4 h-4 transition transform bg-white rounded-full"
                                />
                            </Switch>
                            <SwitchLabel
                                class="text-lg font-semibold"
                                :class="
                                    convertedActif
                                        ? 'text-green-600'
                                        : 'text-gray-600'
                                "
                                >{{
                                    convertedActif ? "Actif" : "Inactif"
                                }}</SwitchLabel
                            >
                        </div>
                    </SwitchGroup>
                    <div class="text-lg">
                        <h4 class="font-semibold">Description:</h4>
                        <p
                            v-if="structureActivite.description"
                            class="break-all whitespace-pre-line"
                        >
                            {{ structureActivite.description }}
                        </p>
                        <p v-else class="break-all whitespace-pre-line">
                            {{
                                structureActivite.structure.presentation_courte
                            }}
                        </p>
                    </div>
                </div>
            </div>
            <Disclosure v-slot="{ open }" defaultOpen>
                <DisclosureButton
                    class="flex justify-between w-full pl-4 font-semibold text-gray-800 bg-gray-200"
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
                            class="w-5 h-5 ml-6 text-gray-800"
                        />
                    </div>

                    <button
                        type="button"
                        @click.prevent="openAddProduitModal(structureActivite)"
                        class="flex items-center justify-center w-auto h-full px-3 py-3 bg-green-500 hover:bg-green-600"
                    >
                        <PlusIcon class="w-6 h-6 text-white" />
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
                        <div class="flex flex-col">
                            <div class="-m-1.5 overflow-x-auto">
                                <div
                                    class="inline-block min-w-full p-1.5 align-middle"
                                >
                                    <div class="overflow-hidden">
                                        <table
                                            class="w-full min-w-full border border-collapse table-auto border-slate-300 md:table-auto"
                                        >
                                            <thead
                                                class="text-xs font-medium tracking-wider uppercase bg-gray-700 text-gray-50"
                                            >
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-xs font-medium text-center uppercase border border-slate-300"
                                                    >
                                                        N°
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-xs font-medium text-center uppercase border border-slate-300"
                                                        v-for="crit in uniqueCriteresByCategorie"
                                                        :key="crit.id"
                                                    >
                                                        {{ crit.nom }}
                                                    </th>

                                                    <th
                                                        scope="col"
                                                        class="px-6 py-3 text-xs font-medium text-center uppercase border border-slate-300"
                                                    >
                                                        Adresse
                                                    </th>
                                                    <th
                                                        colspan="4"
                                                        class="px-6 py-3 border w-80 border-slate-300"
                                                    >
                                                        Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="divide-y divide-gray-200"
                                            >
                                                <template
                                                    v-for="produit in structureActivite.produits"
                                                    :key="produit.id"
                                                >
                                                    <tr
                                                        class="w-full min-w-full border-t odd:bg-white even:bg-gray-50 focus-within:bg-gray-100 hover:bg-gray-100"
                                                    >
                                                        <td
                                                            class="w-16 px-6 py-3 text-sm font-medium text-center text-gray-600 border whitespace-nowrap border-slate-300"
                                                        >
                                                            {{ produit.id }}
                                                        </td>
                                                        <td
                                                            v-for="crit in uniqueCriteresByCategorie"
                                                            :key="crit.id"
                                                            class="px-6 py-3 text-sm font-medium text-center text-gray-600 border whitespace-nowrap border-slate-300"
                                                        >
                                                            <div
                                                                v-for="(
                                                                    groupedCritere,
                                                                    index
                                                                ) in groupCriteres(
                                                                    produit.criteres
                                                                )"
                                                                :key="index"
                                                            >
                                                                <template
                                                                    v-for="(
                                                                        critere,
                                                                        index
                                                                    ) in groupedCritere"
                                                                    :key="
                                                                        critere.id
                                                                    "
                                                                >
                                                                    <ul>
                                                                        <li
                                                                            v-if="
                                                                                critere.critere_id ===
                                                                                crit.id
                                                                            "
                                                                        >
                                                                            <span
                                                                                v-if="
                                                                                    critere.valeur
                                                                                "
                                                                                class="text-sm text-left text-gray-600"
                                                                            >
                                                                                {{
                                                                                    critere.valeur
                                                                                }}
                                                                                <ul
                                                                                    v-if="
                                                                                        critere.sous_criteres &&
                                                                                        critere
                                                                                            .sous_criteres
                                                                                            .length >
                                                                                            0
                                                                                    "
                                                                                    class="text-xs text-gray-600 list-disc list-inside"
                                                                                >
                                                                                    <li
                                                                                        v-for="sousCritere in critere.sous_criteres"
                                                                                        :key="
                                                                                            sousCritere.id
                                                                                        "
                                                                                        class="text-xs text-gray-600"
                                                                                    >
                                                                                        {{
                                                                                            sousCritere.valeur
                                                                                        }}
                                                                                    </li>
                                                                                </ul>
                                                                            </span>
                                                                        </li>
                                                                    </ul>
                                                                </template>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="px-6 py-3 text-sm font-medium text-center text-gray-600 border whitespace-nowrap border-slate-300"
                                                        >
                                                            <div
                                                                class="flex items-center w-full h-full p-2"
                                                            >
                                                                <MapPinIcon
                                                                    class="w-6 h-6 mr-1 text-gray-600"
                                                                />
                                                                <div
                                                                    class="text-xs text-gray-600"
                                                                >
                                                                    {{
                                                                        produit
                                                                            .adresse
                                                                            .address
                                                                    }},
                                                                    {{
                                                                        produit
                                                                            .adresse
                                                                            .zip_code
                                                                    }}
                                                                    {{
                                                                        produit
                                                                            .adresse
                                                                            .city
                                                                    }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="w-20 px-6 py-3 bg-green-600 border border-slate-300 hover:bg-green-500"
                                                        >
                                                            <button
                                                                @click="
                                                                    () =>
                                                                        openTarifToggle(
                                                                            produit
                                                                        )
                                                                "
                                                                type="button"
                                                                class="flex items-center justify-center w-full h-full p-4"
                                                            >
                                                                <CurrencyEuroIcon
                                                                    class="w-6 h-6 text-gray-50 hover:text-white"
                                                                />
                                                            </button>
                                                        </td>
                                                        <td
                                                            class="w-20 px-6 py-3 bg-blue-600 border border-slate-300 hover:bg-blue-500"
                                                        >
                                                            <button
                                                                type="button"
                                                                @click="
                                                                    openEditProduitModal(
                                                                        structureActivite,
                                                                        produit
                                                                    )
                                                                "
                                                                class="flex items-center justify-center w-full h-full p-4"
                                                            >
                                                                <PencilSquareIcon
                                                                    class="w-6 h-6 text-gray-100 hover:text-white"
                                                                />
                                                            </button>
                                                        </td>
                                                        <td
                                                            class="w-20 px-6 py-3 bg-blue-500 border border-slate-300 hover:bg-blue-600"
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
                                                                class="flex items-center justify-center w-full h-full p-4"
                                                            >
                                                                <DocumentDuplicateIcon
                                                                    class="w-6 h-6 text-gray-50 hover:text-white"
                                                                />
                                                            </button>
                                                        </td>
                                                        <td
                                                            class="w-20 px-6 py-3 bg-red-500 border border-slate-300 hover:bg-red-600"
                                                        >
                                                            <button
                                                                type="button"
                                                                @click="
                                                                    () =>
                                                                        destroy(
                                                                            structureActivite,
                                                                            produit
                                                                        )
                                                                "
                                                                class="flex items-center justify-center w-full h-full p-4"
                                                            >
                                                                <TrashIcon
                                                                    class="w-6 h-6 text-gray-100 hover:text-white"
                                                                />
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        v-if="
                                                            isOpenTarif(produit)
                                                        "
                                                        class="border-b border-gray-200"
                                                    >
                                                        <td
                                                            :colspan="
                                                                uniqueCriteresByCategorie.length +
                                                                5
                                                            "
                                                        >
                                                            <div
                                                                class="flex items-center justify-between w-full px-2 py-2 text-sm font-semibold text-gray-700 whitespace-nowrap md:px-4"
                                                            >
                                                                <p
                                                                    v-if="
                                                                        produit
                                                                            .cat_tarifs
                                                                            .length >
                                                                        0
                                                                    "
                                                                >
                                                                    Liste des
                                                                    tarifs pour
                                                                    ce produit:
                                                                </p>
                                                                <p v-else>
                                                                    Pas encore
                                                                    de tarif
                                                                    pour ce
                                                                    produit.
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="px-6 py-3 bg-green-600 border border-slate-300 hover:bg-green-500"
                                                        >
                                                            <button
                                                                type="button"
                                                                @click="
                                                                    openAddTarifModal
                                                                "
                                                                class="flex items-center justify-center w-full h-full p-4 text-xs text-white"
                                                            >
                                                                Ajouter un tarif
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <template
                                                        v-if="
                                                            isOpenTarif(
                                                                produit
                                                            ) &&
                                                            produit.cat_tarifs
                                                                .length > 0
                                                        "
                                                    >
                                                        <tr
                                                            v-for="tarif in produit.cat_tarifs"
                                                            :key="tarif.id"
                                                            class="w-full border-t"
                                                        >
                                                            <td
                                                                class="px-6 py-3 text-sm text-center text-gray-600 border border-slate-300"
                                                            >
                                                                {{ tarif.id }}
                                                            </td>

                                                            <td
                                                                class="px-6 py-3 text-sm text-center text-gray-600 border border-slate-300"
                                                            >
                                                                {{
                                                                    tarif.titre
                                                                }}
                                                            </td>
                                                            <td
                                                                :colspan="
                                                                    uniqueCriteresByCategorie.length -
                                                                    1
                                                                "
                                                                class="px-6 py-3 text-xs text-left text-gray-600 border border-slate-300"
                                                            >
                                                                <template
                                                                    v-if="
                                                                        tarif.attributs
                                                                    "
                                                                >
                                                                    <ul
                                                                        class="flex justify-around list-disc list-inside"
                                                                    >
                                                                        <li
                                                                            v-for="attribut in tarif.attributs"
                                                                            :key="
                                                                                attribut.id
                                                                            "
                                                                            class="items-start"
                                                                        >
                                                                            <span>
                                                                                {{
                                                                                    attribut
                                                                                        .tarif_attribut
                                                                                        .nom
                                                                                }}:
                                                                                <span
                                                                                    class="font-semibold"
                                                                                    >{{
                                                                                        attribut.valeur
                                                                                    }}</span
                                                                                ></span
                                                                            >
                                                                            <template
                                                                                v-if="
                                                                                    attribut.sous_attributs
                                                                                "
                                                                            >
                                                                                <ul
                                                                                    class="flex flex-col justify-start ml-2 list-disc list-inside"
                                                                                >
                                                                                    <li
                                                                                        v-for="sousattr in attribut.sous_attributs"
                                                                                        :key="
                                                                                            sousattr.id
                                                                                        "
                                                                                        class=""
                                                                                    >
                                                                                        <span
                                                                                            v-if="
                                                                                                sousattr.sous_attribut_valeur
                                                                                            "
                                                                                        >
                                                                                            {{
                                                                                                sousattr
                                                                                                    .sous_attribut
                                                                                                    .nom
                                                                                            }}:
                                                                                            <span
                                                                                                class="font-semibold"
                                                                                                >{{
                                                                                                    sousattr
                                                                                                        .sous_attribut_valeur
                                                                                                        .valeur
                                                                                                }}</span
                                                                                            >
                                                                                        </span>
                                                                                        <span
                                                                                            v-else
                                                                                            >{{
                                                                                                sousattr
                                                                                                    .sous_attribut
                                                                                                    .nom
                                                                                            }}:
                                                                                            <span
                                                                                                class="font-semibold"
                                                                                                >{{
                                                                                                    sousattr.valeur
                                                                                                }}</span
                                                                                            >
                                                                                        </span>
                                                                                    </li>
                                                                                </ul>
                                                                            </template>
                                                                        </li>
                                                                    </ul>
                                                                </template>
                                                            </td>
                                                            <td
                                                                class="text-sm text-center text-gray-600 border border-slate-300"
                                                            >
                                                                {{
                                                                    tarif
                                                                        .cat_tarif_type
                                                                        .nom
                                                                }}
                                                            </td>
                                                            <td
                                                                class="px-6 py-3 text-sm text-center text-gray-600 border border-slate-300"
                                                            >
                                                                {{
                                                                    formatCurrency(
                                                                        tarif.amount
                                                                    )
                                                                }}
                                                            </td>
                                                            <td
                                                                class="px-6 py-3 bg-blue-600 border border-slate-300 hover:bg-blue-500"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    @click="
                                                                        openEditTarifModal(
                                                                            tarif
                                                                        )
                                                                    "
                                                                    class="flex items-center justify-center w-full h-full p-4"
                                                                >
                                                                    <ArrowPathIcon
                                                                        class="w-6 h-6 mr-1 transition-all duration-200 text-gray-50 hover:-rotate-90 hover:text-white"
                                                                    />
                                                                </button>
                                                            </td>
                                                            <td
                                                                class="px-6 py-3 bg-blue-500 border border-slate-300 hover:bg-blue-600"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    disabled
                                                                    @click="
                                                                        () =>
                                                                            duplicateTarif(
                                                                                tarif,
                                                                                produit
                                                                            )
                                                                    "
                                                                    class="flex items-center justify-center w-full h-full p-4"
                                                                >
                                                                    <DocumentDuplicateIcon
                                                                        class="w-6 h-6 mr-1 text-gray-50 hover:text-white"
                                                                    />
                                                                </button>
                                                            </td>
                                                            <td
                                                                class="px-6 py-3 bg-red-500 border border-slate-300 hover:bg-red-600"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    @click="
                                                                        () =>
                                                                            destroyTarif(
                                                                                tarif,
                                                                                produit
                                                                            )
                                                                    "
                                                                    class="flex items-center justify-center w-full h-full p-4"
                                                                >
                                                                    <TrashIcon
                                                                        class="w-6 h-6 mr-1 text-gray-100 hover:text-white"
                                                                    />
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </template>
                                            </tbody>
                                        </table>
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
        :structure-activite="structureActivite"
        :show="showDeleteActiviteModal"
        @close="showDeleteActiviteModal = false"
    />
    <ModalAddProduit
        :errors="errors"
        :structure="structure"
        :structure-activite="structureActivite"
        :show="showAddProduitModal"
        @close="showAddProduitModal = false"
        :criteres="criteres"
        :latest-adresse-id="latestAdresseId"
    />
    <ModalEditProduit
        :errors="errors"
        :structure="structure"
        :structure-activite="structureActivite"
        :produit="currentProduit"
        :show="showEditProduitModal"
        @close="showEditProduitModal = false"
        :criteres="criteres"
        :latest-adresse-id="latestAdresseId"
    />

    <ModalEditCatTarif
        :errors="errors"
        :structure="structure"
        :tarif-to-update="currentTarif"
        :all-categories="allCategories"
        :activite-for-tarifs="activiteForTarifs"
        :show="showEditTarifModal"
        @close="showEditTarifModal = false"
    />
</template>
