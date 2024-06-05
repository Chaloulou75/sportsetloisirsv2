<script setup>
import { computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import "dayjs/locale/fr";
dayjs.locale("fr");

const props = defineProps({
    reservations: Object,
    structure: Object,
    notifications: Object,
});

const isUnreadNotification = computed(() => {
    if (!props.notifications) {
        return false;
    }
    return (reservation) => {
        return props.notifications.some(
            (notification) =>
                notification.data.reservation_id === reservation.id
        );
    };
});

const formatDate = (dateString) => {
    const date = dayjs(dateString);
    return date.format("dddd D MMMM YYYY à H[h]mm");
};

const formatCurrency = (value) => {
    let numericValue;

    if (typeof value === "string") {
        numericValue = Number(value.replace(/[^0-9.-]+/g, ""));
    } else if (typeof value === "number") {
        numericValue = value;
    } else {
        return value;
    }

    if (!isNaN(numericValue)) {
        if (numericValue % 1 === 0) {
            return numericValue.toLocaleString() + " €";
        } else {
            return numericValue.toFixed(2) + " €";
        }
    }
    return value;
};

// reprendre cette partie
const notificationForm = useForm({
    markedAsRead: {},
});

const markNotificationAsRead = (reservationId) => {
    const notification = props.notifications.find(
        (notification) => notification.data.reservation_id === reservationId
    );
    if (notification) {
        notificationForm.put(
            route("structures.gestion.notifications.markAsRead", {
                structure: props.structure,
                notification: notification.id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => {},
            }
        );
    }
};
</script>
<template>
    <ul class="list-none space-y-6">
        <li
            v-for="reservation in reservations"
            :key="reservation.id"
            class="relative rounded-lg border border-gray-200 bg-white p-6 shadow-lg transition duration-300 hover:shadow-xl"
            :class="{
                'border-blue-400': isUnreadNotification(reservation),
            }"
        >
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Réservation N° {{ reservation.id }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ reservation.activite_title }} -
                            {{ reservation.cat_tarif.cat_tarif_type.nom }}
                        </p>
                        <p class="text-xs italic text-gray-400">
                            Produit n°{{ reservation.produit_id }}, réglée le
                            {{ formatDate(reservation.paiement_datetime) }}
                        </p>
                    </div>
                    <label
                        v-if="isUnreadNotification(reservation)"
                        class="flex items-center space-x-2"
                    >
                        <span class="text-xs">Marquer comme lu</span>
                        <input
                            type="checkbox"
                            class="form-checkbox h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            :checked="
                                notificationForm.markedAsRead[reservation.id]
                            "
                            @change="markNotificationAsRead(reservation.id)"
                        />
                    </label>
                </div>

                <div class="mt-4">
                    <p class="text-sm text-gray-700">
                        Réservation faite par
                        <span class="font-semibold">{{
                            reservation.user.name
                        }}</span>
                        <span class="text-xs italic"
                            >({{ reservation.user.email }})</span
                        >
                    </p>

                    <template v-if="reservation.plannings_count < 1">
                        <ul class="ml-6 mt-2 list-disc space-y-1 text-sm">
                            <li>
                                <span class="font-semibold"
                                    >Prix unitaire:</span
                                >
                                {{ formatCurrency(reservation.tarif_amount) }}
                            </li>
                            <li>
                                <span class="font-semibold">Quantité:</span>
                                {{ reservation.quantity }}
                            </li>
                            <li class="text-green-600">
                                <span class="font-semibold">Total:</span>
                                {{
                                    formatCurrency(
                                        reservation.quantity *
                                            reservation.tarif_amount
                                    )
                                }}
                            </li>
                        </ul>
                    </template>

                    <template v-if="reservation.plannings_count > 0">
                        <ul class="ml-6 mt-2 list-disc space-y-2 text-sm">
                            <li
                                v-for="planning in reservation.plannings"
                                :key="planning.id"
                            >
                                <span
                                    >du
                                    <strong>{{
                                        formatDate(planning.start)
                                    }}</strong>
                                    au
                                    <strong>{{
                                        formatDate(planning.end)
                                    }}</strong></span
                                >
                                <ul
                                    class="ml-6 mt-1 list-disc space-y-1 text-xs"
                                >
                                    <li>
                                        <span class="font-semibold"
                                            >Prix unitaire:</span
                                        >
                                        {{
                                            formatCurrency(
                                                reservation.tarif_amount
                                            )
                                        }}
                                    </li>
                                    <li>
                                        <span class="font-semibold"
                                            >Quantité:</span
                                        >
                                        {{ planning.pivot.quantity }}
                                    </li>
                                    <li class="text-green-600">
                                        <span class="font-semibold"
                                            >Total:</span
                                        >
                                        {{
                                            formatCurrency(
                                                planning.pivot.quantity *
                                                    reservation.tarif_amount
                                            )
                                        }}
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </template>

                    <template
                        v-if="
                            reservation.attributs &&
                            reservation.attributs.length > 0
                        "
                    >
                        <div class="mt-4">
                            <p class="text-sm font-semibold text-gray-700">
                                Les attributs liés à cette réservation:
                            </p>
                            <ul
                                class="ml-6 mt-2 list-disc space-y-1 text-sm"
                                v-for="attribut in reservation.attributs"
                                :key="attribut.id"
                            >
                                <li>
                                    <span v-if="attribut.booking_field"
                                        >{{ attribut.booking_field.nom }}:</span
                                    >
                                    {{ attribut.valeur }}
                                    <template
                                        v-if="
                                            attribut.reservation_sous_attributs &&
                                            attribut.reservation_sous_attributs
                                                .length > 0
                                        "
                                    >
                                        <ul
                                            class="ml-4 list-disc space-y-1 text-xs text-gray-700"
                                            v-for="sousattribut in attribut.reservation_sous_attributs"
                                            :key="sousattribut.id"
                                        >
                                            <li
                                                v-if="
                                                    sousattribut.booking_sous_field
                                                "
                                            >
                                                {{
                                                    sousattribut
                                                        .booking_sous_field.nom
                                                }}: {{ sousattribut.valeur }}
                                            </li>
                                        </ul>
                                    </template>
                                </li>
                            </ul>
                        </div>
                    </template>
                </div>
            </div>
            <slot name="item" :reservation="reservation"></slot>
        </li>
    </ul>
</template>
<!-- <template>
    <ul class="list-inside list-disc">
        <li
            v-for="reservation in reservations"
            :key="reservation.id"
            class="mb-4 inline-block w-full bg-white px-2 py-2 shadow-sm"
            :class="{
                'border-4 border-blue-300': isUnreadNotification(reservation),
                'border border-blue-100': !isUnreadNotification(reservation),
            }"
        >
            <div class="inline-flex w-full items-center justify-between">
                <div class="text-sm font-normal text-gray-700">
                    N° {{ reservation.id }}:
                    <span class="font-semibold uppercase text-indigo-500"
                        >{{ reservation.activite_title }} -
                        {{ reservation.cat_tarif.cat_tarif_type.nom }}.</span
                    >
                    <span class="text-xs italic">
                        Produit n°{{ reservation.produit_id }}, réglée le
                        {{ formatDate(reservation.paiement_datetime) }}
                    </span>
                </div>
                <label
                    v-if="isUnreadNotification(reservation)"
                    class="inline-flex items-center"
                >
                    <span class="mr-2 text-xs">Marquer comme lu</span>
                    <input
                        type="checkbox"
                        class="form-checkbox h-4 w-4 shrink-0 rounded border-gray-200 text-indigo-600 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50"
                        v-model="notificationForm.markedAsRead"
                        @change="markNotificationAsRead(reservation.id)"
                    />
                </label>
            </div>

            <p>
                Réservation faite par
                <span class="font-semibold">{{ reservation.user.name }} </span>
                <span class="text-xs italic">
                    ({{ reservation.user.email }})</span
                >
            </p>
            <template v-if="reservation.plannings_count < 1">
                <ul class="ml-4 list-inside list-disc font-semibold">
                    <li>
                        <span class="font-normal">Prix unitaire: </span>

                        {{ formatCurrency(reservation.tarif_amount) }}
                    </li>
                    <li>
                        <span class="font-normal">Quantité: </span>

                        {{ reservation.quantity }}
                    </li>
                    <li class="text-green-600">
                        <span class="font-normal">Total: </span>
                        {{
                            formatCurrency(
                                reservation.quantity * reservation.tarif_amount
                            )
                        }}
                    </li>
                </ul>
            </template>
            <template v-if="reservation.plannings_count > 0">
                <ul
                    class="ml-4 list-inside list-disc text-sm"
                    v-for="planning in reservation.plannings"
                    :key="planning.id"
                >
                    <li>
                        du
                        <span class="font-semibold">{{
                            formatDate(planning.start)
                        }}</span>
                        au
                        <span class="font-semibold">{{
                            formatDate(planning.end)
                        }}</span>
                        <ul class="ml-4 list-inside list-disc font-semibold">
                            <li>
                                <span class="font-normal">Prix unitaire: </span>

                                {{ formatCurrency(reservation.tarif_amount) }}
                            </li>
                            <li>
                                <span class="font-normal">Quantité: </span>

                                {{ planning.pivot.quantity }}
                            </li>
                            <li class="text-green-600">
                                <span class="font-normal">Total: </span>
                                {{
                                    formatCurrency(
                                        planning.pivot.quantity *
                                            reservation.tarif_amount
                                    )
                                }}
                            </li>
                        </ul>
                    </li>
                </ul>
            </template>
            <template
                v-if="reservation.attributs && reservation.attributs.length > 0"
            >
                <div class="ml-4 mt-2">
                    <p class="text-sm font-semibold text-gray-700">
                        Les attributs liés à cette réservation:
                    </p>

                    <ul
                        class="list-inside list-disc space-x-2 px-4 text-sm"
                        v-for="attribut in reservation.attributs"
                        :key="attribut.id"
                    >
                        <li>
                            <span v-if="attribut.booking_field">
                                {{ attribut.booking_field.nom }}:
                            </span>
                            {{ attribut.valeur }}

                            <template
                                v-if="
                                    attribut.reservation_sous_attributs &&
                                    attribut.reservation_sous_attributs.length >
                                        0
                                "
                            >
                                <ul
                                    v-for="sousattribut in attribut.reservation_sous_attributs"
                                    :key="sousattribut.id"
                                    class="ml-4 list-inside list-disc text-xs text-gray-700"
                                >
                                    <li
                                        class="text-slate-500"
                                        v-if="sousattribut.booking_sous_field"
                                    >
                                        {{
                                            sousattribut.booking_sous_field.nom
                                        }}:
                                        {{ sousattribut.valeur }}
                                    </li>
                                </ul>
                            </template>
                        </li>
                    </ul>
                </div>
            </template>
            <slot name="item" :reservation="reservation"></slot>
        </li>
    </ul>
</template> -->
