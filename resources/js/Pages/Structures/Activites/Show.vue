<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ref, watch, onMounted, defineAsyncComponent, toRaw } from "vue";
import { debounce } from "lodash";
import CritereForm from "@/Components/Criteres/CritereForm.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import LeafletMap from "@/Components/Maps/LeafletMap.vue";
import { isClient } from "@vueuse/shared";
import { useShare } from "@vueuse/core";
import autoAnimate from "@formkit/auto-animate";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import {
    ArrowPathIcon,
    AtSymbolIcon,
    MapPinIcon,
    PhoneIcon,
    ShareIcon,
} from "@heroicons/vue/24/outline";
import { parse, isValid } from "date-fns";
import { fr } from "date-fns/locale";
dayjs.locale("fr");

const props = defineProps({
    departement: Object,
    city: Object,
    citiesAround: Object,
    allCities: Object,
    familles: Object,
    listDisciplines: Object,
    discipline: Object,
    activite: Object,
    criteres: Object,
    activiteSimilaires: Object,
    selectedProduit: Object,
    requestCategory: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    structuretypeElected: Object,
    produits: Object,
    filters: Object,
    currentRoute: Object,
});

const ProduitFormuleCard = defineAsyncComponent(() =>
    import("@/Components/Produits/ProduitFormuleCard.vue")
);

const ModalReservationForm = defineAsyncComponent(() =>
    import("@/Components/Modals/ModalReservationForm.vue")
);

const ActiviteCard = defineAsyncComponent(() =>
    import("@/Components/Structures/ActiviteCard.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const showReservationModal = ref(false);
const openReservationModal = () => {
    showReservationModal.value = true;
};

const options = ref({
    title: props.activite.titre,
    url: isClient ? location.href : "",
});
const { share, isSupported } = useShare(options);

async function startShare() {
    try {
        return await share();
    } catch (err) {
        return err;
    }
}

const listToAnimate = ref();
const filteredProduits = ref(props.produits.data);

const formCriteres = useForm({
    criteresBase: props.filters?.crit ?? {},
    sousCriteres: props.filters?.ssCrit ?? {},
    page: props.produits.meta.current_page,
});

const debouncedFilter = debounce((newFormCriteres) => {
    router.post(
        route(props.currentRoute.name, props.currentRoute.params),
        {
            crit: newFormCriteres.criteresBase,
            ssCrit: newFormCriteres.sousCriteres,
            page: newFormCriteres.page,
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ["produits", "filters"],
            onSuccess: () => {
                filteredProduits.value = props.produits.data;
            },
        }
    );
}, 350);

watch(
    () => formCriteres,
    (newFormCriteres) => {
        debouncedFilter(newFormCriteres);
    },
    { deep: true }
);

const isEqual = (obj1, obj2) => JSON.stringify(obj1) === JSON.stringify(obj2);

watch(
    () => formCriteres.criteresBase,
    (newCritValue) => {
        // Get the raw (non-reactive) version of the new value
        const rawNewValue = toRaw(newCritValue);
        // Compare with the previous value
        if (!isEqual(rawNewValue, previousCriteresBase)) {
            Object.keys(rawNewValue).forEach((critereId) => {
                if (
                    !isEqual(
                        rawNewValue[critereId],
                        previousCriteresBase[critereId]
                    )
                ) {
                    resetSousCriteres(critereId, rawNewValue[critereId]);
                }
            });
        }
        // Update the previous value for the next comparison
        previousCriteresBase = { ...rawNewValue };
    },
    { deep: true }
);

// Initialize previousCriteresBase
let previousCriteresBase = { ...toRaw(formCriteres.criteresBase) };

const resetSousCriteres = (critereId, newValeur) => {
    const critere = props.criteres.find((c) => c.id.toString() === critereId);

    if (critere && critere.valeurs) {
        critere.valeurs.forEach((valeur) => {
            if (valeur.sous_criteres) {
                valeur.sous_criteres.forEach((sousCritere) => {
                    if (
                        formCriteres.sousCriteres[sousCritere.id] !== undefined
                    ) {
                        formCriteres.sousCriteres[sousCritere.id] = null;
                    }
                });
            }
        });
    }
};

const resetFormCriteres = () => {
    formCriteres.reset();
    formCriteres.page = 1;
    debouncedFilter(formCriteres);
};

const handlePageChange = (newPage) => {
    if (newPage === "previous") {
        formCriteres.page = Math.max(1, formCriteres.page - 1);
    } else if (newPage === "next") {
        formCriteres.page = formCriteres.page + 1;
    } else {
        formCriteres.page = newPage;
    }
    debouncedFilter(formCriteres);
};

watch(
    () => props.selectedProduit,
    (newValue) => {
        if (newValue) {
            formCriteres.criteresBase = {};
            formCriteres.sousCriteres = {};

            newValue.criteres.forEach((critere) => {
                const critereId = critere.critere_id;
                const produitValeur = critere.valeur;
                const critereValue = critere.critere_valeur;
                const sousCriteres = critere.sous_criteres;

                props.criteres.forEach((officialCritere) => {
                    if (
                        officialCritere.id === critereId &&
                        produitValeur !== null
                    ) {
                        if (officialCritere.type_champ_form === "time") {
                            const [hours, minutes] = produitValeur
                                .split("h")
                                .map(Number);
                            const date = new Date();
                            date.setHours(hours);
                            date.setMinutes(minutes);
                            formCriteres.criteresBase[critereId] = date;
                        } else if (officialCritere.type_champ_form === "date") {
                            const parsedDate = parse(
                                produitValeur,
                                "d MMMM yyyy",
                                new Date(),
                                { locale: fr }
                            );
                            if (isValid(parsedDate)) {
                                formCriteres.criteresBase[critereId] =
                                    parsedDate;
                            }
                        } else if (
                            officialCritere.type_champ_form === "dates"
                        ) {
                            const [start, end] = produitValeur
                                .split(" au ")
                                .map((dateStr) => {
                                    const parsedDate = parse(
                                        dateStr,
                                        "d MMMM yyyy",
                                        new Date(),
                                        { locale: fr }
                                    );
                                    return isValid(parsedDate)
                                        ? parsedDate
                                        : null;
                                });
                            if (start && end) {
                                formCriteres.criteresBase[critereId] = [
                                    start,
                                    end,
                                ];
                            }
                        } else if (officialCritere.type_champ_form === "mois") {
                            const [startMonthStr, endMonthStr] =
                                produitValeur.split(" à ");
                            const prodStartMonth = parse(
                                startMonthStr.trim(),
                                "MMMM yyyy",
                                new Date(),
                                { locale: fr }
                            );
                            const prodEndMonth = parse(
                                endMonthStr.trim(),
                                "MMMM yyyy",
                                new Date(),
                                { locale: fr }
                            );
                            if (prodStartMonth && prodEndMonth) {
                                formCriteres.criteresBase[critereId] = {
                                    monthStart: prodStartMonth,
                                    monthEnd: prodEndMonth,
                                };
                            }
                        } else if (
                            officialCritere.type_champ_form === "times"
                        ) {
                            const [startStr, endStr] =
                                produitValeur.split(" à ");
                            const [startHours, startMinutes] = startStr
                                .split("h")
                                .map(Number);
                            const [endHours, endMinutes] = endStr
                                .split("h")
                                .map(Number);

                            const startDate = new Date();
                            startDate.setHours(startHours);
                            startDate.setMinutes(startMinutes);

                            const endDate = new Date();
                            endDate.setHours(endHours);
                            endDate.setMinutes(endMinutes);

                            formCriteres.criteresBase[critereId] = {
                                debut: startDate,
                                fin: endDate,
                            };
                        } else if (
                            officialCritere.type_champ_form === "range"
                        ) {
                            formCriteres.criteresBase[critereId] =
                                Number(produitValeur);
                        } else if (
                            officialCritere.type_champ_form === "range multiple"
                        ) {
                            const matches = produitValeur.match(/(\d+)/g);
                            if (matches && matches.length === 2) {
                                const [minVal, maxVal] = matches.map(Number); // Convert to numbers
                                formCriteres.criteresBase[critereId] = [
                                    minVal,
                                    maxVal,
                                ];
                            }
                        } else if (officialCritere.valeurs.length > 0) {
                            officialCritere.valeurs.forEach(
                                (officialCritereValeur) => {
                                    if (
                                        officialCritereValeur.id ===
                                        critereValue.id
                                    ) {
                                        if (
                                            !formCriteres.criteresBase[
                                                critereId
                                            ]
                                        ) {
                                            if (
                                                officialCritere.type_champ_form ===
                                                "checkbox"
                                            ) {
                                                formCriteres.criteresBase[
                                                    critereId
                                                ] = [officialCritereValeur];
                                            } else {
                                                formCriteres.criteresBase[
                                                    critereId
                                                ] = officialCritereValeur;
                                            }
                                        } else {
                                            const existingValue =
                                                formCriteres.criteresBase[
                                                    critereId
                                                ];

                                            if (!Array.isArray(existingValue)) {
                                                formCriteres.criteresBase[
                                                    critereId
                                                ] = [existingValue];
                                                if (
                                                    !formCriteres.criteresBase[
                                                        critereId
                                                    ].includes(
                                                        officialCritereValeur
                                                    )
                                                ) {
                                                    formCriteres.criteresBase[
                                                        critereId
                                                    ].push(
                                                        officialCritereValeur
                                                    );
                                                }
                                            } else {
                                                if (
                                                    !formCriteres.criteresBase[
                                                        critereId
                                                    ].includes(
                                                        officialCritereValeur
                                                    )
                                                ) {
                                                    formCriteres.criteresBase[
                                                        critereId
                                                    ].push(
                                                        officialCritereValeur
                                                    );
                                                }
                                            }
                                        }
                                        if (
                                            officialCritereValeur.sous_criteres
                                        ) {
                                            officialCritereValeur.sous_criteres.forEach(
                                                (officialSousCritere) => {
                                                    const souscritereId =
                                                        officialSousCritere.id;

                                                    sousCriteres.forEach(
                                                        (sousCritere) => {
                                                            const prodSousCritValeur =
                                                                sousCritere.valeur;

                                                            if (
                                                                souscritereId ===
                                                                    sousCritere.sous_critere_id &&
                                                                prodSousCritValeur !==
                                                                    null
                                                            ) {
                                                                if (
                                                                    officialSousCritere.type_champ_form ===
                                                                    "time"
                                                                ) {
                                                                    const [
                                                                        hours,
                                                                        minutes,
                                                                    ] =
                                                                        prodSousCritValeur
                                                                            .split(
                                                                                "h"
                                                                            )
                                                                            .map(
                                                                                Number
                                                                            );
                                                                    const date =
                                                                        new Date();
                                                                    date.setHours(
                                                                        hours
                                                                    );
                                                                    date.setMinutes(
                                                                        minutes
                                                                    );
                                                                    formCriteres.sousCriteres[
                                                                        souscritereId
                                                                    ] = date;
                                                                } else if (
                                                                    officialSousCritere.type_champ_form ===
                                                                    "date"
                                                                ) {
                                                                    const parsedDate =
                                                                        parse(
                                                                            prodSousCritValeur,
                                                                            "d MMMM yyyy",
                                                                            new Date(),
                                                                            {
                                                                                locale: fr,
                                                                            }
                                                                        );
                                                                    if (
                                                                        isValid(
                                                                            parsedDate
                                                                        )
                                                                    ) {
                                                                        formCriteres.sousCriteres[
                                                                            souscritereId
                                                                        ] =
                                                                            parsedDate;
                                                                    }
                                                                } else if (
                                                                    officialSousCritere.type_champ_form ===
                                                                    "dates"
                                                                ) {
                                                                    const [
                                                                        start,
                                                                        end,
                                                                    ] =
                                                                        prodSousCritValeur
                                                                            .split(
                                                                                " au "
                                                                            )
                                                                            .map(
                                                                                (
                                                                                    dateStr
                                                                                ) => {
                                                                                    const parsedDate =
                                                                                        parse(
                                                                                            dateStr,
                                                                                            "d MMMM yyyy",
                                                                                            new Date(),
                                                                                            {
                                                                                                locale: fr,
                                                                                            }
                                                                                        );
                                                                                    return isValid(
                                                                                        parsedDate
                                                                                    )
                                                                                        ? parsedDate
                                                                                        : null;
                                                                                }
                                                                            );
                                                                    if (
                                                                        start &&
                                                                        end
                                                                    ) {
                                                                        formCriteres.sousCriteres[
                                                                            souscritereId
                                                                        ] = [
                                                                            start,
                                                                            end,
                                                                        ];
                                                                    }
                                                                } else if (
                                                                    officialSousCritere.type_champ_form ===
                                                                    "mois"
                                                                ) {
                                                                    const [
                                                                        startMonthStr,
                                                                        endMonthStr,
                                                                    ] =
                                                                        prodSousCritValeur.split(
                                                                            " à "
                                                                        );
                                                                    const prodStartMonth =
                                                                        parse(
                                                                            startMonthStr.trim(),
                                                                            "MMMM yyyy",
                                                                            new Date(),
                                                                            {
                                                                                locale: fr,
                                                                            }
                                                                        );
                                                                    const prodEndMonth =
                                                                        parse(
                                                                            endMonthStr.trim(),
                                                                            "MMMM yyyy",
                                                                            new Date(),
                                                                            {
                                                                                locale: fr,
                                                                            }
                                                                        );
                                                                    if (
                                                                        prodStartMonth &&
                                                                        prodEndMonth
                                                                    ) {
                                                                        formCriteres.sousCriteres[
                                                                            souscritereId
                                                                        ] = {
                                                                            monthStart:
                                                                                prodStartMonth,
                                                                            monthEnd:
                                                                                prodEndMonth,
                                                                        };
                                                                    }
                                                                } else if (
                                                                    officialSousCritere.type_champ_form ===
                                                                    "times"
                                                                ) {
                                                                    const [
                                                                        startStr,
                                                                        endStr,
                                                                    ] =
                                                                        prodSousCritValeur.split(
                                                                            " à "
                                                                        );
                                                                    const [
                                                                        startHours,
                                                                        startMinutes,
                                                                    ] = startStr
                                                                        .split(
                                                                            "h"
                                                                        )
                                                                        .map(
                                                                            Number
                                                                        );
                                                                    const [
                                                                        endHours,
                                                                        endMinutes,
                                                                    ] = endStr
                                                                        .split(
                                                                            "h"
                                                                        )
                                                                        .map(
                                                                            Number
                                                                        );

                                                                    const startDate =
                                                                        new Date();
                                                                    startDate.setHours(
                                                                        startHours
                                                                    );
                                                                    startDate.setMinutes(
                                                                        startMinutes
                                                                    );

                                                                    const endDate =
                                                                        new Date();
                                                                    endDate.setHours(
                                                                        endHours
                                                                    );
                                                                    endDate.setMinutes(
                                                                        endMinutes
                                                                    );

                                                                    formCriteres.sousCriteres[
                                                                        souscritereId
                                                                    ] = {
                                                                        debut: startDate,
                                                                        fin: endDate,
                                                                    };
                                                                } else if (
                                                                    officialSousCritere.type_champ_form ===
                                                                    "range"
                                                                ) {
                                                                    formCriteres.sousCriteres[
                                                                        souscritereId
                                                                    ] =
                                                                        Number(
                                                                            prodSousCritValeur
                                                                        );
                                                                } else if (
                                                                    officialSousCritere.type_champ_form ===
                                                                    "range multiple"
                                                                ) {
                                                                    const matches =
                                                                        prodSousCritValeur.match(
                                                                            /(\d+)/g
                                                                        );
                                                                    if (
                                                                        matches &&
                                                                        matches.length ===
                                                                            2
                                                                    ) {
                                                                        const [
                                                                            minVal,
                                                                            maxVal,
                                                                        ] =
                                                                            matches.map(
                                                                                Number
                                                                            ); // Convert to numbers
                                                                        formCriteres.sousCriteres[
                                                                            souscritereId
                                                                        ] = [
                                                                            minVal,
                                                                            maxVal,
                                                                        ];
                                                                    }
                                                                } else if (
                                                                    officialSousCritere
                                                                        .sous_criteres_valeurs
                                                                        .length >
                                                                    0
                                                                ) {
                                                                    officialSousCritere.sous_criteres_valeurs.forEach(
                                                                        (
                                                                            officialSousCritereValeur
                                                                        ) => {
                                                                            const officialSousCritereValeurId =
                                                                                officialSousCritereValeur.id;

                                                                            const prodSousCritValeur =
                                                                                sousCritere.sous_critere_valeur;
                                                                            if (
                                                                                prodSousCritValeur &&
                                                                                prodSousCritValeur.id ===
                                                                                    officialSousCritereValeurId
                                                                            ) {
                                                                                if (
                                                                                    !formCriteres
                                                                                        .sousCriteres[
                                                                                        souscritereId
                                                                                    ]
                                                                                ) {
                                                                                    if (
                                                                                        officialSousCritere.type_champ_form ===
                                                                                        "checkbox"
                                                                                    ) {
                                                                                        formCriteres.sousCriteres[
                                                                                            souscritereId
                                                                                        ] =
                                                                                            [
                                                                                                officialSousCritereValeur,
                                                                                            ];
                                                                                    } else {
                                                                                        formCriteres.sousCriteres[
                                                                                            souscritereId
                                                                                        ] =
                                                                                            officialSousCritereValeur;
                                                                                    }
                                                                                } else {
                                                                                    const existingValue =
                                                                                        formCriteres
                                                                                            .sousCriteres[
                                                                                            souscritereId
                                                                                        ];

                                                                                    if (
                                                                                        !Array.isArray(
                                                                                            existingValue
                                                                                        )
                                                                                    ) {
                                                                                        formCriteres.sousCriteres[
                                                                                            souscritereId
                                                                                        ] =
                                                                                            [
                                                                                                existingValue,
                                                                                            ];
                                                                                        if (
                                                                                            !formCriteres.sousCriteres[
                                                                                                souscritereId
                                                                                            ].includes(
                                                                                                officialSousCritereValeur
                                                                                            )
                                                                                        ) {
                                                                                            formCriteres.sousCriteres[
                                                                                                souscritereId
                                                                                            ].push(
                                                                                                officialSousCritereValeur
                                                                                            );
                                                                                        }
                                                                                    } else {
                                                                                        if (
                                                                                            !formCriteres.sousCriteres[
                                                                                                souscritereId
                                                                                            ].includes(
                                                                                                officialSousCritereValeur
                                                                                            )
                                                                                        ) {
                                                                                            formCriteres.sousCriteres[
                                                                                                souscritereId
                                                                                            ].push(
                                                                                                officialSousCritereValeur
                                                                                            );
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    );
                                                                } else {
                                                                    const prodSousCritValeur =
                                                                        sousCritere.valeur;
                                                                    formCriteres.sousCriteres[
                                                                        souscritereId
                                                                    ] =
                                                                        prodSousCritValeur;
                                                                }
                                                            }
                                                        }
                                                    );
                                                }
                                            );
                                        }
                                    }
                                }
                            );
                        } else {
                            formCriteres.criteresBase[critereId] =
                                produitValeur;
                        }
                    }
                });
            });
        }
    },
    { deep: true, immediate: true }
);

const reservationForm = useForm({
    produit: props.selectedProduit?.id ?? null,
    formule: null,
    planning: null,
    remember: false,
});

onMounted(() => {
    if (listToAnimate.value) {
        autoAnimate(listToAnimate.value);
    }
});
</script>

<template>
    <Head :title="activite.titre + ' - ' + activite.structure.name">
        <meta
            head-key="description"
            name="description"
            :content="
                'Fiche détaillée de ' + activite.titre + '. Horaires et tarifs.'
            "
        />
    </Head>

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
        :departement="departement"
        :city="city"
        :discipline="discipline"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> {{ activite.titre }} </template>
            </ResultsHeader>
        </template>
        <template #default>
            <div
                class="sticky left-0 right-0 top-16 z-[998] bg-transparent backdrop-blur-md"
                ref="categoriesEl"
                v-if="categories && categories.length > 0"
            >
                <CategoriesResultNavigation
                    :city="city"
                    :departement="departement"
                    :discipline="discipline"
                    :all-structure-types="allStructureTypes"
                    :categories="categories"
                    :first-categories="firstCategories"
                    :categories-not-in-first="categoriesNotInFirst"
                    :category="requestCategory"
                    :structuretype-elected="structuretypeElected"
                />
            </div>

            <section
                class="mx-auto my-4 max-w-full px-2 sm:px-4 md:py-6 lg:px-8"
            >
                <div
                    class="flex flex-col-reverse rounded-lg text-slate-600 md:flex-row md:items-start md:justify-between md:space-x-6"
                >
                    <!-- Structure -->
                    <div
                        class="mt-10 basis-full space-y-6 md:mt-0 md:basis-1/4"
                    >
                        <img
                            v-if="activite.image"
                            alt="image"
                            :src="activite.image_url"
                            class="h-56 w-full max-w-sm object-cover md:w-auto"
                        />
                        <img
                            v-else
                            alt="image"
                            src="https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                            class="h-full w-full object-cover"
                        />
                        <div class="w-full space-y-2 bg-gray-200 pt-4">
                            <p class="px-2 text-sm text-gray-800">
                                Activité proposée par:
                            </p>
                            <Link
                                class="flex w-full justify-center px-2 text-lg text-indigo-600 hover:text-indigo-800"
                                :href="
                                    route('structures.show', activite.structure)
                                "
                                >{{ activite.structure.name }}</Link
                            >
                            <p
                                class="flex items-center space-x-2 px-2 text-sm text-gray-800"
                            >
                                <AtSymbolIcon class="h-4 w-4" />
                                {{ activite.structure.email }}
                            </p>
                            <p
                                class="flex items-center space-x-2 px-2 text-sm text-gray-800"
                            >
                                <PhoneIcon class="h-4 w-4" />
                                {{ activite.structure.phone1 }}
                            </p>
                            <div>
                                <h3
                                    class="mb-2 flex items-center space-x-2 px-2 text-sm text-gray-800"
                                >
                                    <MapPinIcon class="h-4 w-4" /> Localisation:
                                    <span class="font-semibold">
                                        {{
                                            activite.structure.adresses[0]
                                                .city_name
                                        }},
                                        {{
                                            activite.structure.adresses[0]
                                                .zip_code
                                        }}
                                    </span>
                                </h3>
                                <LeafletMap :item="activite.structure" />
                            </div>
                        </div>
                    </div>
                    <!-- Activité -->
                    <div class="basis-full space-y-8 md:basis-3/4">
                        <!-- titre -->
                        <div
                            class="mb-4 flex flex-col items-center justify-center"
                        >
                            <div class="flex w-full items-center">
                                <h1
                                    class="inline-block w-full text-center text-xl font-semibold sm:text-2xl sm:leading-7 md:text-3xl"
                                >
                                    {{ activite.titre }}
                                </h1>
                                <div
                                    v-if="isSupported"
                                    class="mt-4 hidden items-center justify-center self-end text-sm md:flex"
                                >
                                    <button
                                        class="flex items-center rounded border border-gray-200 bg-white p-2 text-gray-700 hover:bg-blue-600 hover:text-white"
                                        @click="startShare"
                                    >
                                        <ShareIcon class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <h2
                                class="inline-block w-full text-center text-xl font-semibold text-gray-500 sm:leading-5 md:text-xl"
                            >
                                {{ activite.structure.name }}
                            </h2>

                            <div
                                v-if="isSupported"
                                class="mt-4 flex items-center justify-center self-end text-sm md:hidden"
                            >
                                <button
                                    class="flex items-center rounded border border-gray-200 bg-white p-2 text-gray-700 hover:bg-blue-600 hover:text-white"
                                    @click="startShare"
                                >
                                    <ShareIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                        <!-- Resume -->
                        <div>
                            <p
                                v-if="activite.description"
                                class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                            >
                                {{ activite.description }}
                            </p>
                            <p
                                v-else-if="
                                    activite.structure.presentation_longue
                                "
                                class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                            >
                                {{ activite.structure.presentation_longue }}
                            </p>
                            <p
                                v-else
                                class="whitespace-pre-line text-base font-medium leading-5 text-gray-700"
                            >
                                {{ activite.structure.presentation_courte }}
                            </p>
                        </div>
                        <!-- instructeurs -->
                        <div v-if="activite.instructeurs.length > 0">
                            <p class="text-base font-medium text-gray-700">
                                Vos instructeurs:
                            </p>
                            <ul>
                                <li
                                    v-for="instructeur in activite.instructeurs"
                                    class="list-inside list-disc text-base font-semibold text-gray-600"
                                >
                                    {{ instructeur.pivot.contact }} -
                                    {{ instructeur.pivot.email }}
                                </li>
                            </ul>
                        </div>

                        <div class="flex w-full items-center justify-between">
                            <h3 class="text-xl text-gray-700">
                                Selectionner une formule en fonction de vos
                                critères:
                            </h3>
                            <button
                                class="group flex w-full items-center justify-center place-self-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-md transition duration-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 md:w-auto"
                                type="button"
                                @click.prevent="resetFormCriteres"
                            >
                                <ArrowPathIcon
                                    class="h-6 w-6 text-white transition duration-200 group-hover:-rotate-90 md:h-8 md:w-8"
                                />
                                <span class="ml-2"
                                    >Réinitialiser les critères</span
                                >
                            </button>
                        </div>

                        <div
                            v-if="criteres.length > 0"
                            class="mx-auto grid w-full grid-cols-1 gap-4 bg-gray-50 p-2 shadow md:grid-cols-3"
                        >
                            <CritereForm
                                v-if="criteres"
                                :criteres="criteres"
                                :filters="filters"
                                v-model:criteres-base="
                                    formCriteres.criteresBase
                                "
                                v-model:sous-criteres="
                                    formCriteres.sousCriteres
                                "
                                @reset-criteres="resetFormCriteres"
                            />
                        </div>

                        <div
                            v-if="filteredProduits.length > 0"
                            ref="listToAnimate"
                            class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 md:gap-8"
                        >
                            <ProduitFormuleCard
                                v-for="produit in filteredProduits"
                                :key="produit.id"
                                v-model:reservationProduit="
                                    reservationForm.produit
                                "
                                v-model:reservationFormule="
                                    reservationForm.formule
                                "
                                :produit="produit"
                            />
                            <div class="my-5 flex justify-end">
                                <Pagination
                                    :links="produits.meta.links"
                                    :only="['produits', 'filters']"
                                    @page-changed="handlePageChange"
                                />
                            </div>
                        </div>
                        <div v-else>
                            <p>Pas d'activité avec ces critères</p>
                        </div>

                        <div
                            v-if="
                                reservationForm.produit &&
                                reservationForm.formule
                            "
                            class="flex w-full items-center justify-center md:justify-end"
                        >
                            <button
                                @click.prevent="openReservationModal"
                                type="button"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-sm text-white duration-300 hover:-translate-y-1 hover:bg-blue-700 disabled:pointer-events-none disabled:opacity-50 md:w-auto"
                            >
                                Sélectionner
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-white">
                <div
                    class="mx-auto max-w-full px-4 py-16 sm:px-6 sm:py-24 lg:px-8"
                >
                    <h2
                        class="text-center text-2xl font-semibold tracking-tight text-gray-700 sm:text-3xl"
                    >
                        Les derniers avis sur cette activité
                    </h2>

                    <div
                        class="mx-auto mt-12 grid w-full grid-cols-1 gap-4 md:grid-cols-3 md:gap-12"
                    >
                        <blockquote class="relative">
                            <svg
                                class="absolute -start-8 -top-6 size-16 text-yellow-400"
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                    d="M7.39762 10.3C7.39762 11.0733 7.14888 11.7 6.6514 12.18C6.15392 12.6333 5.52552 12.86 4.76621 12.86C3.84979 12.86 3.09047 12.5533 2.48825 11.94C1.91222 11.3266 1.62421 10.4467 1.62421 9.29999C1.62421 8.07332 1.96459 6.87332 2.64535 5.69999C3.35231 4.49999 4.33418 3.55332 5.59098 2.85999L6.4943 4.25999C5.81354 4.73999 5.26369 5.27332 4.84476 5.85999C4.45201 6.44666 4.19017 7.12666 4.05926 7.89999C4.29491 7.79332 4.56983 7.73999 4.88403 7.73999C5.61716 7.73999 6.21938 7.97999 6.69067 8.45999C7.16197 8.93999 7.39762 9.55333 7.39762 10.3ZM14.6242 10.3C14.6242 11.0733 14.3755 11.7 13.878 12.18C13.3805 12.6333 12.7521 12.86 11.9928 12.86C11.0764 12.86 10.3171 12.5533 9.71484 11.94C9.13881 11.3266 8.85079 10.4467 8.85079 9.29999C8.85079 8.07332 9.19117 6.87332 9.87194 5.69999C10.5789 4.49999 11.5608 3.55332 12.8176 2.85999L13.7209 4.25999C13.0401 4.73999 12.4903 5.27332 12.0713 5.85999C11.6786 6.44666 11.4168 7.12666 11.2858 7.89999C11.5215 7.79332 11.7964 7.73999 12.1106 7.73999C12.8437 7.73999 13.446 7.97999 13.9173 8.45999C14.3886 8.93999 14.6242 9.55333 14.6242 10.3Z"
                                    fill="currentColor"
                                />
                            </svg>

                            <div class="relative z-10">
                                <p class="text-gray-800 sm:text-xl">
                                    <em>
                                        Certaines contraintes peuvent parfois
                                        affecter la pratique. Par exemple, la
                                        disponibilité limitée des créneaux
                                        horaires peut restreindre l'accès aux
                                        terrains, en particulier lors des
                                        périodes de forte affluence. De plus,
                                        des problèmes d'entretien des
                                        installations, tels que des terrains
                                        glissants ou des équipements défectueux,
                                        peuvent perturber le déroulement des
                                        séances et compromettre la sécurité des
                                        joueurs. Ces défis logistiques
                                        nécessitent une gestion efficace de la
                                        part de la direction du club afin
                                        d'assurer une expérience optimale pour
                                        tous les membres.</em
                                    >
                                </p>
                            </div>

                            <footer class="mt-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img
                                            class="size-10 rounded-full"
                                            src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80"
                                            alt="Image Description"
                                        />
                                    </div>
                                    <div class="ms-4">
                                        <div
                                            class="text-base font-semibold text-gray-800"
                                        >
                                            Josh Grazioso
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Source title
                                        </div>
                                    </div>
                                </div>
                            </footer>
                        </blockquote>
                        <blockquote class="relative">
                            <svg
                                class="absolute -start-8 -top-6 size-16 text-yellow-400"
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                    d="M7.39762 10.3C7.39762 11.0733 7.14888 11.7 6.6514 12.18C6.15392 12.6333 5.52552 12.86 4.76621 12.86C3.84979 12.86 3.09047 12.5533 2.48825 11.94C1.91222 11.3266 1.62421 10.4467 1.62421 9.29999C1.62421 8.07332 1.96459 6.87332 2.64535 5.69999C3.35231 4.49999 4.33418 3.55332 5.59098 2.85999L6.4943 4.25999C5.81354 4.73999 5.26369 5.27332 4.84476 5.85999C4.45201 6.44666 4.19017 7.12666 4.05926 7.89999C4.29491 7.79332 4.56983 7.73999 4.88403 7.73999C5.61716 7.73999 6.21938 7.97999 6.69067 8.45999C7.16197 8.93999 7.39762 9.55333 7.39762 10.3ZM14.6242 10.3C14.6242 11.0733 14.3755 11.7 13.878 12.18C13.3805 12.6333 12.7521 12.86 11.9928 12.86C11.0764 12.86 10.3171 12.5533 9.71484 11.94C9.13881 11.3266 8.85079 10.4467 8.85079 9.29999C8.85079 8.07332 9.19117 6.87332 9.87194 5.69999C10.5789 4.49999 11.5608 3.55332 12.8176 2.85999L13.7209 4.25999C13.0401 4.73999 12.4903 5.27332 12.0713 5.85999C11.6786 6.44666 11.4168 7.12666 11.2858 7.89999C11.5215 7.79332 11.7964 7.73999 12.1106 7.73999C12.8437 7.73999 13.446 7.97999 13.9173 8.45999C14.3886 8.93999 14.6242 9.55333 14.6242 10.3Z"
                                    fill="currentColor"
                                />
                            </svg>

                            <div class="relative z-10">
                                <p class="text-gray-800 sm:text-xl">
                                    <em>
                                        Au sein du club de badminton, les
                                        installations offrent un cadre idéal
                                        pour la pratique de ce sport
                                        passionnant. Les terrains bien
                                        entretenus et équipés de matériel de
                                        qualité garantissent des séances de jeu
                                        fluides et agréables. De plus, la
                                        disponibilité de plusieurs terrains
                                        permet aux membres de s'entraîner et de
                                        jouer en toute liberté, sans avoir à
                                        subir d'attente excessive. La présence
                                        de coachs qualifiés qui supervisent les
                                        séances contribue également à
                                        l'amélioration des performances de
                                        chacun, créant ainsi un environnement
                                        propice à l'épanouissement sportif et
                                        social.
                                    </em>
                                </p>
                            </div>

                            <footer class="mt-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img
                                            class="size-10 rounded-full"
                                            src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80"
                                            alt="Image Description"
                                        />
                                    </div>
                                    <div class="ms-4">
                                        <div
                                            class="text-base font-semibold text-gray-800"
                                        >
                                            Paul Dumont
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Sports et Loisirs
                                        </div>
                                    </div>
                                </div>
                            </footer>
                        </blockquote>
                        <blockquote class="relative">
                            <svg
                                class="absolute -start-8 -top-6 size-16 text-yellow-400"
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                    d="M7.39762 10.3C7.39762 11.0733 7.14888 11.7 6.6514 12.18C6.15392 12.6333 5.52552 12.86 4.76621 12.86C3.84979 12.86 3.09047 12.5533 2.48825 11.94C1.91222 11.3266 1.62421 10.4467 1.62421 9.29999C1.62421 8.07332 1.96459 6.87332 2.64535 5.69999C3.35231 4.49999 4.33418 3.55332 5.59098 2.85999L6.4943 4.25999C5.81354 4.73999 5.26369 5.27332 4.84476 5.85999C4.45201 6.44666 4.19017 7.12666 4.05926 7.89999C4.29491 7.79332 4.56983 7.73999 4.88403 7.73999C5.61716 7.73999 6.21938 7.97999 6.69067 8.45999C7.16197 8.93999 7.39762 9.55333 7.39762 10.3ZM14.6242 10.3C14.6242 11.0733 14.3755 11.7 13.878 12.18C13.3805 12.6333 12.7521 12.86 11.9928 12.86C11.0764 12.86 10.3171 12.5533 9.71484 11.94C9.13881 11.3266 8.85079 10.4467 8.85079 9.29999C8.85079 8.07332 9.19117 6.87332 9.87194 5.69999C10.5789 4.49999 11.5608 3.55332 12.8176 2.85999L13.7209 4.25999C13.0401 4.73999 12.4903 5.27332 12.0713 5.85999C11.6786 6.44666 11.4168 7.12666 11.2858 7.89999C11.5215 7.79332 11.7964 7.73999 12.1106 7.73999C12.8437 7.73999 13.446 7.97999 13.9173 8.45999C14.3886 8.93999 14.6242 9.55333 14.6242 10.3Z"
                                    fill="currentColor"
                                />
                            </svg>

                            <div class="relative z-10">
                                <p class="text-gray-800 sm:text-xl">
                                    <em>
                                        C'était à chier, mais je mets 5 étoiles
                                        pour le sourire de Roberta.
                                    </em>
                                </p>
                            </div>

                            <footer class="mt-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img
                                            class="size-10 rounded-full"
                                            src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80"
                                            alt="Image Description"
                                        />
                                    </div>
                                    <div class="ms-4">
                                        <div
                                            class="text-base font-semibold text-gray-800"
                                        >
                                            Bobby Grazioso
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Tits Manager
                                        </div>
                                    </div>
                                </div>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </section>
            <section v-if="activiteSimilaires.length > 0" class="bg-white">
                <div
                    class="mx-auto max-w-full px-4 py-16 sm:px-6 sm:py-24 lg:px-8"
                >
                    <h2
                        class="text-center text-2xl font-semibold tracking-tight text-gray-700 sm:text-3xl"
                    >
                        Les activités similaires
                    </h2>
                    <div
                        class="mt-12 grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-8"
                    >
                        <ActiviteCard
                            v-for="activite in activiteSimilaires"
                            :key="activite.id"
                            :activite="activite"
                            :link="
                                route('structures.activites.show', {
                                    activite: activite,
                                    slug: activite.slug_title,
                                })
                            "
                        />
                    </div>
                </div>
            </section>
            <ModalReservationForm
                v-if="reservationForm.produit && reservationForm.formule"
                :produit-id="reservationForm.produit"
                :cat-tarif-id="reservationForm.formule"
                :produits="produits.data"
                :show="showReservationModal"
                @close="showReservationModal = false"
            />
        </template>
    </ResultLayout>
</template>

<style>
.course {
    @apply bg-blue-400 text-white;
}
</style>
