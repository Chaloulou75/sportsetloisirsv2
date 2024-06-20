<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, usePage, Link, router } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import { ChevronLeftIcon } from "@heroicons/vue/24/outline";
import dayjs from "dayjs";
import "dayjs/locale/fr";
dayjs.locale("fr");

const ReservationsList = defineAsyncComponent(() =>
    import("@/Components/Reservation/ReservationsList.vue")
);
const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
const CodeForm = defineAsyncComponent(() =>
    import("@/Components/Inscription/CodeForm.vue")
);

const props = defineProps({
    errors: Object,
    structure: Object,
    confirmedReservations: Object,
    structureNotifs: Object,
    pendingReservations: Object,
    finishedReservations: Object,
    cancelledReservations: Object,
    confirmedReservationsCount: Number,
    allReservationsCount: Number,
    pendingReservationsCount: Number,
    finishedReservationsCount: Number,
    cancelledReservationsCount: Number,
    totalAmountConfirmed: Number,
    totalAmountPending: Number,
    can: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const structureNotifCount = computed(() => {
    if (props.structure && page.props.structures_notifications_count) {
        return (
            page.props.structures_notifications_count[props.structure.id] || 0
        );
    }
    return 0;
});

const isUnreadNotification = computed(() => {
    return (reservation) => {
        return props.structureNotifs.some(
            (notification) =>
                notification.data.reservation_id === reservation.id
        );
    };
});

const formattedCriteria = (criteria) => {
    const criteriaObject = JSON.parse(criteria);
    return formatObject(criteriaObject);
};

const formatObject = (obj) => {
    return Object.entries(obj)
        .map(([key, value]) => `${value}`)
        .join(", ");
};

const currentMonth = ref(dayjs().format("MMMM YYYY"));
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

const selectedReservation = ref(null);
const statusReservation = ref(null);

const confirmReservation = (reservation) => {
    selectedReservation.value = reservation;
    statusReservation.value = "confirmed";
    updateReservation(selectedReservation.value);
};

const refusReservation = (reservation) => {
    selectedReservation.value = reservation;
    statusReservation.value = "refused";
    updateReservation(selectedReservation.value);
};

// const finishReservation = (reservation) => {
//     selectedReservation.value = reservation;
//     statusReservation.value = "finished";
//     updateReservation(selectedReservation.value);
// };

const cancelReservation = (reservation) => {
    selectedReservation.value = reservation;
    statusReservation.value = "cancelled";
    updateReservation(selectedReservation.value);
};

const updateReservation = (reservation) => {
    router.put(
        route("structures.gestion.reservations.update", {
            structure: props.structure.slug,
            reservation: reservation,
        }),
        {
            reservation_id: reservation.id,
            status: statusReservation.value,
        },
        {
            preserveScroll: true,
        }
    );
};
</script>
<template>
    <Head
        title="Gestion de votre structure"
        description="Gestion de votre structure, réservations, disciplines et activités."
    />
    <ProLayout
        :structure="structure"
        :can="can"
        :all-reservations-count="allReservationsCount"
        :pending-reservations-count="pendingReservationsCount"
        :confirmed-reservations-count="confirmedReservationsCount"
    >
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                    :href="route('structures.gestion.index', structure)"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-2 py-2.5 text-center text-lg font-semibold text-indigo-700 md:px-6 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Réservations
                </h1>
            </div>
        </template>

        <template #default="{}">
            <div class="space-y-10 px-2 py-6 text-gray-700 md:px-4">
                <h3 class="text-2xl">
                    Bienvenue
                    <span class="text-indigo-700">{{ user.name }}</span>
                </h3>

                <!-- réservations en attente -->
                <div
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-4 py-6 shadow-md"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-xl font-semibold">
                            Vous avez
                            <span class="text-indigo-700">{{
                                pendingReservationsCount
                            }}</span>
                            <span v-if="pendingReservationsCount > 1">
                                demandes</span
                            >
                            <span v-else> demande</span>
                            de réservation en attente:
                        </p>
                        <div class="text-2xl font-bold text-indigo-500">
                            {{ totalAmountPending }} €
                        </div>
                    </div>
                    <div class="space-y-8">
                        <ReservationsList
                            :reservations="pendingReservations.data"
                            :structure="structure"
                            :notifications="structureNotifs"
                        >
                            <template #item="{ reservation }">
                                <div
                                    class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-4"
                                >
                                    <button
                                        type="button"
                                        @click="confirmReservation(reservation)"
                                        class="rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-indigo-500 shadow hover:bg-gray-100 hover:text-indigo-800"
                                    >
                                        Confirmer
                                    </button>
                                    <button
                                        type="button"
                                        @click="refusReservation(reservation)"
                                        class="rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-red-500 shadow hover:bg-red-50 hover:text-red-800"
                                    >
                                        Refuser
                                    </button>
                                </div>
                            </template>
                        </ReservationsList>
                        <div class="flex w-full items-center justify-end">
                            <Pagination
                                :links="pendingReservations.meta.links"
                                :only="['pendingReservations']"
                            />
                        </div>
                    </div>
                </div>

                <!-- réservations en cours -->
                <div
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-4 py-6 shadow-md"
                >
                    <div
                        class="flex flex-col items-center justify-between md:flex-row"
                    >
                        <p class="text-xl font-semibold">
                            <span class="text-indigo-700">{{
                                confirmedReservationsCount
                            }}</span>
                            <span v-if="confirmedReservationsCount > 1">
                                réservations</span
                            >
                            <span v-else> réservation</span>
                            en cours:
                        </p>
                        <div class="text-2xl font-bold text-indigo-500">
                            {{ formatCurrency(totalAmountConfirmed) }}
                        </div>
                    </div>
                    <div class="space-y-8">
                        <ReservationsList
                            :reservations="confirmedReservations.data"
                            :structure="structure"
                            :notifications="structureNotifs"
                        >
                            <template #item="{ reservation }">
                                <div
                                    class="grid grid-cols-1 gap-4 md:grid-cols-4"
                                >
                                    <CodeForm
                                        :structure="structure"
                                        :reservation="reservation"
                                        :errors="errors[index]"
                                    />
                                    <div class="w-full place-self-end">
                                        <button
                                            type="button"
                                            @click="
                                                cancelReservation(reservation)
                                            "
                                            class="w-full rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-red-500 shadow hover:bg-red-50 hover:text-red-800"
                                        >
                                            Annuler
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </ReservationsList>

                        <div class="flex w-full items-center justify-end">
                            <Pagination
                                :links="confirmedReservations.meta.links"
                                :only="['confirmedReservations']"
                            />
                        </div>
                    </div>
                </div>

                <!-- réservations terminées -->
                <div
                    v-if="finishedReservationsCount > 0"
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-4 py-6 shadow-md"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-xl font-semibold">
                            <span class="text-indigo-700">{{
                                finishedReservationsCount
                            }}</span>
                            <span v-if="finishedReservationsCount > 1">
                                réservations terminées</span
                            >
                            <span v-else> réservation terminée</span>
                            :
                        </p>
                        <!-- <div class="text-2xl font-bold text-indigo-500">
                            {{ totalAmountPending }} €
                        </div> -->
                    </div>
                    <div class="space-y-8">
                        <ReservationsList
                            :reservations="finishedReservations.data"
                            :structure="structure"
                            :notifications="structureNotifs"
                        />

                        <div class="flex w-full items-center justify-end">
                            <Pagination
                                :links="finishedReservations.meta.links"
                                :only="['finishedReservations']"
                            />
                        </div>
                    </div>
                </div>

                <!-- réservations annulées -->
                <div
                    v-if="cancelledReservationsCount > 0"
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-4 py-6 shadow-md"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-xl font-semibold">
                            <span class="text-indigo-700">{{
                                cancelledReservationsCount
                            }}</span>
                            <span v-if="cancelledReservationsCount > 1">
                                réservations annulées</span
                            >
                            <span v-else> réservation annulée</span>
                            :
                        </p>
                        <!-- <div class="text-2xl font-bold text-indigo-500">
                            {{ totalAmountPending }} €
                        </div> -->
                    </div>
                    <div class="space-y-8">
                        <ReservationsList
                            :reservations="cancelledReservations.data"
                            :structure="structure"
                            :notifications="structureNotifs"
                        />
                        <div class="flex w-full items-center justify-end">
                            <Pagination
                                :links="cancelledReservations.meta.links"
                                :only="['cancelledReservations']"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </ProLayout>
</template>
