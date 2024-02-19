<script setup>
import { ref, computed } from "vue";
import { classMapping } from "@/Utils/classMapping.js";
import { MapPinIcon } from "@heroicons/vue/24/outline";
import { TransitionRoot } from "@headlessui/vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import localeData from "dayjs/plugin/localeData";
dayjs.locale("fr");
dayjs.extend(localeData);

const reservationProduit = defineModel("reservationProduit");
const reservationFormule = defineModel("reservationFormule");

const props = defineProps({
    produit: Object,
    discipline: Object,
});

const isSelected = computed(
    () => props.produit.id === reservationProduit.value
);

const isShowing = ref(true);

const headerClass = computed(() => {
    const defaultClass = "bg-la-base";
    if (props.discipline && props.discipline.id) {
        const disciplineId = props.discipline.id;
        if (classMapping[disciplineId]) {
            return classMapping[disciplineId];
        } else {
            return defaultClass;
        }
    } else {
        return defaultClass;
    }
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
        <div
            class="flex h-full flex-col rounded-lg shadow-sm shadow-indigo-200 transition duration-300 ease-in-out hover:shadow-2xl md:px-0"
            :class="{ 'ring-2 ring-blue-500 ring-offset-2': isSelected }"
        >
            <div class="relative w-full">
                <input
                    class="absolute right-3 top-3 z-30 rounded-full bg-white p-2"
                    type="radio"
                    :id="produit.id"
                    :value="produit.id"
                    v-model="reservationProduit"
                />
            </div>

            <div class="mt-2 flex flex-1 flex-col">
                <div
                    class="flex flex-1 flex-col justify-between justify-items-end text-slate-700"
                >
                    <div
                        v-if="produit.adresse"
                        class="flex items-center px-4 py-2 text-base"
                    >
                        <dt class="sr-only">Ville</dt>
                        <MapPinIcon class="mr-1 h-4 w-4 text-indigo-700" />
                        <p class="font-semibold">
                            {{ produit.adresse.city }} ({{
                                produit.adresse.zip_code
                            }})
                        </p>
                    </div>
                    <template v-if="produit.criteres.length > 0">
                        <div
                            class="mt-auto grid w-full grid-cols-3 justify-items-center gap-1 text-xs text-gray-900"
                        >
                            <template
                                v-for="critere in produit.criteres"
                                :key="critere.id"
                            >
                                <div
                                    v-if="
                                        critere.valeur &&
                                        !!critere.critere.visible_block === true
                                    "
                                    class="flex w-full flex-col items-center justify-center px-1 py-3 font-medium"
                                    :class="
                                        [
                                            'date',
                                            'dates',
                                            'time',
                                            'mois',
                                            'times',
                                        ].includes(
                                            critere.critere.type_champ_form
                                        )
                                            ? 'bg-slate-50'
                                            : 'bg-slate-200'
                                    "
                                >
                                    <div
                                        class="text-center text-xs uppercase text-slate-500"
                                    >
                                        {{ critere.critere.nom }}
                                    </div>
                                    <div
                                        v-if="critere.valeur"
                                        class="text-center text-sm"
                                    >
                                        {{ critere.valeur }}
                                        <span
                                            v-if="
                                                critere.sous_criteres &&
                                                critere.sous_criteres.length > 0
                                            "
                                            class="text-xs"
                                        >
                                            <span
                                                v-for="sousCriteres in critere.sous_criteres"
                                                :key="sousCriteres.id"
                                                class="text-sm"
                                            >
                                                ({{ sousCriteres.valeur }})
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
            <template v-if="isSelected && produit.cat_tarifs.length > 0">
                <h3 class="px-3 py-4 text-base font-semibold text-slate-700">
                    Choisissez votre tarif:
                </h3>
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="inline-block min-w-full p-1.5 align-middle">
                            <div class="overflow-hidden">
                                <table
                                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                                >
                                    <thead>
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-3 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                            ></th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                            >
                                                Type de tarif
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                            >
                                                Montant
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                            >
                                                Titre
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                            >
                                                Attributs
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-gray-200 dark:divide-gray-700"
                                    >
                                        <tr
                                            v-for="catTarif in produit.cat_tarifs"
                                            :key="catTarif.id"
                                            class="hover:bg-gray-100 dark:hover:bg-gray-700"
                                        >
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-800 dark:text-gray-200"
                                            >
                                                <input
                                                    type="radio"
                                                    v-model="reservationFormule"
                                                    :id="catTarif.id"
                                                    :value="catTarif.id"
                                                    class="justify-self-start"
                                                />
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-800"
                                            >
                                                {{
                                                    catTarif.cat_tarif_type.nom
                                                }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-800"
                                            >
                                                {{
                                                    formatCurrency(
                                                        catTarif.amount
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-800 dark:text-gray-200"
                                            >
                                                {{ catTarif.titre }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-800 dark:text-gray-200"
                                            >
                                                <template
                                                    v-if="catTarif.attributs"
                                                >
                                                    <ul
                                                        class="list-inside list-disc justify-self-end"
                                                    >
                                                        <li
                                                            v-for="attribut in catTarif.attributs"
                                                            :key="attribut.id"
                                                            class="mb-2"
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
                                                                    class="list-inside list-disc"
                                                                >
                                                                    <li
                                                                        v-for="sousattr in attribut.sous_attributs"
                                                                        :key="
                                                                            sousattr.id
                                                                        "
                                                                        class="pl-4"
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
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </TransitionRoot>
</template>
