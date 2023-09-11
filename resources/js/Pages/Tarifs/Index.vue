<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineAsyncComponent } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ButtonsActiviteEdit from "@/Components/Inscription/Activity/ButtonsActiviteEdit.vue";
import {
    DocumentDuplicateIcon,
    XCircleIcon,
    TrashIcon,
    UsersIcon,
    UserGroupIcon,
    ClockIcon,
    ArrowPathIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    structure: Object,
    tarifTypes: Object,
    activiteForTarifs: Object,
});

const ModalEditTarif = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalEditTarif.vue")
);

const currentTarif = ref(null);
const showEditTarifModal = ref(false);

const openEditTarifModal = (tarif) => {
    currentTarif.value = tarif;
    showEditTarifModal.value = true;
};

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
    <Head
        title="Gestion des tarifs"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
            +' disciplines différentes en France. Choisissez parmi ' +
            +' clubs sur notre site prêts à vous accueillir.'
        "
    />
    <AppLayout>
        <template #header>
            <div
                class="flex flex-col items-start justify-between md:flex-row md:items-center"
            >
                <div>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Gestion des tarifs pour votre structure
                        <span class="text-blue-700"> {{ structure.name }}</span>
                    </h2>
                </div>
                <div class="mt-4 w-full md:mt-0 md:w-1/4">
                    <div
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="
                                route('structures.disciplines.index', structure)
                            "
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Mes activités</Link
                        >
                        <Link
                            :href="route('structures.show', structure.slug)"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg"
                        >
                            Voir la structure</Link
                        >
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-full px-2 py-6 sm:px-6 lg:px-8">
                <ButtonsActiviteEdit :structure="structure" />
                <div
                    class="mt-6 min-h-full w-full overflow-x-auto rounded-xl shadow-lg"
                >
                    <table
                        class="w-full table-fixed border-collapse text-sm font-semibold text-gray-700"
                    >
                        <caption
                            class="caption-top bg-slate-50 py-6 text-sm font-semibold text-slate-600"
                        >
                            Liste des tarifs de votre structure:
                        </caption>
                        <thead class="bg-slate-50">
                            <tr
                                class="border-b text-center font-medium text-slate-400"
                            >
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
                                <td
                                    class="flex flex-col items-center justify-center p-5"
                                >
                                    <div
                                        v-for="info in tarif.structure_tarif_type_infos"
                                        :key="info.id"
                                        class="flex items-center justify-center"
                                    >
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
                                                [4].includes(info.attribut_id)
                                            "
                                            class="mr-1 h-5 w-5"
                                        />

                                        <UsersIcon
                                            v-else
                                            class="mr-1 h-5 w-5"
                                        />

                                        <span
                                            v-if="info.valeur"
                                            class="text-sm font-thin"
                                        >
                                            {{
                                                info.tarif_type_attribut
                                                    .attribut
                                            }}: {{ info.valeur }}
                                            <span v-if="info.unite">{{
                                                info.unite
                                            }}</span>
                                        </span>
                                        <span v-else class="text-sm font-thin"
                                            >Pas de valeur</span
                                        >
                                    </div>
                                </td>
                                <td class="p-5">{{ tarif.titre }}</td>
                                <td class="p-5">{{ tarif.tarif_type.type }}</td>
                                <td class="p-5">
                                    <p class="truncate font-medium">
                                        {{ tarif.description }}
                                    </p>
                                </td>
                                <td class="p-5">
                                    {{ formatCurrency(tarif.amount) }}
                                </td>
                                <td class="p-5">
                                    <button
                                        type="button"
                                        @click="openEditTarifModal(tarif)"
                                    >
                                        <ArrowPathIcon
                                            class="mr-1 h-6 w-6 text-slate-400 transition-all duration-200 hover:-rotate-90 hover:text-slate-600"
                                        />
                                    </button>
                                    <!-- <button
                                        type="button"
                                        @click="
                                            () => duplicateTarif(tarif)
                                        "
                                    >
                                        <DocumentDuplicateIcon
                                            class="mr-1 h-6 w-6 text-slate-400 hover:text-slate-600"
                                        />
                                    </button> -->

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
            </div>
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
    </AppLayout>
</template>
