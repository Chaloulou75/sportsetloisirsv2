<script setup>
import { classMapping } from "@/Utils/classMapping.js";
import { ref, computed, nextTick, defineAsyncComponent } from "vue";
import { router, Link } from "@inertiajs/vue3";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import localeData from "dayjs/plugin/localeData";
import {
    ArrowPathIcon,
    MapPinIcon,
    ChevronUpIcon,
    DocumentDuplicateIcon,
    TrashIcon,
    PlusIcon,
    PencilSquareIcon,
    CurrencyEuroIcon,
    ClockIcon,
} from "@heroicons/vue/24/outline";
import {
    Switch,
    SwitchGroup,
    SwitchLabel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    TransitionRoot,
} from "@headlessui/vue";
dayjs.locale("fr");
dayjs.extend(localeData);

const emit = defineEmits(["addTarif", "addPlanning"]);

const openAddTarifModal = (produit) => {
    emit("addTarif", produit);
};

const openAddPlanningModal = (produit) => {
    emit("addPlanning", produit);
};

const ModalEditActivite = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditActivite.vue")
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

const ModalEditCatTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditCatTarif.vue")
);

const props = defineProps({
    activite: Object,
    structure: Object,
    errors: Object,
    uniqueCriteresInProducts: Object,
    criteres: Object,
    latestAdresseId: Number,
    tarifTypes: Object,
    allCategories: Object,
});

const isShowing = ref(true);
const currentProduit = ref(null);
const showEditActiviteModal = ref(false);
const showDeleteActiviteModal = ref(false);
const showAddProduitModal = ref(false);
const showEditProduitModal = ref(false);
const openTarif = ref(false);
const currentTarif = ref(null);
const showEditTarifModal = ref(false);

const headerClass = computed(() => {
    const defaultClass = "bg-la-base";
    const image = props.activite?.image;
    const disciplineId = props.activite?.discipline_id;

    if (props.activite && image) {
        return "";
    }

    if (props.activite && disciplineId && classMapping[disciplineId]) {
        return classMapping[disciplineId];
    }

    return defaultClass;
});

const uniqueCriteresByCategorie = computed(() => {
    return props.uniqueCriteresInProducts.filter((critere) => {
        return critere.categorie_id === props.activite.categorie_id;
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

function decodeValue(valeur) {
    try {
        return JSON.parse(valeur);
    } catch (e) {
        console.log("Failed to parse JSON", e);
        return [];
    }
}

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

const openDeleteModal = () => {
    showDeleteActiviteModal.value = true;
};

const openAddProduitModal = () => {
    showAddProduitModal.value = true;
};

const openEditProduitModal = (produit) => {
    currentProduit.value = produit;
    showEditProduitModal.value = true;
};

const openEditTarifModal = (tarif) => {
    currentTarif.value = tarif;
    showEditTarifModal.value = true;
};

const openEditModal = () => {
    showEditActiviteModal.value = true;
};

const convertedActif = ref(!!props.activite.actif);

async function toggleActif() {
    await nextTick();
    router.patch(
        route("structures.activites.toggleactif", {
            structure: props.structure.slug,
            activite: props.activite.id,
        }),
        {
            actif: convertedActif.value,
        },
        {
            preserveScroll: true,
        }
    );
}

const duplicate = (produit) => {
    router.post(
        route("produits.duplicate", {
            structure: props.structure.slug,
            activite: props.activite.id,
            produit: produit.id,
        }),
        {
            preserveScroll: true,
        }
    );
};

const destroy = (produit) => {
    router.delete(
        route("structures.activites.produits.destroy", {
            structure: props.structure.slug,
            activite: props.activite.id,
            produit: produit.id,
        }),
        {
            preserveScroll: true,
        }
    );
};

const openTarifToggle = (produit) => {
    currentProduit.value = produit;
    if (currentProduit.value.cat_tarifs.length === 0) {
        openAddTarifModal(produit);
    } else {
        openTarif.value = !openTarif.value;
    }
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
        <div class="flex w-full flex-col ring ring-blue-300">
            <div class="flex w-full items-center justify-between bg-gray-700">
                <h2 class="px-2 font-semibold uppercase text-white">
                    {{ activite.titre }}
                    <Link
                        class="text-xs lowercase italic text-gray-300 hover:text-gray-100 md:text-sm"
                        :href="
                            route('structures.activites.show', {
                                activite: activite,
                                slug: activite.slug_title,
                            })
                        "
                    >
                        (voir la fiche)</Link
                    >
                </h2>
                <div class="flex h-full items-center">
                    <button
                        type="button"
                        @click="openEditModal()"
                        class="h-full w-auto bg-blue-500 p-4 hover:bg-blue-600"
                    >
                        <PencilSquareIcon
                            class="h-6 w-6 text-gray-100 hover:text-white"
                        />
                    </button>
                    <button
                        type="button"
                        @click="openDeleteModal(activite)"
                        class="h-full w-auto bg-red-500 p-4 hover:bg-red-600"
                    >
                        <TrashIcon
                            class="mr-1 h-6 w-6 text-gray-100 hover:text-white"
                        />
                    </button>
                </div>
            </div>
            <div class="flex w-full flex-col items-start md:flex-row">
                <div
                    class="flex h-56 w-full items-center justify-center bg-slate-100/20 bg-cover bg-center bg-no-repeat bg-blend-soft-light md:max-w-xs"
                    :class="headerClass"
                >
                    <img
                        v-if="activite.image"
                        alt="activite"
                        :src="activite.image_url"
                        class="h-auto max-h-56 w-auto max-w-xs object-cover"
                    />
                </div>
                <div
                    class="flex flex-1 flex-col items-start space-y-3 px-2 py-2 md:space-y-6 md:px-4"
                >
                    <SwitchGroup>
                        <div class="flex items-center space-x-2">
                            <Switch
                                v-model="convertedActif"
                                @click="toggleActif()"
                                :class="
                                    convertedActif
                                        ? 'bg-green-600'
                                        : 'bg-gray-200'
                                "
                                class="relative inline-flex h-6 w-11 items-center rounded-full"
                            >
                                <span
                                    :class="
                                        convertedActif
                                            ? 'translate-x-6'
                                            : 'translate-x-1'
                                    "
                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition"
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
                            v-if="activite.description"
                            class="whitespace-pre-line break-all"
                        >
                            {{ activite.description }}
                        </p>
                        <p v-else class="whitespace-pre-line break-all">
                            {{ structure.presentation_courte }}
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
                            {{ activite.produits.length }}
                            <span v-if="activite.produits.length > 1"
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
                        @click.prevent="openAddProduitModal()"
                        class="flex h-full w-auto items-center justify-center bg-green-500 px-3 py-3 hover:bg-green-400"
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
                        <div class="flex flex-col">
                            <div class="overflow-x-auto">
                                <div
                                    class="inline-block min-w-full align-middle"
                                >
                                    <div class="overflow-hidden">
                                        <table
                                            class="w-full max-w-full table-auto border-collapse border border-slate-300"
                                        >
                                            <thead
                                                class="bg-gray-700 text-xs font-medium uppercase tracking-wider text-gray-50"
                                            >
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="border border-slate-300 px-3 py-3 text-center text-xs font-medium uppercase"
                                                    >
                                                        N°
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="border border-slate-300 px-3 py-3 text-center text-xs font-medium uppercase"
                                                        v-for="crit in uniqueCriteresByCategorie"
                                                        :key="crit.id"
                                                    >
                                                        {{ crit.nom }}
                                                    </th>

                                                    <th
                                                        scope="col"
                                                        class="border border-slate-300 px-3 py-3 text-center text-xs font-medium uppercase"
                                                    >
                                                        Adresse
                                                    </th>
                                                    <th
                                                        colspan="5"
                                                        class="w-80 border border-slate-300 px-3 py-3"
                                                    >
                                                        Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="divide-y divide-gray-200"
                                            >
                                                <template
                                                    v-for="produit in activite.produits"
                                                    :key="produit.id"
                                                >
                                                    <tr
                                                        class="w-full min-w-full border-t odd:bg-white even:bg-gray-50 focus-within:bg-gray-100 hover:bg-gray-100"
                                                    >
                                                        <td
                                                            class="w-12 whitespace-nowrap border border-slate-300 px-3 py-3 text-center text-sm font-medium text-gray-600"
                                                        >
                                                            {{ produit.id }}
                                                        </td>
                                                        <td
                                                            v-for="crit in uniqueCriteresByCategorie"
                                                            :key="crit.id"
                                                            class="whitespace-nowrap border border-slate-300 px-3 py-3 text-center text-sm font-medium text-gray-600"
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
                                                                                class="text-left text-sm text-gray-600"
                                                                            >
                                                                                {{
                                                                                    critere.valeur
                                                                                }}
                                                                                <template
                                                                                    v-if="
                                                                                        critere.sous_criteres &&
                                                                                        critere
                                                                                            .sous_criteres
                                                                                            .length >
                                                                                            0
                                                                                    "
                                                                                >
                                                                                    <ul
                                                                                        v-for="sousCrit in critere.sous_criteres"
                                                                                        :key="
                                                                                            sousCrit.id
                                                                                        "
                                                                                        class="ml-1 text-xs"
                                                                                    >
                                                                                        <span
                                                                                            class="font-semibold"
                                                                                            >{{
                                                                                                sousCrit
                                                                                                    .sous_critere
                                                                                                    .nom
                                                                                            }}:</span
                                                                                        >
                                                                                        <li>
                                                                                            {{
                                                                                                sousCrit.valeur
                                                                                            }}
                                                                                        </li>
                                                                                    </ul>
                                                                                </template>
                                                                            </span>
                                                                        </li>
                                                                    </ul>
                                                                </template>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap border border-slate-300 px-3 py-3 text-center text-sm font-medium text-gray-600"
                                                        >
                                                            <div
                                                                class="flex h-full w-full items-center p-2"
                                                            >
                                                                <MapPinIcon
                                                                    class="mr-1 h-6 w-6 text-gray-600"
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
                                                            class="border border-slate-300 bg-green-500 px-3 py-3 hover:bg-green-400"
                                                        >
                                                            <button
                                                                @click="
                                                                    () =>
                                                                        openTarifToggle(
                                                                            produit
                                                                        )
                                                                "
                                                                type="button"
                                                                class="flex h-full w-full items-center justify-center p-4"
                                                            >
                                                                <CurrencyEuroIcon
                                                                    class="h-6 w-6 text-gray-50 hover:text-white"
                                                                />
                                                            </button>
                                                        </td>
                                                        <td
                                                            class="border border-slate-300 bg-slate-400 px-3 py-3 hover:bg-slate-500"
                                                        >
                                                            <button
                                                                @click="
                                                                    openAddPlanningModal(
                                                                        produit
                                                                    )
                                                                "
                                                                type="button"
                                                                class="flex h-full w-full items-center justify-center p-4"
                                                            >
                                                                <ClockIcon
                                                                    class="h-6 w-6 text-gray-50 hover:text-white"
                                                                />
                                                            </button>
                                                        </td>
                                                        <td
                                                            class="border border-slate-300 bg-blue-600 px-3 py-3 hover:bg-blue-500"
                                                        >
                                                            <button
                                                                type="button"
                                                                @click="
                                                                    openEditProduitModal(
                                                                        produit
                                                                    )
                                                                "
                                                                class="flex h-full w-full items-center justify-center p-4"
                                                            >
                                                                <PencilSquareIcon
                                                                    class="h-6 w-6 text-gray-100 hover:text-white"
                                                                />
                                                            </button>
                                                        </td>
                                                        <td
                                                            class="border border-slate-300 bg-blue-500 px-3 py-3 hover:bg-blue-600"
                                                        >
                                                            <button
                                                                type="button"
                                                                @click="
                                                                    () =>
                                                                        duplicate(
                                                                            produit
                                                                        )
                                                                "
                                                                class="flex h-full w-full items-center justify-center p-4"
                                                            >
                                                                <DocumentDuplicateIcon
                                                                    class="h-6 w-6 text-gray-50 hover:text-white"
                                                                />
                                                            </button>
                                                        </td>
                                                        <td
                                                            class="border border-slate-300 bg-red-500 px-3 py-3 hover:bg-red-600"
                                                        >
                                                            <button
                                                                type="button"
                                                                @click="
                                                                    () =>
                                                                        destroy(
                                                                            produit
                                                                        )
                                                                "
                                                                class="flex h-full w-full items-center justify-center p-4"
                                                            >
                                                                <TrashIcon
                                                                    class="h-6 w-6 text-gray-100 hover:text-white"
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
                                                                6
                                                            "
                                                        >
                                                            <div
                                                                class="flex w-full items-center justify-between whitespace-nowrap px-2 py-2 text-sm font-semibold text-gray-700 md:px-4"
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
                                                            class="group border border-slate-300 bg-green-500 px-3 py-3 group-hover:bg-green-400"
                                                        >
                                                            <button
                                                                type="button"
                                                                @click="
                                                                    openAddTarifModal(
                                                                        produit
                                                                    )
                                                                "
                                                                class="flex h-full w-full max-w-16 items-center justify-center text-center text-xs text-white"
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
                                                                class="border border-slate-300 px-3 py-3 text-center text-sm text-gray-600"
                                                            >
                                                                {{ tarif.id }}
                                                            </td>

                                                            <td
                                                                class="border border-slate-300 px-3 py-3 text-center text-sm text-gray-600"
                                                            >
                                                                {{
                                                                    tarif.titre
                                                                }}
                                                            </td>
                                                            <td
                                                                :colspan="
                                                                    uniqueCriteresByCategorie.length
                                                                "
                                                                class="border border-slate-300 px-3 py-3 text-left text-xs text-gray-600"
                                                            >
                                                                <template
                                                                    v-if="
                                                                        tarif.attributs
                                                                    "
                                                                >
                                                                    <ul
                                                                        class="flex list-inside list-disc justify-around"
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
                                                                                    class="ml-2 flex list-inside list-disc flex-col justify-start"
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
                                                                class="border border-slate-300 text-center text-sm text-gray-600"
                                                            >
                                                                {{
                                                                    tarif
                                                                        .cat_tarif_type
                                                                        .nom
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-slate-300 px-3 py-3 text-center text-sm text-gray-600"
                                                            >
                                                                {{
                                                                    formatCurrency(
                                                                        tarif.amount
                                                                    )
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border border-slate-300 bg-blue-600 px-3 py-3 hover:bg-blue-500"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    @click="
                                                                        openEditTarifModal(
                                                                            tarif
                                                                        )
                                                                    "
                                                                    class="flex h-full w-full items-center justify-center p-4"
                                                                >
                                                                    <ArrowPathIcon
                                                                        class="mr-1 h-6 w-6 text-gray-50 transition-all duration-200 hover:-rotate-90 hover:text-white"
                                                                    />
                                                                </button>
                                                            </td>
                                                            <td
                                                                class="border border-slate-300 bg-blue-500 px-3 py-3 hover:bg-blue-600"
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
                                                                    class="flex h-full w-full items-center justify-center p-4"
                                                                >
                                                                    <DocumentDuplicateIcon
                                                                        class="mr-1 h-6 w-6 text-gray-50 hover:text-white"
                                                                    />
                                                                </button>
                                                            </td>
                                                            <td
                                                                class="border border-slate-300 bg-red-500 px-3 py-3 hover:bg-red-600"
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
                                                                    class="flex h-full w-full items-center justify-center p-4"
                                                                >
                                                                    <TrashIcon
                                                                        class="mr-1 h-6 w-6 text-gray-100 hover:text-white"
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

    <ModalEditActivite
        :activite="activite"
        :structure="structure"
        :show="showEditActiviteModal"
        @close="showEditActiviteModal = false"
    />

    <ModalDeleteActivite
        :structure="structure"
        :activite="activite"
        :show="showDeleteActiviteModal"
        @close="showDeleteActiviteModal = false"
    />
    <ModalAddProduit
        :errors="errors"
        :structure="structure"
        :activite="activite"
        :show="showAddProduitModal"
        @close="showAddProduitModal = false"
        :criteres="criteres"
        :latest-adresse-id="latestAdresseId"
    />
    <ModalEditProduit
        :errors="errors"
        :structure="structure"
        :activite="activite"
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
        :show="showEditTarifModal"
        @close="showEditTarifModal = false"
    />
</template>
