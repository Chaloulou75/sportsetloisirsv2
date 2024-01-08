<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { router } from "@inertiajs/vue3";
import {
    TrashIcon,
    UsersIcon,
    UserGroupIcon,
    ClockIcon,
    ArrowPathIcon,
    PencilSquareIcon,
} from "@heroicons/vue/24/outline";

const ModalEditCatTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditCatTarif.vue")
);
const ModalEditTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditTarif.vue")
);

const props = defineProps({
    errors: Object,
    structure: Object,
    allCategories: Object,
    tarifTypes: Object,
    strCatTarifs: Object,
    activiteForTarifs: Object,
    structureActivites: Object,
});

//newest version
const tarifToUpdate = ref(null);
const showEditCatTarifModal = ref(false);

const openEditCatTarifModal = (catTarif) => {
    tarifToUpdate.value = catTarif;
    showEditCatTarifModal.value = true;
};

const destroyCatTarif = (catTarif) => {
    router.delete(
        route("structures.cat.tarifs.destroy", {
            structure: props.structure.slug,
            tarif: catTarif.id,
        }),
        {
            preserveScroll: true,
        }
    );
};

// Old version
const tarifsList = computed(() => {
    const tarifs = [];
    for (const structureActivite of props.structureActivites) {
        if (structureActivite.produits.length > 0) {
            for (const produit of structureActivite.produits) {
                for (const tarif of produit.tarifs) {
                    tarifs.push(tarif);
                }
            }
        }
    }
    return tarifs;
});

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
const currentTarif = ref(null);
const showEditTarifModal = ref(false);

const openEditTarifModal = (tarif) => {
    currentTarif.value = tarif;
    showEditTarifModal.value = true;
};

const destroyTarif = (tarif) => {
    router.delete(
        route("tarifs.destroyTarif", {
            structure: props.structure.slug,
            tarif: tarif.id,
        }),
        {
            preserveScroll: true,
        }
    );
};
</script>
<template>
    <div v-if="strCatTarifs.length > 0" class="overflow-x-auto">
        <table
            class="min-w-full table-auto border-collapse divide-y divide-gray-200 border border-slate-300"
        >
            <thead
                class="bg-gray-700 text-xs font-medium uppercase tracking-wider text-gray-50"
            >
                <tr>
                    <th class="hidden px-4 py-2 text-left lg:table-cell">
                        Discipline
                    </th>
                    <th class="px-4 py-2 text-left">Categorie</th>
                    <th class="hidden px-4 py-2 text-left lg:table-cell">
                        Type de tarif
                    </th>
                    <th class="px-4 py-2 text-left">Titre</th>
                    <th class="hidden px-4 py-2 text-left lg:table-cell">
                        Description
                    </th>
                    <th class="px-4 py-2 text-left">Montant</th>
                    <th class="px-4 py-2 text-left">Attributs</th>
                    <th class="px-4 py-2 text-left">Produits</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody
                class="divide-y divide-gray-200 bg-white text-xs text-slate-800"
            >
                <tr
                    v-for="catTarif in strCatTarifs"
                    :key="catTarif.id"
                    class="odd:bg-white even:bg-gray-50"
                >
                    <td
                        class="hidden max-w-0 truncate whitespace-nowrap border border-slate-300 px-4 py-2 font-semibold lg:table-cell"
                    >
                        {{ catTarif.categorie.discipline.name }}
                    </td>
                    <td
                        class="whitespace-nowrap border border-slate-300 px-4 py-2 font-semibold"
                    >
                        <dl class="lg:hidden">
                            <dt class="sr-only">Discipline</dt>
                            <dd class="text-slate-600">
                                {{ catTarif.categorie.discipline.name }}
                            </dd>
                        </dl>
                        {{ catTarif.categorie.nom_categorie_client }}
                        <dl class="lg:hidden">
                            <dt class="sr-only">Type de tarif</dt>
                            <dd class="text-slate-700">
                                {{ catTarif.cat_tarif_type.nom }}
                            </dd>
                        </dl>
                    </td>
                    <td
                        class="hidden max-w-0 truncate whitespace-nowrap border border-slate-300 px-4 py-2 font-semibold lg:table-cell"
                    >
                        {{ catTarif.cat_tarif_type.nom }}
                    </td>
                    <td
                        class="max-w-0 truncate whitespace-nowrap border border-slate-300 px-4 py-2"
                    >
                        {{ catTarif.titre }}
                    </td>
                    <td
                        class="hidden max-w-0 truncate whitespace-nowrap border border-slate-300 px-4 py-2 lg:table-cell"
                    >
                        {{ catTarif.description }}
                    </td>
                    <td
                        class="whitespace-nowrap border border-slate-300 px-4 py-2 font-semibold"
                    >
                        {{ formatCurrency(catTarif.amount) }}
                    </td>
                    <td
                        class="whitespace-normal border border-slate-300 px-4 py-2"
                    >
                        <template v-if="catTarif.attributs">
                            <ul class="list-inside list-disc">
                                <li
                                    v-for="attribut in catTarif.attributs"
                                    :key="attribut.id"
                                    class="mb-2"
                                >
                                    <span>
                                        {{ attribut.tarif_attribut.nom }}:
                                        <span class="font-semibold">{{
                                            attribut.valeur
                                        }}</span></span
                                    >
                                    <template v-if="attribut.sous_attributs">
                                        <ul class="list-inside list-disc">
                                            <li
                                                v-for="sousattr in attribut.sous_attributs"
                                                :key="sousattr.id"
                                                class="pl-4"
                                            >
                                                <span
                                                    v-if="
                                                        sousattr.sous_attribut_valeur
                                                    "
                                                >
                                                    {{
                                                        sousattr.sous_attribut
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
                                                <span v-else
                                                    >{{
                                                        sousattr.sous_attribut
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
                        class="whitespace-nowrap border border-slate-300 px-4 py-2 font-semibold"
                    >
                        <template v-if="catTarif.produits">
                            <ul class="list-inside list-disc">
                                <li
                                    v-for="produit in catTarif.produits"
                                    :key="produit.id"
                                    class="mb-2"
                                >
                                    {{ produit.id }}
                                </li>
                            </ul>
                        </template>
                    </td>
                    <td
                        class="whitespace-nowrap border border-slate-300 px-4 py-2"
                    >
                        <button
                            type="button"
                            @click="openEditCatTarifModal(catTarif)"
                        >
                            <PencilSquareIcon
                                class="mr-1 h-6 w-6 text-slate-500 hover:text-slate-700"
                            />
                        </button>

                        <button
                            type="button"
                            @click="() => destroyCatTarif(catTarif)"
                        >
                            <TrashIcon
                                class="mr-1 h-6 w-6 text-red-400 hover:text-red-600"
                            />
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div
        v-if="
            structure.tarifs.length === 0 &&
            tarifsList.length === 0 &&
            !strCatTarifs.length > 0
        "
    >
        <p class="font-semibold italic text-gray-600">
            Pas de tarif associé à cette structure
        </p>
    </div>
    <div
        v-if="
            structure.tarifs.length > 0 &&
            route().current('structures.disciplines.index', structure)
        "
        class="mt-6 w-full overflow-x-auto rounded-xl shadow-lg"
    >
        <table
            class="w-full table-fixed border-collapse text-sm font-semibold text-gray-700 md:table-auto"
        >
            <caption
                class="caption-top bg-slate-50 py-6 text-sm font-semibold text-slate-600"
            >
                Liste des tarifs de la structure:
            </caption>
            <thead class="bg-slate-50">
                <tr class="border-b text-center font-medium text-slate-400">
                    <th class="p-5">Infos</th>
                    <th class="p-5">Titre</th>
                    <th class="p-5">Type</th>
                    <th class="p-5">Description</th>
                    <th class="p-5">Montant</th>
                    <th class="p-5">Gestion</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="border-b border-slate-100 text-center text-slate-500"
                    v-for="tarif in structure.tarifs"
                    :key="tarif.id"
                >
                    <td class="flex flex-col items-center justify-center p-5">
                        <div
                            v-for="info in tarif.structure_tarif_type_infos"
                            :key="info.id"
                            class="inline-flex items-center justify-center space-y-2"
                        >
                            <ClockIcon
                                v-if="[1, 2, 5, 7].includes(info.attribut_id)"
                                class="mr-1 h-5 w-5"
                            />
                            <UserGroupIcon
                                v-else-if="[3, 6].includes(info.attribut_id)"
                                class="mr-1 h-5 w-5 text-slate-500"
                            />
                            <UsersIcon
                                v-else-if="[4].includes(info.attribut_id)"
                                class="mr-1 h-5 w-5"
                            />

                            <UsersIcon v-else class="mr-1 h-5 w-5" />

                            <span v-if="info.valeur" class="text-sm font-thin">
                                {{ info.tarif_type_attribut.attribut }}:
                                {{ info.valeur }}
                                <span v-if="info.unite">{{ info.unite }}</span>
                            </span>
                            <span v-else class="text-sm font-thin"
                                >Pas de valeur</span
                            >
                        </div>
                    </td>
                    <td class="p-5">{{ tarif.titre }}</td>
                    <td class="p-5">{{ tarif.tarif_type.type }}</td>
                    <td class="p-5">
                        <p
                            class="line-clamp-1 w-36 overflow-hidden text-ellipsis"
                        >
                            {{ tarif.description }}
                        </p>
                    </td>
                    <td class="p-5">{{ formatCurrency(tarif.amount) }}</td>
                    <td class="flex p-5">
                        <button
                            type="button"
                            @click="openEditTarifModal(tarif)"
                        >
                            <ArrowPathIcon
                                class="mr-1 h-6 w-6 text-slate-400 transition-all duration-200 hover:-rotate-90 hover:text-slate-600"
                            />
                        </button>

                        <button
                            type="button"
                            @click="() => destroyTarif(tarif)"
                        >
                            <TrashIcon
                                class="mr-1 h-6 w-6 text-slate-400 hover:text-slate-600"
                            />
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div
        v-if="tarifsList.length > 0"
        class="mt-12 w-full overflow-x-auto rounded-xl shadow-lg"
    >
        <table
            class="w-full table-fixed border-collapse text-sm font-semibold text-gray-700 md:table-auto"
        >
            <caption
                class="caption-top bg-slate-50 py-6 text-sm font-semibold text-slate-600"
            >
                Liste des tarifs par produit de votre activité:
            </caption>
            <thead class="bg-slate-50">
                <tr class="border-b text-center font-medium text-slate-400">
                    <th class="p-5">Infos</th>
                    <th class="p-5">Titre</th>
                    <th class="p-5">Produit associé</th>
                    <th class="p-5">Type</th>
                    <th class="p-5">Description</th>
                    <th class="p-5">Montant</th>
                    <th class="p-5">Gestion</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="border-b border-slate-100 text-center text-slate-500"
                    v-for="tarif in tarifsList"
                    :key="tarif.id"
                >
                    <td class="flex flex-col items-center justify-center p-5">
                        <div
                            v-for="info in tarif.structure_tarif_type_infos"
                            :key="info.id"
                            class="inline-flex items-center justify-center space-y-2"
                        >
                            <ClockIcon
                                v-if="[1, 2, 5, 7].includes(info.attribut_id)"
                                class="mr-1 h-5 w-5"
                            />
                            <UserGroupIcon
                                v-else-if="[3, 6].includes(info.attribut_id)"
                                class="mr-1 h-5 w-5 text-slate-500"
                            />
                            <UsersIcon
                                v-else-if="[4].includes(info.attribut_id)"
                                class="mr-1 h-5 w-5"
                            />

                            <UsersIcon v-else class="mr-1 h-5 w-5" />

                            <span v-if="info.valeur" class="text-sm font-thin">
                                {{ info.tarif_type_attribut.attribut }}:
                                {{ info.valeur }}
                                <span v-if="info.unite">{{ info.unite }}</span>
                            </span>
                            <span v-else class="text-sm font-thin"
                                >Pas de valeur</span
                            >
                        </div>
                    </td>
                    <td class="p-5">{{ tarif.titre }}</td>
                    <td class="p-5">N° {{ tarif.pivot.produit_id }}</td>
                    <td class="p-5">{{ tarif.tarif_type.type }}</td>
                    <td class="p-5">
                        <p
                            class="line-clamp-1 w-36 overflow-hidden text-ellipsis"
                        >
                            {{ tarif.description }}
                        </p>
                    </td>
                    <td class="p-5">{{ formatCurrency(tarif.amount) }}</td>
                    <td class="p-5">
                        <button
                            type="button"
                            @click="openEditTarifModal(tarif)"
                        >
                            <ArrowPathIcon
                                class="mr-1 h-6 w-6 text-slate-400 transition-all duration-200 hover:-rotate-90 hover:text-slate-600"
                            />
                        </button>

                        <button
                            type="button"
                            @click="() => destroyTarif(tarif)"
                        >
                            <TrashIcon
                                class="mr-1 h-6 w-6 text-slate-400 hover:text-slate-600"
                            />
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <ModalEditCatTarif
        :errors="errors"
        :structure="structure"
        :tarif-to-update="tarifToUpdate"
        :all-categories="allCategories"
        :activite-for-tarifs="activiteForTarifs"
        :show="showEditCatTarifModal"
        @close="showEditCatTarifModal = false"
    />
    <ModalEditTarif
        :errors="errors"
        :structure="structure"
        :tarif="currentTarif"
        :all-categories="allCategories"
        :tarif-types="tarifTypes"
        :activite-for-tarifs="activiteForTarifs"
        :show="showEditTarifModal"
        @close="showEditTarifModal = false"
    />
</template>
