<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, computed, watch, onMounted, onBeforeMount } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { Head, Link } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import ReservationItem from "@/Components/Panier/ReservationItem.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import autoAnimate from "@formkit/auto-animate";
import "dayjs/locale/fr";

const page = usePage();

const user = computed(() => page.props.auth.user);

const props = defineProps({
    reservations: Object,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
});

const updateQuantity = ({ reservationId, creneauId, quantity }) => {
    if (creneauId) {
        if (typeof panierForm.quantity[reservationId] !== "object") {
            panierForm.quantity[reservationId] = {};
        }
        panierForm.quantity[reservationId][creneauId] = quantity;
    } else {
        panierForm.quantity[reservationId] = quantity;
    }
};

const getCreneauAmount = computed(() => {
    return (reservation, creneau) => {
        const tarifAmount = reservation.tarif_amount;
        let quantity;
        if (creneau) {
            quantity = panierForm.quantity[reservation.id][creneau.id];
        } else {
            quantity = panierForm.quantity[reservation.id];
        }
        return tarifAmount * quantity;
    };
});

const totalReservationAmount = computed(() => {
    return (reservation) => {
        let totalResaAmount = 0;
        if (reservation.plannings && reservation.plannings.length > 0) {
            reservation.plannings.forEach((creneau) => {
                totalResaAmount += getCreneauAmount.value(reservation, creneau);
            });
        } else {
            totalResaAmount += getCreneauAmount.value(reservation);
        }
        return totalResaAmount;
    };
});

const totalAmount = computed(() => {
    let total = 0;
    props.reservations.forEach((reservation) => {
        total += totalReservationAmount.value(reservation);
    });
    return total;
});

const panierForm = useForm({
    reservations: props.reservations,
    quantity: {},
    codePromo: null,
});

const onSubmit = () => {
    panierForm.post(route("reservations.store"), { preserveScroll: true });
};

const deleteReservationPlanning = (reservation, creneau) => {
    router.delete(
        route("reservations.plannings.destroy", {
            reservation: reservation,
            planning: creneau,
        }),
        {
            preserveScroll: true,
            onSuccess: () => router.reload({ only: ["reservations"] }),
        }
    );
};
const deleteReservation = (reservation) => {
    router.delete(
        route("panier.destroy", {
            reservation: reservation,
        }),
        {
            preserveScroll: true,
            onSuccess: () => router.reload({ only: ["reservations"] }),
        }
    );
};

const listToAnimate = ref();

watch(
    () => props.reservations,
    (newReservations, oldReservations) => {
        newReservations.forEach((reservation) => {
            if (!panierForm.quantity[reservation.id]) {
                panierForm.quantity[reservation.id] = {};
            }
            if (reservation.plannings && reservation.plannings.length > 0) {
                reservation.plannings.forEach((creneau) => {
                    const quantity = ref(creneau.pivot.quantity || 1);
                    panierForm.quantity[reservation.id][creneau.id] =
                        quantity.value;
                });
            } else {
                const quantity = ref(reservation.quantity || 1);
                panierForm.quantity[reservation.id] = quantity.value;
            }
        });
    },
    { deep: true }
);

onMounted(() => {
    if (listToAnimate.value) {
        autoAnimate(listToAnimate.value);
    }
});

onBeforeMount(() => {
    if (props.reservations && props.reservations.length > 0) {
        props.reservations.forEach((reservation) => {
            if (!panierForm.quantity[reservation.id]) {
                panierForm.quantity[reservation.id] = {};
            }
            if (reservation.plannings && reservation.plannings.length > 0) {
                reservation.plannings.forEach((creneau) => {
                    const quantity = ref(creneau.pivot.quantity || 1);
                    panierForm.quantity[reservation.id][creneau.id] =
                        quantity.value;
                });
            } else {
                const quantity = ref(reservation.quantity || 1);
                panierForm.quantity[reservation.id] = quantity.value;
            }
        });
    }
});
</script>
<template>
    <Head
        title="Récapitulatif de mon panier"
        description="Récapitulatif de mon mon panier"
    />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title>Récapitulatif de mon Panier</template>
            </ResultsHeader>
        </template>

        <Breadcrumb />
        <form @submit.prevent="onSubmit" autocomplete="off">
            <div class="container mx-auto flex flex-col gap-4 py-6 md:flex-row">
                <div
                    v-if="reservations && reservations.length > 0"
                    ref="listToAnimate"
                    class="flex flex-1 flex-col gap-4"
                >
                    <ReservationItem
                        v-for="reservation in reservations"
                        :key="reservation.id"
                        :reservation="reservation"
                        v-model:quantity="panierForm.quantity[reservation.id]"
                        :getCreneauAmount="getCreneauAmount"
                        :totalReservationAmount="totalReservationAmount"
                        @update:quantity="updateQuantity"
                        @delete-reservation="deleteReservation"
                        @delete-reservation-planning="deleteReservationPlanning"
                    />
                </div>
                <div v-else class="flex-1 px-2">
                    <p class="text-lg text-gray-700">Votre panier est vide.</p>
                </div>

                <div
                    v-if="reservations && reservations.length > 0"
                    class="mt-6 h-full w-full rounded-lg border border-blue-400 bg-gray-50 p-6 text-xl font-bold text-gray-700 shadow-md md:mt-0 md:w-1/3"
                >
                    <div class="my-4 w-full">
                        <label
                            for="codePromo"
                            class="mb-2 block text-sm font-medium normal-case text-gray-700"
                        >
                            Code Promo
                        </label>
                        <TextInput
                            type="text"
                            v-model="panierForm.codePromo"
                            name="codePromo"
                            id="codePromo"
                            class="block w-full rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                            placeholder="Code Promo"
                            autocomplete="none"
                        />
                    </div>
                    <div class="flex justify-between">
                        <p class="text-lg font-bold">Montant TTC</p>
                        <div>
                            <p class="mb-1 text-lg font-bold text-green-600">
                                {{ totalAmount }} €
                            </p>
                        </div>
                    </div>

                    <div
                        class="my-4 w-full rounded bg-blue-200 px-4 py-3 text-lg font-semibold text-blue-800"
                    >
                        Vous ne serez débité que lorsque la structure aura
                        validé votre réservation.
                    </div>
                    <button
                        v-if="user"
                        :disabled="panierForm.processing"
                        :class="{
                            'opacity-25': panierForm.processing,
                        }"
                        type="submit"
                        class="mt-4 inline-flex w-full items-center justify-center rounded-md bg-blue-500 px-4 py-2.5 font-medium text-blue-50 hover:bg-blue-600"
                    >
                        <LoadingSVG v-if="panierForm.processing" />
                        Continuer
                    </button>
                    <Link
                        v-else
                        :href="route('login')"
                        class="mt-4 inline-flex w-full items-center justify-center rounded-md bg-blue-500 px-4 py-2.5 font-medium text-blue-50 hover:bg-blue-600"
                    >
                        Connectez vous pour continuer
                    </Link>
                </div>
            </div>
        </form>
    </ResultLayout>
</template>
