<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    DocumentDuplicateIcon,
    XCircleIcon,
    TrashIcon,
    UsersIcon,
    UserGroupIcon,
    ClockIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    errors: Object,
    structure: Object,
    tarifTypes: Object,
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
</script>

<template>
    <Head
        title="Les tarifs"
        :description="
            'Trouvez un club de sport ou un cours collectif parmi plus de ' +
             +
            ' disciplines différentes en France. Choisissez parmi ' +
             +
            ' clubs sur notre site prêts à vous accueillir.'
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
                        La liste des tarifs pour votre structure
                        <span class="text-blue-700"> {{ structure.name }}</span>
                    </h2>
                </div>
                <div class="mt-4 w-full md:mt-0 md:w-1/4">
                    <div
                        class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6"
                    >
                        <Link
                            :href="
                                route('structures.activites.index', structure)
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
            <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                <div class="min-h-full w-full rounded-xl shadow-lg">
                    <div class="bg-slate-50 flex items-center justify-between w-full px-2 md:px-4 py-2">
                        <h3
                            class="w-full px-2 py-2 text-sm font-semibold text-gray-700 md:px-4"
                        >
                            Liste des tarifs de votre structure:
                        </h3>
                    </div>
                    <table class="table-auto text-sm font-semibold text-gray-700 w-full">
                        <thead class="bg-slate-50">
                            <tr class="border-b font-medium text-slate-400 text-center">
                                <th>Infos</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="tarif in structure.tarifs" :key="tarif.id">
                                <td
                                    v-for="info in tarif.structure_tarif_type_infos"
                                    :key="info.id"
                                    class="text-center border-b border-slate-100 p-4 pl-8 text-slate-500 flex items-center justify-center"
                                >
                                    <div v-if="info">
                                        <ClockIcon
                                            v-if="
                                                [1, 2, 5, 7].includes(
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
                                        class="text-xs font-thin text-slate-500"
                                        >{{ info.tarif_type_attribut.attribut }}: {{ info.valeur }}
                                        <span v-if="info.unite">{{
                                            info.unite
                                        }}</span>
                                    </span>
                                    <span v-else class="text-xs font-thin text-slate-500">Pas de valeur</span>
                                    </div>
                                </td>
                                <td class="text-center border-b border-slate-100 p-4 pl-8 text-slate-500">{{ tarif.titre }}</td>
                                <td class="text-center border-b border-slate-100 p-4 pl-8 text-slate-500">{{ tarif.tarif_type.type }}</td>
                                <td class="text-center border-b border-slate-100 p-4 pl-8 text-slate-500">{{ tarif.description }}</td>
                                <td class="text-center border-b border-slate-100 p-4 pl-8 text-slate-500">{{ formatCurrency(tarif.amount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
