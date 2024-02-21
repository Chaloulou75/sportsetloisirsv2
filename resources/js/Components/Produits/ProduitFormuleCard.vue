<script setup>
import { ref, computed } from "vue";
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
            class="flex flex-col h-full transition duration-300 ease-in-out rounded-lg shadow-sm ring ring-gray-300 ring-offset-1 hover:shadow-lg md:px-0"
            :class="{
                'ring-3 shadow-indigo-400 ring-indigo-500 ring-offset-2':
                    isSelected,
            }"
        >
            <div class="flex flex-col flex-1 mt-2">
                <div
                    class="flex flex-col justify-between flex-1 justify-items-end text-slate-700"
                >
                    <div class="flex items-center justify-between px-4 py-2">
                        <input
                            class="p-2 bg-white rounded-full"
                            type="radio"
                            :id="produit.id"
                            :value="produit.id"
                            v-model="reservationProduit"
                        />
                        <div
                            v-if="produit.adresse"
                            class="flex items-center px-4 py-2 text-base"
                        >
                            <dt class="sr-only">Ville</dt>
                            <MapPinIcon class="w-4 h-4 mr-1 text-indigo-700" />
                            <p class="font-semibold">
                                {{ produit.adresse.city }} ({{
                                    produit.adresse.zip_code
                                }})
                            </p>
                        </div>
                    </div>
                    <template v-if="produit.criteres.length > 0">
                        <div
                            class="grid w-full grid-cols-3 gap-1 mt-auto text-xs text-gray-900 justify-items-center md:grid-cols-6"
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
                                    class="flex flex-col items-center justify-center w-full px-1 py-3 font-medium"
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
                                        class="text-xs text-center uppercase text-slate-500"
                                    >
                                        {{ critere.critere.nom }}
                                    </div>
                                    <div
                                        v-if="critere.valeur"
                                        class="text-sm text-center"
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

            <TransitionRoot
                :show="isSelected"
                enter="transition-opacity ease-linear duration-500"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="transition-opacity ease-linear duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <template v-if="isSelected && produit.cat_tarifs.length > 0">
                    <div>
                        <h3
                            class="px-3 py-4 text-base font-semibold text-blue-700"
                        >
                            Choisissez votre formule:
                        </h3>
                        <div class="flex flex-col">
                            <div class="-m-1.5 overflow-x-auto">
                                <div
                                    class="inline-block min-w-full p-1.5 align-middle"
                                >
                                    <div class="overflow-hidden">
                                        <table
                                            class="min-w-full divide-y divide-gray-200"
                                        >
                                            <thead>
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3 text-xs font-medium text-gray-500 uppercase text-start"
                                                    ></th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3 text-xs font-medium text-gray-500 uppercase text-start"
                                                    >
                                                        Type de tarif
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3 text-xs font-medium text-gray-500 uppercase text-start"
                                                    >
                                                        Montant
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3 text-xs font-medium text-gray-500 uppercase text-start"
                                                    >
                                                        Titre
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3 text-xs font-medium text-gray-500 uppercase text-start"
                                                    >
                                                        Attributs
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="divide-y divide-gray-200"
                                            >
                                                <tr
                                                    v-for="catTarif in produit.cat_tarifs"
                                                    :key="catTarif.id"
                                                    class="hover:bg-gray-50"
                                                    :class="{
                                                        'bg-sky-200':
                                                            catTarif.id ===
                                                            reservationFormule,
                                                    }"
                                                >
                                                    <td
                                                        class="px-3 py-4 text-sm font-medium text-gray-800 whitespace-nowrap"
                                                    >
                                                        <input
                                                            type="radio"
                                                            v-model="
                                                                reservationFormule
                                                            "
                                                            :id="catTarif.id"
                                                            :value="catTarif.id"
                                                            class="justify-self-start"
                                                        />
                                                    </td>
                                                    <td
                                                        class="px-3 py-4 text-sm font-semibold text-gray-800 whitespace-nowrap md:text-base"
                                                    >
                                                        {{
                                                            catTarif
                                                                .cat_tarif_type
                                                                .nom
                                                        }}
                                                    </td>
                                                    <td
                                                        class="px-3 py-4 text-sm font-semibold text-gray-800 whitespace-nowrap md:text-base"
                                                    >
                                                        {{
                                                            formatCurrency(
                                                                catTarif.amount
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="px-3 py-4 text-sm text-gray-800 whitespace-nowrap"
                                                    >
                                                        {{ catTarif.titre }}
                                                    </td>
                                                    <td
                                                        class="px-3 py-4 text-sm text-gray-800 whitespace-nowrap"
                                                    >
                                                        <template
                                                            v-if="
                                                                catTarif.attributs
                                                            "
                                                        >
                                                            <ul
                                                                class="list-disc list-inside justify-self-end"
                                                            >
                                                                <li
                                                                    v-for="attribut in catTarif.attributs"
                                                                    :key="
                                                                        attribut.id
                                                                    "
                                                                    class="mb-2"
                                                                >
                                                                    <span
                                                                        v-if="
                                                                            attribut.tarif_attribut
                                                                        "
                                                                    >
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
                                                                            class="list-disc list-inside"
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
                    </div>
                </template>
            </TransitionRoot>
        </div>
    </TransitionRoot>
</template>
