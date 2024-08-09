<script setup>
import { computed } from "vue";
import {
    MapPinIcon,
    XMarkIcon,
    CalendarDaysIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import dayjs from "dayjs";
import "dayjs/locale/fr";

const props = defineProps({
    reservation: Object,
    quantity: [Object, Number],
    getCreneauAmount: Function,
    totalReservationAmount: Function,
});

const emit = defineEmits([
    "update:quantity",
    "deleteReservation",
    "deleteReservationPlanning",
]);

const formatDate = (dateTimeString) => {
    return dayjs(dateTimeString)
        .locale("fr")
        .format("dddd D MMMM YYYY [à] HH[h]mm");
};

const getQuantity = computed(() => {
    return (creneauId) => {
        if (typeof props.quantity === "number") {
            return props.quantity;
        }
        return creneauId ? props.quantity[creneauId] : props.quantity;
    };
});

const decrementQuantity = (creneauId) => {
    const currentQuantity = getQuantity.value(creneauId);
    if (currentQuantity > 1) {
        emit("update:quantity", {
            reservationId: props.reservation.id,
            creneauId,
            quantity: currentQuantity - 1,
        });
    }
};

const incrementQuantity = (creneauId) => {
    const currentQuantity = getQuantity.value(creneauId);
    emit("update:quantity", {
        reservationId: props.reservation.id,
        creneauId,
        quantity: currentQuantity + 1,
    });
};
</script>
<template>
    <div class="space-y-2 rounded border border-blue-400 bg-gray-50 shadow">
        <div class="flex w-full items-start justify-between">
            <div class="space-y-3 px-4 py-1.5">
                <h3 class="text-lg">
                    <span v-if="reservation.cat_tarif" class="font-semibold">{{
                        reservation.cat_tarif.cat_tarif_type.nom
                    }}</span>
                    pour
                    <span class="font-semibold">{{
                        reservation.activite_title
                    }}</span>
                </h3>
                <div
                    v-if="reservation.produit && reservation.produit.adresse"
                    class="flex items-center text-base"
                >
                    <dt class="sr-only">Ville</dt>
                    <MapPinIcon class="mr-1 size-4 text-gray-600" />
                    <p class="font-semibold">
                        {{ reservation.produit.adresse.city_name }}
                        ({{ reservation.produit.adresse.zip_code }})
                    </p>
                </div>
                <template
                    v-if="
                        reservation.produit &&
                        reservation.produit.criteres &&
                        reservation.produit.criteres.length > 0
                    "
                >
                    <h3
                        class="border-b pb-2 text-lg font-semibold text-gray-800"
                    >
                        Les critères liés à ce produit:
                    </h3>
                    <div
                        class="mt-auto grid w-full grid-cols-3 justify-items-center gap-1 text-xs text-gray-900 md:grid-cols-6"
                    >
                        <template
                            v-for="critere in reservation.produit.criteres"
                            :key="critere.id"
                        >
                            <div
                                v-if="
                                    critere.valeur &&
                                    !!critere.critere.visible_block === true
                                "
                                class="flex w-full flex-col items-center justify-center px-1 py-1 font-medium"
                                :class="
                                    [
                                        'date',
                                        'dates',
                                        'time',
                                        'mois',
                                        'times',
                                    ].includes(critere.critere.type_champ_form)
                                        ? 'bg-slate-100'
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
                                    class="text-center text-xs"
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
                                            class="text-xs"
                                        >
                                            ({{ sousCriteres.valeur }})
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
                <p
                    v-if="reservation.plannings_count > 0"
                    class="text-sm font-normal"
                >
                    Vous avez
                    <span class="font-semibold"
                        >{{ reservation.plannings_count }}
                    </span>
                    <span
                        v-if="reservation.plannings_count > 1"
                        class="font-semibold"
                    >
                        créneaux
                    </span>
                    <span v-else class="font-semibold"> créneau </span>
                    dans votre liste:
                </p>
            </div>

            <button
                class="bg-red-400 p-2 hover:bg-red-500"
                type="button"
                @click="emit('deleteReservation', reservation)"
            >
                <span class="sr-only">Supprimer la réservation du panier</span>
                <XMarkIcon class="h-6 w-6 text-white" />
            </button>
        </div>

        <template v-if="reservation.cat_tarif">
            <div class="space-y-4 rounded-lg bg-white px-3 py-2 shadow-md">
                <h3 class="border-b pb-2 text-lg font-semibold text-gray-800">
                    Les attributs liés au tarif:
                </h3>
                <div
                    class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6"
                >
                    <template
                        v-for="attribut in reservation.cat_tarif.attributs"
                        :key="attribut.id"
                    >
                        <div
                            v-if="attribut.tarif_attribut"
                            class="flex h-full flex-col items-start justify-start rounded-md bg-gray-50 p-3 transition-all duration-300 hover:bg-gray-100 hover:shadow-sm"
                        >
                            <div
                                class="mb-1 w-full text-center text-sm font-medium uppercase text-gray-600"
                            >
                                {{ attribut.tarif_attribut.nom }}
                            </div>
                            <div v-if="attribut.valeur" class="w-full">
                                <span
                                    class="block text-center text-base font-semibold text-gray-800"
                                    >{{ attribut.valeur }}</span
                                >
                                <div
                                    v-if="
                                        attribut.sous_attributs &&
                                        attribut.sous_attributs.length > 0
                                    "
                                    class="mt-2 w-full space-y-2"
                                >
                                    <div
                                        v-for="sousattribut in attribut.sous_attributs"
                                        :key="sousattribut.id"
                                        class="border-t pt-1 text-center text-xs text-gray-600"
                                    >
                                        <span
                                            v-if="sousattribut.sous_attribut"
                                            class="text-2xs block font-medium uppercase tracking-wide"
                                        >
                                            {{ sousattribut.sous_attribut.nom }}
                                        </span>
                                        <span
                                            class="mt-0.5 block font-semibold text-gray-700"
                                        >
                                            {{ sousattribut.valeur }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
        <template
            v-if="reservation.attributs && reservation.attributs.length > 0"
            ><h3 class="px-4 text-sm text-gray-700">
                Les attributs liés à cette réservation:
            </h3>
            <div
                class="mt-auto grid w-full grid-cols-3 justify-items-center gap-1 px-4 text-xs text-gray-900 md:grid-cols-6"
            >
                <template
                    v-for="attribut in reservation.attributs"
                    :key="attribut.id"
                >
                    <div
                        v-if="attribut.booking_field"
                        class="flex w-full flex-col items-center justify-center bg-gray-100 px-1 py-1 font-medium"
                    >
                        <div
                            class="text-center text-xs uppercase text-slate-500"
                        >
                            {{ attribut.booking_field.nom }}
                        </div>
                        <div v-if="attribut.valeur" class="text-center text-xs">
                            {{ attribut.valeur }}
                            <div
                                v-if="
                                    attribut.reservation_sous_attributs &&
                                    attribut.reservation_sous_attributs.length >
                                        0
                                "
                                class="text-xs"
                            >
                                <p
                                    v-for="sousattribut in attribut.reservation_sous_attributs"
                                    :key="sousattribut.id"
                                    class="text-xs text-gray-700"
                                >
                                    <span
                                        class="text-slate-500"
                                        v-if="sousattribut.booking_sous_field"
                                        >{{
                                            sousattribut.booking_sous_field.nom
                                        }}:
                                    </span>
                                    {{ sousattribut.valeur }}
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>
        <template
            v-if="reservation.plannings && reservation.plannings.length > 0"
        >
            <div
                class="flex items-center justify-between space-x-2 px-1 md:px-4"
                v-for="creneau in reservation.plannings"
                :key="creneau.id"
            >
                <div class="flex flex-1 items-center">
                    <CalendarDaysIcon
                        class="me-3 size-4 flex-shrink-0 text-gray-700"
                    />
                    <p class="text-xs text-gray-700 md:text-sm">
                        <span class="font-semibold">{{
                            formatDate(creneau.start)
                        }}</span>
                        au
                        <span class="font-semibold">{{
                            formatDate(creneau.end)
                        }}</span>
                    </p>
                </div>

                <div class="flex items-center space-x-1 self-end">
                    <div
                        class="inline-block rounded-lg border border-gray-200 bg-white px-3 py-2 dark:border-gray-700"
                        data-hs-input-number
                    >
                        <div class="flex items-center gap-x-1.5">
                            <button
                                type="button"
                                class="inline-flex size-6 items-center justify-center gap-x-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
                                @click="decrementQuantity(creneau.id)"
                            >
                                <svg
                                    class="size-3.5 flex-shrink-0"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M5 12h14" />
                                </svg>
                            </button>
                            <input
                                class="w-6 border-0 bg-transparent p-0 text-center text-gray-800 focus:ring-0"
                                type="text"
                                :value="getQuantity(creneau.id)"
                                data-hs-input-number-input
                            />
                            <button
                                type="button"
                                class="inline-flex size-6 items-center justify-center gap-x-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
                                @click="incrementQuantity(creneau.id)"
                            >
                                <svg
                                    class="size-3.5 flex-shrink-0"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M5 12h14" />
                                    <path d="M12 5v14" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!-- End Input Number -->
                </div>
                <div class="flex items-center space-x-2">
                    <p class="text-lg font-bold text-green-700">
                        {{ getCreneauAmount(reservation, creneau) }}
                        €
                    </p>
                    <button
                        class="ml-4"
                        type="button"
                        @click="
                            emit(
                                deleteReservationPlanning,
                                reservation,
                                creneau
                            )
                        "
                    >
                        <span class="sr-only">Supprimer le créneau</span>
                        <TrashIcon
                            class="h-5 w-5 text-gray-400 hover:text-red-500"
                        />
                    </button>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="flex w-full items-center justify-end space-x-2 px-2">
                <div
                    class="inline-block rounded-lg border border-gray-200 bg-white px-3 py-2 dark:border-gray-700"
                    data-hs-input-number
                >
                    <div class="flex items-center gap-x-1.5">
                        <button
                            type="button"
                            class="inline-flex size-6 items-center justify-center gap-x-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
                            @click="decrementQuantity()"
                        >
                            <svg
                                class="size-3.5 flex-shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M5 12h14" />
                            </svg>
                        </button>
                        <input
                            class="w-6 border-0 bg-transparent p-0 text-center text-gray-800 focus:ring-0"
                            type="text"
                            :value="getQuantity()"
                            data-hs-input-number-input
                        />
                        <button
                            type="button"
                            class="inline-flex size-6 items-center justify-center gap-x-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
                            @click="incrementQuantity()"
                        >
                            <svg
                                class="size-3.5 flex-shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="text-lg font-bold text-green-700">
                    {{ getCreneauAmount(reservation) }} €
                </p>
            </div>
        </template>

        <div
            class="flex w-full justify-between border-t border-gray-200 bg-white px-4 py-2 text-lg font-bold"
        >
            <div class="text-gray-700">Montant:</div>
            <div class="text-green-700">
                {{ totalReservationAmount(reservation) }}
                €
            </div>
        </div>
    </div>
</template>
