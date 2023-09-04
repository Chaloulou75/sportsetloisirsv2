<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";
dayjs.locale("fr");

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    errors: Object,
    structure: Object,
    confirmedReservations: Object,
    allReservations: Object,
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

const formatDate = (dateString) => {
    const date = dayjs(dateString);
    return date.format("dddd D MMMM YYYY à H[h]mm");
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

const selectedReservation = ref(null);
const statusReservation = ref(null);

const confirmReservation = (reservation) => {
    selectedReservation.value = reservation;
    statusReservation.value = "confirmed";
    updateReservation(selectedReservation.value);
};

const codeForm = useForm({
    code: ref({}),
});

const onCodeSubmit = (reservation) => {
    router.put(
        route("structures.gestion.reservations.update", {
            structure: props.structure.slug,
            reservation: reservation,
        }),
        {
            reservation_id: reservation.id,
            status: "finished",
            code: codeForm.code[reservation.id],
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                codeForm.reset();
            },
        }
    );
};

const refusReservation = (reservation) => {
    selectedReservation.value = reservation;
    statusReservation.value = "refused";
    updateReservation(selectedReservation.value);
};

const finishReservation = (reservation) => {
    selectedReservation.value = reservation;
    statusReservation.value = "finished";
    updateReservation(selectedReservation.value);
};

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
        :description="'Gestion de votre structure, disciplines et activités.'"
    />
    <ProLayout
        :structure="structure"
        :can="can"
        :allReservationsCount="allReservationsCount"
        :pendingReservationsCount="pendingReservationsCount"
        :confirmedReservationsCount="confirmedReservationsCount"
    >
        <template #header>
            <h1
                class="text-xl font-semibold leading-tight tracking-widest text-gray-700"
            >
                Réservations
            </h1>
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
                    <div class="space-y-6">
                        <div
                            class="space-y-2"
                            v-for="reservation in pendingReservations"
                            :key="reservation.id"
                        >
                            <p>
                                Vous avez reçu une demande de réservation de la
                                part de
                                <span class="font-semibold text-indigo-500">{{
                                    reservation.user.name
                                }}</span>
                            </p>
                            <p>
                                <span class="font-semibold text-indigo-500">{{
                                    reservation.planning.title
                                }}</span
                                >: du
                                <span class="font-semibold">{{
                                    formatDate(reservation.planning.start)
                                }}</span>
                                au
                                <span class="font-semibold">{{
                                    formatDate(reservation.planning.end)
                                }}</span>
                                <span
                                    class="ml-5 font-semibold text-indigo-500"
                                >
                                    {{
                                        formatCurrency(reservation.tarif.amount)
                                    }}
                                </span>
                            </p>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
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
                        </div>
                    </div>
                </div>

                <!-- réservations en cours -->
                <div
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-4 py-6 shadow-md"
                >
                    <div class="flex items-center justify-between">
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
                            {{ totalAmountConfirmed }} €
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div
                            class="space-y-4"
                            v-for="(
                                reservation, index
                            ) in confirmedReservations"
                            :key="reservation.id"
                            :index="index"
                        >
                            <p>
                                &bullet; Vous avez reçu une demande de
                                réservation de la part de
                                <span class="font-semibold text-indigo-500">{{
                                    reservation.user.name
                                }}</span>
                            </p>
                            <p>
                                <span class="font-semibold text-indigo-500">{{
                                    reservation.planning.title
                                }}</span
                                >: du
                                <span class="font-semibold">{{
                                    formatDate(reservation.planning.start)
                                }}</span>
                                au
                                <span class="font-semibold">{{
                                    formatDate(reservation.planning.end)
                                }}</span>
                                <span
                                    class="ml-5 font-semibold text-indigo-500"
                                >
                                    {{
                                        formatCurrency(reservation.tarif.amount)
                                    }}
                                </span>
                            </p>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                <form
                                    @submit.prevent="
                                        () => onCodeSubmit(reservation)
                                    "
                                    autocomplete="off"
                                    class="space-y-4"
                                >
                                    <div>
                                        <label
                                            :for="
                                                reservation.code[reservation.id]
                                            "
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Code de la réservation
                                            <span class="italic"
                                                >(4 chiffres)</span
                                            >
                                            *
                                        </label>
                                        <div class="mt-1 flex rounded-md">
                                            <input
                                                v-model="
                                                    codeForm.code[
                                                        reservation.id
                                                    ]
                                                "
                                                type="text"
                                                :name="
                                                    reservation.code[
                                                        reservation.id
                                                    ]
                                                "
                                                :id="
                                                    reservation.code[
                                                        reservation.id
                                                    ]
                                                "
                                                :class="{
                                                    'border-red-400':
                                                        errors.code,
                                                }"
                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                placeholder="1234"
                                                autocomplete="none"
                                            />
                                        </div>
                                        <div
                                            v-if="errors.code"
                                            class="mt-2 text-sm text-red-500"
                                        >
                                            {{ errors.code }}
                                        </div>
                                    </div>
                                    <button
                                        type="submit"
                                        :disabled="codeForm.processing"
                                        class="w-full rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-indigo-500 shadow hover:bg-gray-100 hover:text-indigo-800"
                                    >
                                        Verifier le code
                                    </button>
                                </form>
                                <!-- <button
                                    type="button"
                                    @click="finishReservation(reservation)"
                                    class="w-full rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-indigo-500 shadow hover:bg-gray-100 hover:text-indigo-800"
                                >
                                    Terminer
                                </button> -->
                                <button
                                    type="button"
                                    @click="cancelReservation(reservation)"
                                    class="rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-red-500 shadow hover:bg-red-50 hover:text-red-800"
                                >
                                    Annuler
                                </button>
                            </div>
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
                    <div class="space-y-6">
                        <div
                            class="space-y-2"
                            v-for="reservation in finishedReservations"
                            :key="reservation.id"
                        >
                            <p>
                                Réservation de la part de
                                <span class="font-semibold text-indigo-500">{{
                                    reservation.user.name
                                }}</span>
                            </p>
                            <p>
                                <span class="font-semibold text-indigo-500">{{
                                    reservation.planning.title
                                }}</span
                                >: du
                                <span class="font-semibold">{{
                                    formatDate(reservation.planning.start)
                                }}</span>
                                au
                                <span class="font-semibold">{{
                                    formatDate(reservation.planning.end)
                                }}</span>
                                <span
                                    class="ml-5 font-semibold text-indigo-500"
                                >
                                    {{
                                        formatCurrency(reservation.tarif.amount)
                                    }}
                                </span>
                            </p>
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
                    <div class="space-y-6">
                        <div
                            class="space-y-2"
                            v-for="reservation in cancelledReservations"
                            :key="reservation.id"
                        >
                            <p>
                                Réservation de la part de
                                <span class="font-semibold text-indigo-500">{{
                                    reservation.user.name
                                }}</span>
                            </p>
                            <p>
                                <span class="font-semibold text-indigo-500">{{
                                    reservation.planning.title
                                }}</span
                                >: du
                                <span class="font-semibold">{{
                                    formatDate(reservation.planning.start)
                                }}</span>
                                au
                                <span class="font-semibold">{{
                                    formatDate(reservation.planning.end)
                                }}</span>
                                <span
                                    class="ml-5 font-semibold text-indigo-500"
                                >
                                    {{
                                        formatCurrency(reservation.tarif.amount)
                                    }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </ProLayout>
</template>
