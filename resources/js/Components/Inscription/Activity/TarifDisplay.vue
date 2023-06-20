<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { router } from "@inertiajs/vue3";
import {
    TrashIcon,
    UsersIcon,
    UserGroupIcon,
    ClockIcon,
    ArrowPathIcon,
} from "@heroicons/vue/24/outline";

const ModalEditTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditTarif.vue")
);

const props = defineProps({
    errors: Object,
    structure: Object,
    tarifTypes: Object,
    activiteForTarifs: Object,
    structureActivites: Object,
});

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
    // Remove the non-numeric characters from the currency value
    const numericValue = Number(value.replace(/[^0-9.-]+/g, ""));
    // Check if the numeric value is a valid number
    if (!isNaN(numericValue)) {
        // Check if the numeric value has decimal places
        if (numericValue % 1 === 0) {
            // No decimal places, return as integer
            return numericValue.toLocaleString() + " €";
        } else {
            // Decimal places present, format with two decimal places
            return numericValue.toFixed(2) + " €";
        }
    }
    // Return the original value if conversion failed
    return value;
};

const currentTarif = ref(null);
const showEditTarifModal = ref(false);

const openEditTarifModal = (tarif) => {
    currentTarif.value = tarif;
    showEditTarifModal.value = true;
};

const destroyTarif = (tarif) => {
    const url = `/structures/${props.structure.slug}/tarifs/${tarif.id}`;
    router.delete(url, {
        preserveScroll: true,
        structure: props.structure.slug,
        tarif: tarif.id,
    });
};
</script>
<template>
    <div class="min-h-full w-full rounded-xl shadow-lg mt-6 overflow-x-auto">
        <table class="table-fixed md:table-auto border-collapse text-sm font-semibold text-gray-700 w-full">
            <caption class="bg-slate-50 caption-top text-sm font-semibold text-slate-600 py-6">
                Liste des tarifs de votre activité:
            </caption>
            <thead class="bg-slate-50">
                <tr class="border-b font-medium text-slate-400 text-center">
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
                <tr class="text-slate-500 text-center border-b border-slate-100" v-for="tarif in tarifsList" :key="tarif.id">
                    <td class="p-5 flex flex-col items-center justify-center">
                        <div v-for="info in tarif.structure_tarif_type_infos"
                        :key="info.id" class="inline-flex items-center justify-center space-y-2">
                            <ClockIcon
                                v-if="
                                    [1, 2, 5, 7].includes(
                                        info.attribut_id
                                    )
                                "
                                class="mr-1 h-5 w-5"
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
                                v-else-if="
                                    [4].includes(
                                        info.attribut_id
                                    )
                                "
                                class="mr-1 h-5 w-5"
                            />

                            <UsersIcon v-else
                                class="mr-1 h-5 w-5"
                            />

                            <span v-if="info.valeur"
                                    class="text-sm font-thin">
                                {{ info.tarif_type_attribut.attribut }}: {{ info.valeur }}
                                    <span v-if="info.unite">{{
                                        info.unite
                                    }}</span>
                            </span>
                        <span v-else class="text-sm font-thin">Pas de valeur</span>
                        </div>
                    </td>
                    <td class="p-5">{{ tarif.titre }}</td>
                    <td class="p-5">N° {{ tarif.pivot.produit_id }}</td>
                    <td class="p-5">{{ tarif.tarif_type.type }}</td>
                    <td class="p-5"><p class="font-medium truncate">{{ tarif.description }}</p> </td>
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
                            @click="
                                () => destroyTarif(tarif)
                            "
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
