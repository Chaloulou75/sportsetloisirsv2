<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";
dayjs.locale("fr");
const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);
const ActiviteCard = defineAsyncComponent(() =>
    import("@/Components/Structures/ActiviteCard.vue")
);

const props = defineProps({
    errors: Object,
    structure: Object,
    structureNotifs: Object,
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

const page = usePage();
const user = computed(() => page.props.auth.user);
const structureNotifCount = computed(() => {
    if (props.structure && page.props.structures_notifications) {
        return page.props.structures_notifications[props.structure.id] || 0;
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
        description="Gestion de votre structure, réservations, disciplines et activités."
    />
    <ProLayout
        :structure="structure"
        :can="can"
        :all-reservations-count="allReservationsCount"
        :pending-reservations-count="pendingReservationsCount"
        :confirmed-reservations-count="confirmedReservationsCount"
        :total-amount-confirmed="totalAmountConfirmed"
    >
        <template #header>
            <h1
                class="px-2 py-2.5 text-center text-lg font-semibold text-indigo-700 md:px-6 md:py-4 md:text-left md:text-2xl md:font-bold"
            >
                Accueil
            </h1>
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
                            de réservation <span>réglées</span> en attente de
                            validation:
                        </p>
                        <div class="text-2xl font-bold text-indigo-500">
                            {{ totalAmountPending }} €
                        </div>
                    </div>
                    <ul class="list-inside list-disc">
                        <li
                            v-for="reservation in pendingReservations.data"
                            :key="reservation.id"
                            class="mb-4 px-2 py-2"
                            :class="{
                                'border border-blue-300 bg-white':
                                    isUnreadNotification(reservation),
                            }"
                        >
                            <span>
                                <span class="font-semibold"
                                    >N° {{ reservation.id }}:
                                    {{ reservation.activite_title }} -
                                    {{
                                        reservation.cat_tarif.cat_tarif_type.nom
                                    }}</span
                                >, produit n°{{ reservation.produit_id }}
                                <!-- <span class="text-xs italic"
                                    >({{
                                        formattedCriteria(
                                            reservation.produit_criteres
                                        )
                                    }}) -
                                </span> -->
                                Réservation faite par
                                <span class="font-semibold"
                                    >{{ reservation.user.name }}
                                </span>
                                <span class="text-xs italic">
                                    ({{ reservation.user.email }})</span
                                >
                                -
                                <span
                                    v-if="reservation.plannings_count < 1"
                                    class="font-bold text-green-600"
                                >
                                    {{
                                        formatCurrency(reservation.tarif_amount)
                                    }}</span
                                >
                                <span
                                    v-if="reservation.plannings_count < 1"
                                    class="ml-5 font-semibold text-indigo-500"
                                >
                                    Quantité:
                                    {{ reservation.quantity }}
                                </span>
                            </span>
                            <template v-if="reservation.plannings_count > 0">
                                <div
                                    class="ml-4"
                                    v-for="planning in reservation.plannings"
                                    :key="planning.id"
                                >
                                    <ul class="list-inside list-disc">
                                        <li>
                                            du
                                            <span class="font-semibold">{{
                                                formatDate(planning.start)
                                            }}</span>
                                            au
                                            <span class="font-semibold">{{
                                                formatDate(planning.end)
                                            }}</span>
                                            <span
                                                class="ml-5 font-semibold text-indigo-500"
                                            >
                                                {{
                                                    formatCurrency(
                                                        reservation.tarif_amount
                                                    )
                                                }}
                                            </span>
                                            <span
                                                class="ml-5 font-semibold text-indigo-500"
                                            >
                                                Quantité:
                                                {{ planning.pivot.quantity }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </template>
                        </li>
                    </ul>
                    <div class="flex w-full items-center justify-end">
                        <Pagination
                            :links="pendingReservations.links"
                            :only="['pendingReservations']"
                        />
                    </div>

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
                            v-for="reservation in confirmedReservations.data"
                            :key="reservation.id"
                        >
                            <span>
                                <span class="font-semibold"
                                    >N° {{ reservation.id }}:
                                    {{ reservation.activite_title }} -
                                    {{
                                        reservation.cat_tarif.cat_tarif_type.nom
                                    }}</span
                                >, produit n°{{ reservation.produit_id }}
                                <span class="text-xs italic"
                                    >({{
                                        formattedCriteria(
                                            reservation.produit_criteres
                                        )
                                    }})</span
                                >
                                - Réservation faite par
                                <span class="font-semibold"
                                    >{{ reservation.user.name }}
                                </span>
                                <span class="text-xs italic">
                                    ({{ reservation.user.email }})</span
                                >
                                -
                                <span
                                    v-if="reservation.plannings_count < 1"
                                    class="font-bold text-green-600"
                                >
                                    {{
                                        formatCurrency(reservation.tarif_amount)
                                    }}</span
                                >
                                <span
                                    v-if="reservation.plannings_count < 1"
                                    class="ml-5 font-semibold text-indigo-500"
                                >
                                    Quantité:
                                    {{ reservation.quantity }}
                                </span>
                            </span>
                            <template v-if="reservation.plannings_count > 0">
                                <div
                                    class="ml-4"
                                    v-for="planning in reservation.plannings"
                                    :key="planning.id"
                                >
                                    <ul class="list-inside list-disc">
                                        <li>
                                            du
                                            <span class="font-semibold">{{
                                                formatDate(planning.start)
                                            }}</span>
                                            au
                                            <span class="font-semibold">{{
                                                formatDate(planning.end)
                                            }}</span>
                                            <span
                                                class="ml-5 font-semibold text-indigo-500"
                                            >
                                                {{
                                                    formatCurrency(
                                                        reservation.tarif_amount
                                                    )
                                                }}
                                            </span>
                                            <span
                                                class="ml-5 font-semibold text-indigo-500"
                                            >
                                                Quantité:
                                                {{ planning.pivot.quantity }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </template>
                        </li>
                    </ul>
                    <div class="flex w-full items-center justify-end">
                        <Pagination
                            :links="confirmedReservations.links"
                            :only="['confirmedReservations']"
                        />
                    </div>
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
                                {{ structureNotifCount }}
                            </div>
                            <div class="font-semibold">messages</div>
                        </div>
                    </div>
                    <div class="flex w-full items-center justify-end">
                        <Link
                            :href="
                                route(
                                    'structures.gestion.statistiques.index',
                                    structure
                                )
                            "
                            class="rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-indigo-500 shadow hover:bg-gray-100 hover:text-indigo-800"
                        >
                            Voir mes statistiques
                        </Link>
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
                                    activite: activite,
                                    slug: activite.slug_title,
                                })
                            "
                        />
                    </div>
                    <div class="flex w-full items-center justify-end">
                        <Link
                            :href="
                                route('structures.disciplines.index', structure)
                            "
                            class="rounded-md border border-gray-200 bg-white px-4 py-2 text-lg text-indigo-500 shadow hover:bg-gray-100 hover:text-indigo-800"
                        >
                            Voir mes activités
                        </Link>
                    </div>
                </div>
            </div>
        </template>
    </ProLayout>
</template>
