<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, computed, watch, onMounted, onBeforeMount } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { Head, Link } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";
import autoAnimate from "@formkit/auto-animate";
import {
    HomeIcon,
    TrashIcon,
    XMarkIcon,
    CalendarDaysIcon,
    MapPinIcon,
} from "@heroicons/vue/24/outline";
import dayjs from "dayjs";
import "dayjs/locale/fr";

const props = defineProps({
    reservations: Object,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
});

const formatDate = (dateTimeString) => {
    return dayjs(dateTimeString)
        .locale("fr")
        .format("dddd D MMMM YYYY [à] HH[h]mm");
};

const reservationPlanningQtyForm = useForm({
    quantity: {},
});

const decrementQuantity = (reservationId, creneauId) => {
    if (reservationPlanningQtyForm.quantity[reservationId][creneauId] > 1) {
        reservationPlanningQtyForm.quantity[reservationId][creneauId]--;
    }
};

const incrementQuantity = (reservationId, creneauId) => {
    reservationPlanningQtyForm.quantity[reservationId][creneauId]++;
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

const getCreneauAmount = (reservation, creneau) => {
    const tarifAmount = reservation.tarif_amount;
    const quantity =
        reservationPlanningQtyForm.quantity[reservation.id][creneau.id];
    return tarifAmount * quantity;
};

const totalReservationAmount = (reservation) => {
    let totalResaAmount = 0;

    reservation.plannings.forEach((creneau) => {
        totalResaAmount += getCreneauAmount(reservation, creneau);
    });

    return totalResaAmount;
};

const totalAmount = () => {
    let total = 0;
    props.reservations.forEach((reservation) => {
        total += totalReservationAmount(reservation);
    });
    return total;
};

// const updateReservationPlanningQty = (reservation, creneau) => {
//     reservationPlanningQtyForm.update(
//         route("reservations.plannings.update", {
//             reservation: reservation,
//             planning: creneau,
//         }),
//         { preserveScroll: true }
//     );
// };

const deleteReservationPlanning = (reservation, creneau) => {
    router.delete(
        route("reservations.plannings.destroy", {
            reservation: reservation,
            planning: creneau,
        }),
        { preserveScroll: true }
    );
};
const deleteReservation = (reservation) => {
    router.delete(
        route("panier.destroy", {
            reservation: reservation,
        }),
        { preserveScroll: true }
    );
};

const panierForm = useForm({
    codePromo: null,
    totalAmount: ref(totalAmount.value),
});

const listToAnimate = ref();

onMounted(() => {
    autoAnimate(listToAnimate.value);
});

onBeforeMount(() => {
    props.reservations.forEach((reservation) => {
        if (!reservationPlanningQtyForm.quantity[reservation.id]) {
            reservationPlanningQtyForm.quantity[reservation.id] = {};
        }

        reservation.plannings.forEach((creneau) => {
            const quantity = ref(creneau.pivot.quantity || 1);
            reservationPlanningQtyForm.quantity[reservation.id][creneau.id] =
                quantity.value;
        });
    });
});
</script>
<template>
    <Head title="Panier" description="Mon panier" />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Mon Panier</template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex text-gray-600 border border-gray-200 rounded-lg"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <HomeIcon class="w-4 h-4" />

                                    <span
                                        class="ms-1.5 hidden text-xs font-medium md:block"
                                    >
                                        Accueil
                                    </span>
                                </Link>
                            </li>

                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="route('favoris.index')"
                                    class="flex items-center h-10 text-xs font-medium transition bg-white pe-4 ps-8 hover:text-gray-900"
                                >
                                    Favoris
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>

        <Breadcrumb />

        <div class="container flex gap-4 py-6 mx-auto">
            <div ref="listToAnimate" class="flex flex-col flex-1 gap-4">
                <div
                    class="space-y-4 text-sm"
                    v-for="reservation in reservations"
                    :key="reservation.id"
                >
                    <div
                        class="space-y-2 border border-gray-200 rounded shadow bg-gray-50"
                    >
                        <div class="flex items-start justify-between w-full">
                            <div class="space-y-3 px-4 py-1.5">
                                <h3 class="text-lg">
                                    Demande:
                                    <span class="font-semibold">{{
                                        reservation.cat_tarif.cat_tarif_type.nom
                                    }}</span>
                                    pour
                                    <span class="font-semibold">{{
                                        reservation.activite_title
                                    }}</span>
                                </h3>
                                <div
                                    v-if="reservation.produit.adresse"
                                    class="flex items-center text-base"
                                >
                                    <dt class="sr-only">Ville</dt>
                                    <MapPinIcon
                                        class="mr-1 text-gray-600 size-4"
                                    />
                                    <p class="font-semibold">
                                        {{ reservation.produit.adresse.city }}
                                        ({{
                                            reservation.produit.adresse
                                                .zip_code
                                        }})
                                    </p>
                                </div>
                                <p class="text-sm font-normal">
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
                                    <span v-else class="font-semibold">
                                        créneau
                                    </span>
                                    dans votre liste:
                                </p>
                            </div>

                            <button
                                class="p-2 bg-red-400 hover:bg-red-500"
                                type="button"
                                @click="deleteReservation(reservation)"
                            >
                                <span class="sr-only"
                                    >Supprimer la réservation du panier</span
                                >
                                <XMarkIcon class="w-6 h-6 text-white" />
                            </button>
                        </div>
                        <div
                            class="flex items-center justify-between px-2 space-x-2"
                            v-for="creneau in reservation.plannings"
                            :key="creneau.id"
                        >
                            <div class="flex items-center flex-1">
                                <CalendarDaysIcon
                                    class="flex-shrink-0 text-gray-700 me-3 size-4"
                                />
                                <p class="text-sm text-gray-700">
                                    <span class="text-sm font-semibold">{{
                                        formatDate(creneau.start)
                                    }}</span>
                                    au
                                    <span class="text-sm font-semibold">{{
                                        formatDate(creneau.end)
                                    }}</span>
                                </p>
                            </div>

                            <div class="flex items-center self-end space-x-1">
                                <div
                                    class="inline-block px-3 py-2 bg-white border border-gray-200 rounded-lg dark:border-gray-700"
                                    data-hs-input-number
                                >
                                    <div class="flex items-center gap-x-1.5">
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-md shadow-sm size-6 gap-x-2 hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
                                            @click="
                                                decrementQuantity(
                                                    reservation.id,
                                                    creneau.id
                                                )
                                            "
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
                                            class="w-6 p-0 text-center text-gray-800 bg-transparent border-0 focus:ring-0"
                                            type="text"
                                            v-model="
                                                reservationPlanningQtyForm
                                                    .quantity[reservation.id][
                                                    creneau.id
                                                ]
                                            "
                                            data-hs-input-number-input
                                        />
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-md shadow-sm size-6 gap-x-2 hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
                                            @click="
                                                incrementQuantity(
                                                    reservation.id,
                                                    creneau.id
                                                )
                                            "
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
                                        deleteReservationPlanning(
                                            reservation,
                                            creneau
                                        )
                                    "
                                >
                                    <span class="sr-only"
                                        >Supprimer le créneau</span
                                    >
                                    <TrashIcon
                                        class="w-5 h-5 text-gray-400 hover:text-red-500"
                                    />
                                </button>
                            </div>
                        </div>
                        <div
                            class="px-4 py-1.5"
                            v-if="
                                reservation.attributs &&
                                reservation.attributs.length > 0
                            "
                        >
                            <p class="text-xs">
                                Les attributs liés à votre réservation:
                            </p>
                            <ul class="text-xs list-disc list-inside">
                                <li
                                    v-for="attribut in reservation.attributs"
                                    :key="attribut.id"
                                >
                                    <span v-if="attribut.booking_field"
                                        >{{ attribut.booking_field.nom }}:</span
                                    ><span class="font-semibold">{{
                                        attribut.valeur
                                    }}</span>

                                    <ul
                                        v-if="
                                            attribut.reservation_sous_attributs &&
                                            attribut.reservation_sous_attributs
                                                .length > 0
                                        "
                                        class="text-xs list-disc list-inside indent-4"
                                    >
                                        <li
                                            v-for="sousattribut in attribut.reservation_sous_attributs"
                                            :key="sousattribut.id"
                                        >
                                            <span
                                                v-if="
                                                    sousattribut.booking_sous_field
                                                "
                                                >{{
                                                    sousattribut
                                                        .booking_sous_field.nom
                                                }}:</span
                                            ><span class="font-semibold">{{
                                                sousattribut.valeur
                                            }}</span>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div
                            class="flex justify-between w-full px-4 py-2 text-lg font-bold text-green-700"
                        >
                            <div>Montant:</div>
                            <div>
                                {{ totalReservationAmount(reservation) }} €
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="w-full h-full p-6 mt-6 text-xl font-bold text-gray-700 border border-gray-200 rounded-lg shadow-md bg-gray-50 md:mt-0 md:w-1/3"
            >
                <div class="w-full my-4">
                    <label
                        for="codePromo"
                        class="block mb-2 text-sm font-medium text-gray-700"
                    >
                        Code Promo
                    </label>
                    <TextInput
                        type="text"
                        v-model="panierForm.codePromo"
                        name="codePromo"
                        id="codePromo"
                        class="block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                        placeholder="Code Promo"
                        autocomplete="none"
                    />
                </div>
                <div class="flex justify-between">
                    <p class="text-lg font-bold">Montant TTC</p>
                    <div class="">
                        <p class="mb-1 text-lg font-bold text-green-600">
                            {{ totalAmount() }} €
                        </p>
                    </div>
                </div>

                <div
                    class="w-full px-4 py-3 my-4 text-lg font-semibold text-blue-800 bg-blue-200 rounded"
                >
                    Vous ne serez débité que lorsque la structure aura validé
                    votre réservation.
                </div>
                <button
                    type="button"
                    class="mt-4 w-full rounded-md bg-blue-500 py-2.5 font-medium text-blue-50 hover:bg-blue-600"
                >
                    Payer et réserver
                </button>
            </div>
        </div>
    </ResultLayout>
</template>
