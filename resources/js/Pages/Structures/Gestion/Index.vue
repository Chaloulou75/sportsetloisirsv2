<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";
dayjs.locale("fr");

const ActiviteCard = defineAsyncComponent(() =>
    import("@/Components/Structures/ActiviteCard.vue")
);

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    errors: Object,
    structure: Object,
    confirmedReservations: Object,
    allReservations: Object,
    pendingReservations: Object,
    confirmedReservationsCount: Number,
    allReservationsCount: Number,
    pendingReservationsCount: Number,
    totalAmountConfirmed: Number,
    totalAmountPending: Number,
    can: Object,
});

const currentMonth = ref(dayjs().format("MMMM YYYY"));
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
            <h1 class="text-2xl font-bold text-indigo-700">Accueil</h1>
        </template>

        <template #default>
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
                    <ul class="list-inside list-disc">
                        <li
                            v-for="reservation in pendingReservations"
                            :key="reservation.id"
                        >
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
                            <span class="ml-5 font-semibold text-indigo-500">
                                {{ formatCurrency(reservation.tarif.amount) }}
                            </span>
                        </li>
                    </ul>
                    <div class="flex w-full items-center justify-end">
                        <Link
                            preserve-scroll
                            :href="
                                route(
                                    'structures.gestion.reservations.index',
                                    structure
                                )
                            "
                            class="rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-indigo-500 shadow hover:bg-gray-100 hover:text-indigo-800"
                        >
                            Voir mes réservations
                        </Link>
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
                    <ul class="list-inside list-disc">
                        <li
                            v-for="reservation in confirmedReservations"
                            :key="reservation.id"
                        >
                            <span class="font-semibold text-indigo-500">{{
                                reservation.planning.title
                            }}</span
                            >:<span class="font-normal"> du </span
                            ><span class="font-semibold">{{
                                formatDate(reservation.planning.start)
                            }}</span
                            ><span class="font-normal"> au </span
                            ><span class="font-semibold">{{
                                formatDate(reservation.planning.end)
                            }}</span>
                            <span class="ml-5 font-semibold text-indigo-500">{{
                                formatCurrency(reservation.tarif.amount)
                            }}</span>
                        </li>
                    </ul>
                    <div class="flex w-full items-center justify-end">
                        <Link
                            preserve-scroll
                            :href="
                                route(
                                    'structures.gestion.reservations.index',
                                    structure
                                )
                            "
                            class="rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-indigo-500 shadow hover:bg-gray-100 hover:text-indigo-800"
                        >
                            Voir mes réservations
                        </Link>
                    </div>
                </div>

                <!-- Statistiques -->
                <div
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-4 py-6 shadow-md"
                >
                    <h2 class="text-2xl font-semibold">
                        Vos statistiques de {{ currentMonth }}:
                    </h2>
                    <div
                        class="grid w-full grid-cols-1 justify-items-center gap-4 md:grid-cols-4"
                    >
                        <div
                            class="flex flex-col items-center justify-center space-y-4"
                        >
                            <div class="text-5xl font-bold text-indigo-500">
                                {{ allReservationsCount }}
                            </div>
                            <div class="font-semibold">réservations</div>
                        </div>
                        <div
                            class="flex flex-col items-center justify-center space-y-4"
                        >
                            <div class="text-5xl font-bold text-indigo-500">
                                {{ totalAmountConfirmed }} €
                            </div>
                            <div class="font-semibold">chiffre d'affaire</div>
                        </div>
                        <div
                            class="flex flex-col items-center justify-center space-y-4"
                        >
                            <div class="text-5xl font-bold text-indigo-500">
                                {{ structure.view_count }}
                            </div>
                            <div class="font-semibold">pages vues</div>
                        </div>
                        <div
                            class="flex flex-col items-center justify-center space-y-4"
                        >
                            <div class="text-5xl font-bold text-indigo-500">
                                4
                            </div>
                            <div class="font-semibold">messages</div>
                        </div>
                    </div>
                </div>

                <!-- activités populaires -->
                <div
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-4 py-6 shadow-md"
                >
                    <h2 class="text-2xl font-semibold">
                        Vos activités les plus populaires:
                    </h2>
                    <div
                        class="grid w-full grid-cols-1 justify-items-center gap-4 md:grid-cols-3"
                    >
                        <ActiviteCard
                            v-for="(activite, index) in structure.activites"
                            :key="activite.id"
                            :index="index"
                            :activite="activite"
                            :link="
                                route('structures.activites.show', {
                                    activite: activite.id,
                                })
                            "
                        />
                    </div>
                </div>
            </div>
        </template>
    </ProLayout>
</template>
