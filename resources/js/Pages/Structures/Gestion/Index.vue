<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";
dayjs.locale("fr");

const ReservationsList = defineAsyncComponent(() =>
    import("@/Components/Reservation/ReservationsList.vue")
);
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
    allReservationsCount: Number,
    confirmedReservations: Object,
    confirmedReservationsCount: Number,
    pendingReservations: Object,
    pendingReservationsCount: Number,
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
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-2 py-6 shadow-md"
                >
                    <div
                        class="flex flex-col items-center justify-between md:flex-row"
                    >
                        <p class="text-justify text-xl font-semibold">
                            Vous avez
                            <span class="text-indigo-700">{{
                                pendingReservationsCount
                            }}</span>
                            <span v-if="pendingReservationsCount > 1">
                                demandes</span
                            >
                            <span v-else> demande</span>
                            de réservation <span>réglées</span> en attente de
                            validation
                            <span class="text-sm italic text-gray-600"
                                >( {{ structureNotifCount }} non lues)</span
                            >:
                        </p>
                        <div
                            class="mt-4 text-2xl font-bold text-indigo-500 md:mt-0"
                        >
                            {{ formatCurrency(totalAmountPending) }}
                        </div>
                    </div>
                    <ReservationsList
                        :reservations="pendingReservations.data"
                        :structure="structure"
                        :notifications="structureNotifs"
                    />
                    <div class="flex w-full items-center justify-end">
                        <Pagination
                            :links="pendingReservations.links"
                            :only="['pendingReservations']"
                        />
                    </div>

                    <div
                        class="flex w-full items-center justify-center md:justify-end"
                    >
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
                            Gérer mes réservations
                        </Link>
                    </div>
                </div>

                <!-- réservations en cours -->
                <div
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-2 py-6 shadow-sm"
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
                        <div
                            class="mt-4 text-2xl font-bold text-indigo-500 md:mt-0"
                        >
                            {{ formatCurrency(totalAmountConfirmed) }}
                        </div>
                    </div>
                    <ReservationsList
                        :reservations="confirmedReservations.data"
                        :structure="structure"
                        :notifications="structureNotifs"
                    />
                    <div class="flex w-full items-center justify-end">
                        <Pagination
                            :links="confirmedReservations.links"
                            :only="['confirmedReservations']"
                        />
                    </div>
                    <div
                        class="flex w-full items-center justify-center md:justify-end"
                    >
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
                            Gérer mes réservations
                        </Link>
                    </div>
                </div>

                <!-- Statistiques -->
                <div
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-2 py-6 shadow-md"
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
                    class="space-y-10 rounded-md border border-gray-200 bg-gray-50 px-2 py-6 shadow-md"
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
