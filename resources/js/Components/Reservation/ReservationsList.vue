<script setup>
import { computed } from "vue";
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
</script>
<template>
    <ul class="list-inside list-disc">
        <li
            v-for="reservation in reservations"
            :key="reservation.id"
            class="mb-4 bg-white px-2 py-2 shadow-sm"
            :class="{
                'border-4 border-blue-300': isUnreadNotification(reservation),
                'border border-blue-100': !isUnreadNotification(reservation),
            }"
        >
            <span class="text-sm font-normal text-gray-700">
                N° {{ reservation.id }}:
                <span class="font-semibold uppercase text-indigo-500"
                    >{{ reservation.activite_title }} -
                    {{ reservation.cat_tarif.cat_tarif_type.nom }}</span
                >
                <span class="text-xs italic">
                    (produit n°{{ reservation.produit_id }})
                </span>
            </span>
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
</template>
