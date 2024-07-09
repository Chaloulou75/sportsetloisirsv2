<script setup>
import { ref, defineAsyncComponent } from "vue";
import { router } from "@inertiajs/vue3";
import { TrashIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["showDisplay"]);

const ModalEditCatTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditCatTarif.vue")
);

const props = defineProps({
    errors: Object,
    structure: Object,
    discipline: Object,
    categorie: Object,
    tarifTypes: Object,
    strCatTarifs: Object,
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
            only: ["strCatTarifs"],
            onSuccess: () => {
                emit("showDisplay", "Mes tarifs");
            },
        }
    );
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

const emitShowDisplay = () => {
    emit("showDisplay", "Mes tarifs");
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
                                    <span v-if="attribut.tarif_attribut">
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
                            <ul
                                class="grid list-inside list-disc grid-cols-2 gap-1"
                            >
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
    <div v-if="!strCatTarifs.length > 0">
        <p class="font-semibold italic text-gray-600">
            Pas de tarif associé à cette structure
        </p>
    </div>
    <ModalEditCatTarif
        :errors="errors"
        :structure="structure"
        :discipline="discipline"
        :categorie="categorie"
        :tarif-to-update="tarifToUpdate"
        :show="showEditCatTarifModal"
        @close="showEditCatTarifModal = false"
        @show-display="emitShowDisplay"
    />
</template>
