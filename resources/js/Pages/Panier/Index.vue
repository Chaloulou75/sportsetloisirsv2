<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Head, Link } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import { HomeIcon, TrashIcon, XMarkIcon } from "@heroicons/vue/24/outline";
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
        .format("dddd D MMMM YYYY, HH[h]mm");
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

const totalReservationAmount = computed(() => {
    return props.reservations.reduce((acc, reservation) => {
        acc[reservation.id] =
            reservation.tarif_amount * reservation.plannings_count;
        return acc;
    }, {});
});

const totalAmount = computed(() => {
    let sum = 0;
    for (const reservationId in totalReservationAmount.value) {
        sum += totalReservationAmount.value[reservationId];
    }
    return sum;
});
</script>
<template>
    <Head title="Panier" :description="'Mon panier.'" />

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

        <div class="container flex gap-4 py-6 mx-auto">
            <div class="flex flex-col flex-1 gap-4">
                <div
                    class="space-y-4 text-sm"
                    v-for="reservation in reservations"
                    :key="reservation.id"
                >
                    <div
                        class="px-6 py-4 space-y-2 border border-gray-100 rounded shadow bg-gray-50"
                    >
                        <div class="flex items-start justify-between w-full">
                            <div class="space-y-3">
                                <p class="text-base font-medium">
                                    Vous avez
                                    <span class="font-semibold"
                                        >{{ reservation.plannings_count }}
                                    </span>
                                    créneaux sauvegardés pour l'activité
                                    <span class="font-semibold">{{
                                        reservation.activite_title
                                    }}</span
                                    >, de type
                                    <span class="font-semibold">{{
                                        reservation.cat_tarif.cat_tarif_type.nom
                                    }}</span
                                    >:
                                </p>
                                <div
                                    v-if="
                                        reservation.attributs &&
                                        reservation.attributs.length > 0
                                    "
                                >
                                    <p>
                                        Les attributs liés à votre réservation:
                                    </p>
                                    <ul class="text-xs list-disc list-inside">
                                        <li
                                            v-for="attribut in reservation.attributs"
                                            :key="attribut.id"
                                        >
                                            {{ attribut.valeur }}
                                            <ul
                                                v-if="
                                                    attribut.reservation_sous_attributs &&
                                                    attribut
                                                        .reservation_sous_attributs
                                                        .length > 0
                                                "
                                                class="text-xs list-disc list-inside indent-4"
                                            >
                                                <li
                                                    v-for="sousattribut in attribut.reservation_sous_attributs"
                                                    :key="sousattribut.id"
                                                >
                                                    {{ sousattribut.valeur }}
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <button
                                class="ml-4"
                                type="button"
                                @click="deleteReservation(reservation)"
                            >
                                <XMarkIcon
                                    class="w-6 h-6 text-gray-500 hover:text-red-500"
                                />
                            </button>
                        </div>

                        <div
                            class="flex items-center justify-between"
                            v-for="creneau in reservation.plannings"
                            :key="creneau.id"
                        >
                            <div>
                                Date:
                                <span class="text-sm text-gray-700"
                                    >{{ formatDate(creneau.start) }}
                                    au
                                    {{ formatDate(creneau.end) }}</span
                                >, au tarif de:
                                <span
                                    class="text-lg font-bold text-green-700"
                                    >{{
                                        formatCurrency(reservation.tarif_amount)
                                    }}</span
                                >
                            </div>
                            <div>
                                <button
                                    class="ml-4"
                                    type="button"
                                    @click="deleteReservationPlanning(creneau)"
                                >
                                    <TrashIcon
                                        class="w-5 h-5 text-gray-400 hover:text-red-500"
                                    />
                                </button>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-green-700">
                            Sous total:
                            {{ totalReservationAmount[reservation.id] }} €
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="w-full h-full p-6 mt-6 text-xl font-bold text-right text-gray-700 border border-gray-100 rounded-lg shadow-md bg-gray-50 md:mt-0 md:w-1/3"
            >
                <div class="flex justify-between">
                    <p class="text-lg font-bold">Total</p>
                    <div class="">
                        <p class="mb-1 text-lg font-bold">
                            {{ totalAmount }} €
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    class="mt-6 w-full rounded-md bg-blue-500 py-1.5 font-medium text-blue-50 hover:bg-blue-600"
                >
                    Payer et réserver
                </button>
            </div>
        </div>
    </ResultLayout>
</template>
