<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, computed, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
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

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    customer: Object,
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

const listToAnimate = ref();

onMounted(() => {
    if (listToAnimate.value) {
        autoAnimate(listToAnimate.value);
    }
});
</script>
<template>
    <Head
        title="Mon compte client"
        description="Récapitulatif de mes réservations"
    />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title>Mes réservations</template>
            </ResultsHeader>
        </template>

        <div class="container mx-auto flex flex-col gap-4 py-6 md:flex-row">
            <div
                v-if="
                    customer &&
                    customer.reservations &&
                    customer.reservations.length > 0
                "
                ref="listToAnimate"
                class="flex flex-1 flex-col gap-4"
            >
                <div
                    class="space-y-4 text-sm"
                    v-for="reservation in customer.reservations"
                    :key="reservation.id"
                >
                    <div
                        class="space-y-2 rounded border border-blue-400 bg-gray-50 shadow"
                    >
                        <div class="flex w-full items-start justify-between">
                            <div class="space-y-3 px-4 py-1.5">
                                <h3 class="text-lg">
                                    <span
                                        v-if="reservation.cat_tarif"
                                        class="font-semibold"
                                        >{{
                                            reservation.cat_tarif.cat_tarif_type
                                                .nom
                                        }}</span
                                    >
                                    pour
                                    <span class="font-semibold">{{
                                        reservation.activite_title
                                    }}</span>
                                </h3>
                                <div
                                    v-if="
                                        reservation.produit &&
                                        reservation.produit.adresse
                                    "
                                    class="flex items-center text-base"
                                >
                                    <dt class="sr-only">Ville</dt>
                                    <MapPinIcon
                                        class="mr-1 size-4 text-gray-600"
                                    />
                                    <p class="font-semibold">
                                        {{ reservation.produit.adresse.city }}
                                        ({{
                                            reservation.produit.adresse
                                                .zip_code
                                        }})
                                    </p>
                                </div>
                                <template
                                    v-if="
                                        reservation.produit &&
                                        reservation.produit.criteres &&
                                        reservation.produit.criteres.length > 0
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
                                                        .visible_block === true
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
                                                    {{ critere.critere.nom }}
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
                        </div>
                        <template v-if="reservation.cat_tarif"
                            ><h3 class="px-4 text-sm text-gray-700">
                                Les attributs liés au tarif:
                            </h3>
                            <div
                                class="mt-auto grid w-full grid-cols-3 justify-items-center gap-1 px-4 text-xs text-gray-900 md:grid-cols-6"
                            >
                                <template
                                    v-for="attribut in reservation.cat_tarif
                                        .attributs"
                                    :key="attribut.id"
                                >
                                    <div
                                        v-if="attribut.tarif_attribut"
                                        class="flex w-full flex-col items-center justify-center bg-gray-100 px-1 py-1 font-medium"
                                    >
                                        <div
                                            class="text-center text-xs uppercase text-slate-500"
                                        >
                                            {{ attribut.tarif_attribut.nom }}
                                        </div>
                                        <div
                                            v-if="attribut.valeur"
                                            class="text-center text-xs"
                                        >
                                            {{ attribut.valeur }}
                                            <div
                                                v-if="
                                                    attribut.sous_attributs &&
                                                    attribut.sous_attributs
                                                        .length > 0
                                                "
                                                class="text-xs"
                                            >
                                                <span
                                                    v-for="sousattribut in attribut.sous_attributs"
                                                    :key="sousattribut.id"
                                                    class="text-xs text-gray-700"
                                                    ><span
                                                        class="text-slate-500"
                                                        v-if="
                                                            sousattribut.sous_attribut
                                                        "
                                                        >{{
                                                            sousattribut
                                                                .sous_attribut
                                                                .nom
                                                        }}:
                                                    </span>
                                                    {{ sousattribut.valeur }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
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
                                                <p
                                                    v-for="sousattribut in attribut.reservation_sous_attributs"
                                                    :key="sousattribut.id"
                                                    class="text-xs text-gray-700"
                                                >
                                                    <span
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
                                                    {{ sousattribut.valeur }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template
                            v-if="
                                reservation.plannings &&
                                reservation.plannings.length > 0
                            "
                        >
                            <div
                                class="flex items-center justify-between space-x-2 px-1 md:px-4"
                                v-for="creneau in reservation.plannings"
                                :key="creneau.id"
                            >
                                <div class="flex flex-1 items-center">
                                    <CalendarDaysIcon
                                        class="me-3 size-4 flex-shrink-0 text-gray-700"
                                    />
                                    <p class="text-xs text-gray-700 md:text-sm">
                                        <span class="font-semibold">{{
                                            formatDate(creneau.start)
                                        }}</span>
                                        au
                                        <span class="font-semibold">{{
                                            formatDate(creneau.end)
                                        }}</span>
                                    </p>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div
                                class="flex w-full items-center justify-end space-x-2 px-2"
                            ></div>
                        </template>

                        <div
                            class="flex w-full justify-between border-t border-gray-200 bg-white px-4 py-2 text-lg font-bold"
                        >
                            <div class="text-gray-700">Montant:</div>
                            <div class="text-green-700">€</div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="flex-1 px-2">
                <p class="text-lg text-gray-700">Votre panier est vide.</p>
            </div>
        </div>
    </ResultLayout>
</template>
