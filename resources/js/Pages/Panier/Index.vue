<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, computed, watch, onMounted, onBeforeMount } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { Head, Link } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
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

const decrementQuantity = (reservationId, creneauId) => {
    if (creneauId) {
        if (panierForm.quantity[reservationId][creneauId] > 1) {
            panierForm.quantity[reservationId][creneauId]--;
        }
    } else {
        if (panierForm.quantity[reservationId] > 1) {
            panierForm.quantity[reservationId]--;
        }
    }
};

const incrementQuantity = (reservationId, creneauId) => {
    if (creneauId) {
        panierForm.quantity[reservationId][creneauId]++;
    } else {
        panierForm.quantity[reservationId]++;
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
                            class="flex rounded-lg border border-gray-200 text-gray-600"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <HomeIcon class="h-4 w-4" />

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
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
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
        <form @submit.prevent="onSubmit" autocomplete="off">
            <div class="container mx-auto flex flex-col gap-4 py-6 md:flex-row">
                <div
                    v-if="reservations && reservations.length > 0"
                    ref="listToAnimate"
                    class="flex flex-1 flex-col gap-4"
                >
                    <div
                        class="space-y-4 text-sm"
                        v-for="reservation in reservations"
                        :key="reservation.id"
                    >
                        <div
                            class="space-y-2 rounded border border-blue-400 bg-gray-50 shadow"
                        >
                            <div
                                class="flex w-full items-start justify-between"
                            >
                                <div class="space-y-3 px-4 py-1.5">
                                    <h3 class="text-lg">
                                        <span class="font-semibold">{{
                                            reservation.cat_tarif.cat_tarif_type
                                                .nom
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
                                            class="mr-1 size-4 text-gray-600"
                                        />
                                        <p class="font-semibold">
                                            {{
                                                reservation.produit.adresse.city
                                            }}
                                            ({{
                                                reservation.produit.adresse
                                                    .zip_code
                                            }})
                                        </p>
                                    </div>
                                    <template
                                        v-if="
                                            reservation.produit.criteres
                                                .length > 0
                                        "
                                    >
                                        <div
                                            class="mt-auto grid w-full grid-cols-3 justify-items-center gap-1 text-xs text-gray-900 md:grid-cols-6"
                                        >
                                            <template
                                                v-for="critere in reservation
                                                    .produit.criteres"
                                                :key="critere.id"
                                            >
                                                <div
                                                    v-if="
                                                        critere.valeur &&
                                                        !!critere.critere
                                                            .visible_block ===
                                                            true
                                                    "
                                                    class="flex w-full flex-col items-center justify-center px-1 py-1 font-medium"
                                                    :class="
                                                        [
                                                            'date',
                                                            'dates',
                                                            'time',
                                                            'mois',
                                                            'times',
                                                        ].includes(
                                                            critere.critere
                                                                .type_champ_form
                                                        )
                                                            ? 'bg-slate-100'
                                                            : 'bg-slate-200'
                                                    "
                                                >
                                                    <div
                                                        class="text-center text-xs uppercase text-slate-500"
                                                    >
                                                        {{
                                                            critere.critere.nom
                                                        }}
                                                    </div>
                                                    <div
                                                        v-if="critere.valeur"
                                                        class="text-center text-xs"
                                                    >
                                                        {{ critere.valeur }}
                                                        <span
                                                            v-if="
                                                                critere.sous_criteres &&
                                                                critere
                                                                    .sous_criteres
                                                                    .length > 0
                                                            "
                                                            class="text-xs"
                                                        >
                                                            <span
                                                                v-for="sousCriteres in critere.sous_criteres"
                                                                :key="
                                                                    sousCriteres.id
                                                                "
                                                                class="text-sm"
                                                            >
                                                                ({{
                                                                    sousCriteres.valeur
                                                                }})
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <p
                                        v-if="reservation.plannings_count > 0"
                                        class="text-sm font-normal"
                                    >
                                        Vous avez
                                        <span class="font-semibold"
                                            >{{ reservation.plannings_count }}
                                        </span>
                                        <span
                                            v-if="
                                                reservation.plannings_count > 1
                                            "
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
                                    class="bg-red-400 p-2 hover:bg-red-500"
                                    type="button"
                                    @click.prevent="
                                        deleteReservation(reservation)
                                    "
                                >
                                    <span class="sr-only"
                                        >Supprimer la réservation du
                                        panier</span
                                    >
                                    <XMarkIcon class="h-6 w-6 text-white" />
                                </button>
                            </div>
                            <template
                                v-if="
                                    reservation.plannings &&
                                    reservation.plannings.length > 0
                                "
                            >
                                <div
                                    class="flex items-center justify-between space-x-2 px-4"
                                    v-for="creneau in reservation.plannings"
                                    :key="creneau.id"
                                >
                                    <div class="flex flex-1 items-center">
                                        <CalendarDaysIcon
                                            class="me-3 size-4 flex-shrink-0 text-gray-700"
                                        />
                                        <p class="text-sm text-gray-700">
                                            <span
                                                class="text-sm font-semibold"
                                                >{{
                                                    formatDate(creneau.start)
                                                }}</span
                                            >
                                            au
                                            <span
                                                class="text-sm font-semibold"
                                                >{{
                                                    formatDate(creneau.end)
                                                }}</span
                                            >
                                        </p>
                                    </div>

                                    <div
                                        class="flex items-center space-x-1 self-end"
                                    >
                                        <div
                                            class="inline-block rounded-lg border border-gray-200 bg-white px-3 py-2 dark:border-gray-700"
                                            data-hs-input-number
                                        >
                                            <div
                                                class="flex items-center gap-x-1.5"
                                            >
                                                <button
                                                    type="button"
                                                    class="inline-flex size-6 items-center justify-center gap-x-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
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
                                                    class="w-6 border-0 bg-transparent p-0 text-center text-gray-800 focus:ring-0"
                                                    type="text"
                                                    v-model="
                                                        panierForm.quantity[
                                                            reservation.id
                                                        ][creneau.id]
                                                    "
                                                    data-hs-input-number-input
                                                />
                                                <button
                                                    type="button"
                                                    class="inline-flex size-6 items-center justify-center gap-x-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
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
                                        <p
                                            class="text-lg font-bold text-green-700"
                                        >
                                            {{
                                                getCreneauAmount(
                                                    reservation,
                                                    creneau
                                                )
                                            }}
                                            €
                                        </p>
                                        <button
                                            class="ml-4"
                                            type="button"
                                            @click.prevent="
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
                                                class="h-5 w-5 text-gray-400 hover:text-red-500"
                                            />
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div
                                    class="flex w-full items-center justify-end space-x-2 px-2"
                                >
                                    <div
                                        class="inline-block rounded-lg border border-gray-200 bg-white px-3 py-2 dark:border-gray-700"
                                        data-hs-input-number
                                    >
                                        <div
                                            class="flex items-center gap-x-1.5"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex size-6 items-center justify-center gap-x-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
                                                @click="
                                                    decrementQuantity(
                                                        reservation.id
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
                                                class="w-6 border-0 bg-transparent p-0 text-center text-gray-800 focus:ring-0"
                                                type="text"
                                                v-model="
                                                    panierForm.quantity[
                                                        reservation.id
                                                    ]
                                                "
                                                data-hs-input-number-input
                                            />
                                            <button
                                                type="button"
                                                class="inline-flex size-6 items-center justify-center gap-x-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50"
                                                @click="
                                                    incrementQuantity(
                                                        reservation.id
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
                                    <p class="text-lg font-bold text-green-700">
                                        {{ getCreneauAmount(reservation) }} €
                                    </p>
                                </div>
                            </template>
                            <template
                                v-if="
                                    reservation.attributs &&
                                    reservation.attributs.length > 0
                                "
                                ><h3 class="px-4 text-sm text-gray-700">
                                    Les attributs liés à cette réservation:
                                </h3>
                                <div
                                    class="mt-auto grid w-full grid-cols-3 justify-items-center gap-1 px-4 text-xs text-gray-900 md:grid-cols-6"
                                >
                                    <template
                                        v-for="attribut in reservation.attributs"
                                        :key="attribut.id"
                                    >
                                        <div
                                            v-if="attribut.booking_field"
                                            class="flex w-full flex-col items-center justify-center bg-gray-100 px-1 py-1 font-medium"
                                        >
                                            <div
                                                class="text-center text-xs uppercase text-slate-500"
                                            >
                                                {{ attribut.booking_field.nom }}
                                            </div>
                                            <div
                                                v-if="attribut.valeur"
                                                class="text-center text-xs"
                                            >
                                                {{ attribut.valeur }}
                                                <div
                                                    v-if="
                                                        attribut.reservation_sous_attributs &&
                                                        attribut
                                                            .reservation_sous_attributs
                                                            .length > 0
                                                    "
                                                    class="text-xs"
                                                >
                                                    <span
                                                        v-for="sousattribut in attribut.reservation_sous_attributs"
                                                        :key="sousattribut.id"
                                                        class="text-xs text-gray-700"
                                                        ><span
                                                            class="text-slate-500"
                                                            v-if="
                                                                sousattribut.booking_sous_field
                                                            "
                                                            >{{
                                                                sousattribut
                                                                    .booking_sous_field
                                                                    .nom
                                                            }}:
                                                        </span>
                                                        {{
                                                            sousattribut.valeur
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <div
                                class="flex w-full justify-between border-t border-gray-200 bg-white px-4 py-2 text-lg font-bold"
                            >
                                <div class="text-gray-700">Montant:</div>
                                <div class="text-green-700">
                                    {{ totalReservationAmount(reservation) }}
                                    €
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="flex-1 px-2">
                    <p class="text-lg text-gray-700">Votre panier est vide.</p>
                </div>

                <div
                    class="mt-6 h-full w-full rounded-lg border border-blue-400 bg-gray-50 p-6 text-xl font-bold text-gray-700 shadow-md md:mt-0 md:w-1/3"
                >
                    <div class="my-4 w-full">
                        <label
                            for="codePromo"
                            class="mb-2 block text-sm font-medium text-gray-700"
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
                </div>
            </div>
        </form>
    </ResultLayout>
</template>
