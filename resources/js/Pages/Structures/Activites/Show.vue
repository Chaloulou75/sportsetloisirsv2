<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted, defineAsyncComponent } from "vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import LeafletMap from "@/Components/Maps/LeafletMap.vue";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import CheckboxForm from "@/Components/Forms/CheckboxForm.vue";
import RadioForm from "@/Components/Forms/RadioForm.vue";
import RangeInputForm from "@/Components/Forms/RangeInputForm.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import OpenDaysForm from "@/Components/Forms/DayTime/OpenDaysForm.vue";
import SingleDateForm from "@/Components/Forms/DayTime/SingleDateForm.vue";
import SingleTimeForm from "@/Components/Forms/DayTime/SingleTimeForm.vue";
import OpenTimesForm from "@/Components/Forms/DayTime/OpenTimesForm.vue";
import OpenMonthsForm from "@/Components/Forms/DayTime/OpenMonthsForm.vue";
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
import {
    parse,
    isValid,
    isSameDay,
    endOfMonth,
    isWithinInterval,
} from "date-fns";
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
const filteredProduits = ref(props.produits);
const selectedProduct = ref();
const selectedCriteres = ref([]);
const selectedSousCriteres = ref([]);

const critereForm = ref({
    criteres: {},
    souscriteres: {},
});

const updateSelectedCheckboxes = (critereId, optionValue, checked) => {
    if (checked) {
        if (!critereForm.value.criteres[critereId]) {
            critereForm.value.criteres[critereId] = [optionValue];
        } else {
            critereForm.value.criteres[critereId].push(optionValue);
        }
    } else {
        const selectedCritere = critereForm.value.criteres[critereId];
        if (selectedCritere) {
            const index = selectedCritere.indexOf(optionValue);
            if (index !== -1) {
                selectedCritere.splice(index, 1);
            }
            if (selectedCritere.length === 0) {
                delete critereForm.value.criteres[critereId];
            }
        }
    }
};

const isCheckboxSelected = computed(() => {
    return (critereId, optionValue) => {
        return (
            critereForm.value.criteres[critereId] &&
            critereForm.value.criteres[critereId].includes(optionValue)
        );
    };
});

const filterProducts = () => {
    if (selectedCriteres.value.length === 0) {
        filteredProduits.value = props.produits;
    } else {
        filteredProduits.value = props.produits.filter((produit) => {
            return (
                selectedCriteres.value.every((selectedCritereEntry) => {
                    const [critereId, selectedCritere] = selectedCritereEntry;
                    const numericCritereId = parseInt(critereId);
                    if (
                        selectedCritere === null ||
                        selectedCritere.inclus_all === true
                    ) {
                        return true;
                    } else if (Array.isArray(selectedCritere)) {
                        return selectedCritere.some((critereInArray) => {
                            if (critereInArray === null) {
                                return true;
                            } else if (
                                critereInArray !== null &&
                                typeof critereInArray === "object" &&
                                "month" in critereInArray &&
                                "year" in critereInArray
                            ) {
                                // MONTHS
                                const startMonth = selectedCritere[0]
                                    ? new Date(
                                          parseInt(selectedCritere[0].year),
                                          selectedCritere[0].month,
                                          1
                                      )
                                    : null;

                                const endMonth = selectedCritere[1]
                                    ? new Date(
                                          parseInt(selectedCritere[1].year),
                                          selectedCritere[1].month,
                                          1
                                      )
                                    : null;
                                if (startMonth && endMonth) {
                                    return produit.criteres.some(
                                        (produitCritere) => {
                                            if (
                                                numericCritereId ===
                                                produitCritere.critere_id
                                            ) {
                                                const [
                                                    startMonthStr,
                                                    endMonthStr,
                                                ] =
                                                    produitCritere.valeur.split(
                                                        " à "
                                                    );

                                                const prodStartMonth = parse(
                                                    startMonthStr,
                                                    "MMMM yyyy",
                                                    new Date(),
                                                    {
                                                        locale: fr,
                                                    }
                                                );
                                                const prodEndMonth = parse(
                                                    endMonthStr,
                                                    "MMMM yyyy",
                                                    new Date(),
                                                    {
                                                        locale: fr,
                                                    }
                                                );

                                                if (
                                                    isValid(prodStartMonth) &&
                                                    isValid(prodEndMonth)
                                                ) {
                                                    const lastDayProdEndMonth =
                                                        endOfMonth(
                                                            prodEndMonth
                                                        );
                                                    return (
                                                        isWithinInterval(
                                                            startMonth,
                                                            {
                                                                start: prodStartMonth,
                                                                end: lastDayProdEndMonth,
                                                            }
                                                        ) &&
                                                        isWithinInterval(
                                                            endMonth,
                                                            {
                                                                start: prodStartMonth,
                                                                end: lastDayProdEndMonth,
                                                            }
                                                        )
                                                    );
                                                } else {
                                                    return false;
                                                }
                                            } else {
                                                return false;
                                            }
                                        }
                                    );
                                } else {
                                    return false;
                                }
                            } else if (
                                critereInArray !== null &&
                                Array.isArray(selectedCritere) &&
                                selectedCritere.every(
                                    (date) => date instanceof Date
                                )
                            ) {
                                //DATES
                                return produit.criteres.some(
                                    (produitCritere) => {
                                        if (
                                            numericCritereId ===
                                            produitCritere.critere_id
                                        ) {
                                            const [
                                                startProdDateStr,
                                                endProdDateStr,
                                            ] =
                                                produitCritere.valeur.split(
                                                    " au "
                                                );
                                            const startProdDate = parse(
                                                startProdDateStr,
                                                "d MMMM yyyy",
                                                new Date(),
                                                { locale: fr }
                                            );
                                            const endProdDate = parse(
                                                endProdDateStr,
                                                "d MMMM yyyy",
                                                new Date(),
                                                { locale: fr }
                                            );

                                            if (
                                                isValid(startProdDate) &&
                                                isValid(endProdDate)
                                            ) {
                                                return selectedCritere.every(
                                                    (selectedDate) => {
                                                        return isWithinInterval(
                                                            selectedDate,
                                                            {
                                                                start: startProdDate,
                                                                end: endProdDate,
                                                            }
                                                        );
                                                    }
                                                );
                                            } else {
                                                return false;
                                            }
                                        }
                                        return false;
                                    }
                                );
                            } else if (
                                Array.isArray(selectedCritere) &&
                                selectedCritere.length === 2 &&
                                selectedCritere.every(
                                    (timeObj) =>
                                        "hours" in timeObj &&
                                        "minutes" in timeObj
                                )
                            ) {
                                //TIMES
                                return produit.criteres.some(
                                    (produitCritere) => {
                                        if (
                                            numericCritereId ===
                                            produitCritere.critere_id
                                        ) {
                                            const [startTimeObj, endTimeObj] =
                                                selectedCritere;

                                            // Create Date objects for the selected start and end times
                                            const selectedStartTime = new Date(
                                                0,
                                                0,
                                                0,
                                                startTimeObj.hours,
                                                startTimeObj.minutes
                                            );
                                            const selectedEndTime = new Date(
                                                0,
                                                0,
                                                0,
                                                endTimeObj.hours,
                                                endTimeObj.minutes
                                            );

                                            const [startTimeStr, endTimeStr] =
                                                produitCritere.valeur.split(
                                                    " à "
                                                );

                                            // Parse start time
                                            const [startHours, startMinutes] =
                                                startTimeStr
                                                    .split("h")
                                                    .map(Number);
                                            const produitStartTime = new Date(
                                                0,
                                                0,
                                                0,
                                                startHours,
                                                startMinutes
                                            );

                                            // Parse end time
                                            const [endHours, endMinutes] =
                                                endTimeStr
                                                    .split("h")
                                                    .map(Number);
                                            const produitEndTime = new Date(
                                                0,
                                                0,
                                                0,
                                                endHours,
                                                endMinutes
                                            );
                                            // Check if the selected time range falls within the produit time range
                                            if (
                                                (selectedStartTime,
                                                selectedEndTime,
                                                produitStartTime,
                                                produitEndTime)
                                            ) {
                                                return (
                                                    isWithinInterval(
                                                        selectedStartTime,
                                                        {
                                                            start: produitStartTime,
                                                            end: produitEndTime,
                                                        }
                                                    ) &&
                                                    isWithinInterval(
                                                        selectedEndTime,
                                                        {
                                                            start: produitStartTime,
                                                            end: produitEndTime,
                                                        }
                                                    )
                                                );
                                            } else {
                                                return false;
                                            }
                                        } else {
                                            return false;
                                        }
                                    }
                                );
                            } else {
                                return produit.criteres.some(
                                    (produitCritere) => {
                                        const valeurIdExists =
                                            produitCritere.hasOwnProperty(
                                                "valeur_id"
                                            );
                                        if (
                                            valeurIdExists &&
                                            valeurIdExists !== null &&
                                            numericCritereId ===
                                                produitCritere.critere_id
                                        ) {
                                            return (
                                                produitCritere.valeur_id ===
                                                critereInArray.id
                                            );
                                        } else {
                                            return false;
                                        }
                                    }
                                );
                            }
                        });
                    } else {
                        return produit.criteres.some((produitCritere) => {
                            const valeurIdExists =
                                produitCritere.hasOwnProperty("valeur_id");
                            if (
                                valeurIdExists &&
                                valeurIdExists !== null &&
                                numericCritereId === produitCritere.critere_id
                            ) {
                                if (
                                    produitCritere.valeur_id ===
                                        selectedCritere.id ||
                                    (!!produitCritere.critere_valeur &&
                                        !!produitCritere.critere_valeur
                                            .inclus_all === true)
                                ) {
                                    return (
                                        (valeurIdExists &&
                                            produitCritere.valeur_id ===
                                                selectedCritere.id) ||
                                        (produitCritere.critere_valeur &&
                                            produitCritere.critere_valeur
                                                .inclus_all === true)
                                    );
                                } else if (
                                    typeof selectedCritere === "object" &&
                                    selectedCritere !== null &&
                                    numericCritereId ===
                                        produitCritere.critere_id
                                ) {
                                    //HORAIRE
                                    if (
                                        "hours" in selectedCritere &&
                                        "minutes" in selectedCritere
                                    ) {
                                        const timeSelectedCritere = `${String(
                                            selectedCritere.hours
                                        ).padStart(2, "0")}h${String(
                                            selectedCritere.minutes
                                        ).padStart(2, "0")}`;

                                        return (
                                            produitCritere.valeur ===
                                            timeSelectedCritere
                                        );
                                    } else if (
                                        selectedCritere instanceof Date &&
                                        numericCritereId ===
                                            produitCritere.critere_id
                                    ) {
                                        // DATE
                                        if (
                                            produitCritere.critere
                                                .type_champ_form === "date"
                                        ) {
                                            const produitDate = parse(
                                                produitCritere.valeur,
                                                "d MMMM yyyy",
                                                new Date(),
                                                { locale: fr }
                                            );
                                            if (isValid(produitDate)) {
                                                return isSameDay(
                                                    selectedCritere,
                                                    produitDate
                                                );
                                            } else {
                                                return false;
                                            }
                                        } else {
                                            return false;
                                        }
                                    } else {
                                        return false;
                                    }
                                } else if (
                                    typeof selectedCritere === "number" &&
                                    selectedCritere !== null
                                ) {
                                    // NUMBER
                                    const prodValeurAsNumber = parseFloat(
                                        produitCritere.valeur
                                    );
                                    if (!isNaN(prodValeurAsNumber)) {
                                        return (
                                            numericCritereId ===
                                                produitCritere.critere_id &&
                                            prodValeurAsNumber ===
                                                selectedCritere
                                        );
                                    } else {
                                        return false;
                                    }
                                } else if (
                                    typeof selectedCritere === "string" &&
                                    selectedCritere !== null
                                ) {
                                    if (selectedCritere.trim() === "") {
                                        return true;
                                    } else {
                                        return (
                                            numericCritereId ===
                                                produitCritere.critere_id &&
                                            produitCritere.valeur !== null &&
                                            produitCritere.valeur ===
                                                selectedCritere
                                        );
                                    }
                                } else {
                                    return false;
                                }
                            } else {
                                return false;
                            }
                        });
                    }
                }) &&
                selectedSousCriteres.value.every((selectedSousCritereEntry) => {
                    const [sousCritereId, selectedSousCritere] =
                        selectedSousCritereEntry;
                    const numericSousCritereId = parseInt(sousCritereId);
                    return produit.criteres.some((produitCritere) => {
                        return (
                            produitCritere.sous_criteres &&
                            produitCritere.sous_criteres.some((sousCritere) => {
                                if (
                                    typeof selectedSousCritere === "number" &&
                                    selectedSousCritere !== null &&
                                    numericSousCritereId ===
                                        sousCritere.sous_critere_id
                                ) {
                                    const prodValeurAsNumber = parseFloat(
                                        sousCritere.valeur
                                    );
                                    if (!isNaN(prodValeurAsNumber)) {
                                        return (
                                            numericSousCritereId ===
                                                sousCritere.sous_critere_id &&
                                            prodValeurAsNumber ===
                                                selectedSousCritere
                                        );
                                    } else {
                                        return false;
                                    }
                                } else if (
                                    typeof selectedSousCritere === "string" &&
                                    selectedSousCritere !== null
                                ) {
                                    if (selectedSousCritere.trim() === "") {
                                        return true;
                                    } else {
                                        return (
                                            numericSousCritereId ===
                                                sousCritere.sous_critere_id &&
                                            sousCritere.valeur !== null &&
                                            sousCritere.valeur ===
                                                selectedSousCritere
                                        );
                                    }
                                } else if (
                                    numericSousCritereId ===
                                    sousCritere.sous_critere_id
                                ) {
                                    return (
                                        sousCritere.sous_critere_valeur_id ===
                                        selectedSousCritere.id
                                    );
                                } else {
                                    return false;
                                }
                            })
                        );
                    });
                })
            );
        });
    }
};

watch(
    () => critereForm.value.criteres,
    (newCriteres) => {
        selectedCriteres.value = Object.entries(newCriteres);
        filterProducts();
    },
    { deep: true }
);

watch(
    () => critereForm.value.souscriteres,
    (newSousCriteres) => {
        selectedSousCriteres.value = Object.entries(newSousCriteres);
        filterProducts();
    },
    { deep: true }
);

watch(
    () => selectedProduct.value,
    (newValue) => {
        if (newValue) {
            critereForm.value.criteres = {};
            critereForm.value.souscriteres = {};

            props.criteres.forEach((officialCritere) => {
                newValue.criteres.forEach((critere) => {
                    const critereId = critere.critere_id;
                    const produitValeur = critere.valeur;
                    const critereValueId = critere.valeur_id;
                    const sousCriteres = critere.sous_criteres;
                    if (officialCritere.id === critereId) {
                        if (
                            officialCritere.type_champ_form === "time" &&
                            produitValeur !== null
                        ) {
                            const [hours, minutes] = produitValeur
                                .split("h")
                                .map(Number);
                            critereForm.value.criteres[critereId] = {
                                hours,
                                minutes,
                            };
                        } else if (
                            officialCritere.type_champ_form === "date" &&
                            produitValeur !== null
                        ) {
                            const parsedDate = parse(
                                produitValeur,
                                "d MMMM yyyy",
                                new Date(),
                                { locale: fr }
                            );
                            if (isValid(parsedDate)) {
                                critereForm.value.criteres[critereId] =
                                    parsedDate;
                            }
                        } else if (
                            officialCritere.type_champ_form === "dates" &&
                            produitValeur !== null
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
                                critereForm.value.criteres[critereId] = [
                                    start,
                                    end,
                                ];
                            }
                        } else if (
                            officialCritere.type_champ_form === "mois" &&
                            produitValeur !== null
                        ) {
                            const [startMonth, endMonth] = produitValeur
                                .split(" à ")
                                .map((dateStr) => {
                                    const parsedDate = parse(
                                        dateStr,
                                        "MMMM yyyy",
                                        new Date(),
                                        { locale: fr }
                                    );
                                    return isValid(parsedDate)
                                        ? parsedDate
                                        : null;
                                });

                            if (startMonth && endMonth) {
                                const startMonthYear = {
                                    month: startMonth.getMonth(),
                                    year: startMonth.getFullYear(),
                                };
                                const endMonthYear = {
                                    month: endMonth.getMonth(),
                                    year: endMonth.getFullYear(),
                                };

                                critereForm.value.criteres[critereId] = [
                                    startMonthYear,
                                    endMonthYear,
                                ];
                            }
                        } else if (
                            officialCritere.type_champ_form === "times" &&
                            produitValeur !== null
                        ) {
                            const [openTime, closeTime] = produitValeur
                                .split(" à ")
                                .map((timeString) => {
                                    const [hours, minutes] = timeString
                                        .split("h")
                                        .map(Number);
                                    return { hours, minutes };
                                });

                            critereForm.value.criteres[critereId] = [
                                openTime,
                                closeTime,
                            ];
                        } else if (
                            officialCritere.valeurs.length > 0 &&
                            critereValueId
                        ) {
                            officialCritere.valeurs.forEach(
                                (officialCritereValeur) => {
                                    if (
                                        officialCritereValeur.id ===
                                        critereValueId
                                    ) {
                                        if (
                                            !critereForm.value.criteres[
                                                critereId
                                            ]
                                        ) {
                                            if (
                                                officialCritere.type_champ_form ===
                                                "checkbox"
                                            ) {
                                                critereForm.value.criteres[
                                                    critereId
                                                ] = [officialCritereValeur];
                                            } else {
                                                critereForm.value.criteres[
                                                    critereId
                                                ] = officialCritereValeur;
                                            }
                                        } else {
                                            const existingValue =
                                                critereForm.value.criteres[
                                                    critereId
                                                ];

                                            if (!Array.isArray(existingValue)) {
                                                critereForm.value.criteres[
                                                    critereId
                                                ] = [existingValue];
                                                if (
                                                    !critereForm.value.criteres[
                                                        critereId
                                                    ].includes(
                                                        officialCritereValeur
                                                    )
                                                ) {
                                                    critereForm.value.criteres[
                                                        critereId
                                                    ].push(
                                                        officialCritereValeur
                                                    );
                                                }
                                            } else {
                                                if (
                                                    !critereForm.value.criteres[
                                                        critereId
                                                    ].includes(
                                                        officialCritereValeur
                                                    )
                                                ) {
                                                    critereForm.value.criteres[
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
                                                    if (
                                                        officialSousCritere
                                                            .sous_criteres_valeurs
                                                            .length > 0
                                                    ) {
                                                        officialSousCritere.sous_criteres_valeurs.forEach(
                                                            (
                                                                officialSousCritereValeur
                                                            ) => {
                                                                const officialSousCritereValeurId =
                                                                    officialSousCritereValeur.id;
                                                                sousCriteres.forEach(
                                                                    (
                                                                        sousCritere
                                                                    ) => {
                                                                        const prodSousCritValeur =
                                                                            sousCritere.sous_critere_valeur;

                                                                        if (
                                                                            prodSousCritValeur &&
                                                                            prodSousCritValeur.id ===
                                                                                officialSousCritereValeurId
                                                                        ) {
                                                                            critereForm.value.souscriteres[
                                                                                souscritereId
                                                                            ] =
                                                                                officialSousCritereValeur;
                                                                        }
                                                                    }
                                                                );
                                                            }
                                                        );
                                                    } else {
                                                        sousCriteres.forEach(
                                                            (sousCritere) => {
                                                                const prodSousCritValeur =
                                                                    sousCritere.valeur;
                                                                if (
                                                                    prodSousCritValeur !==
                                                                    null
                                                                ) {
                                                                    critereForm.value.souscriteres[
                                                                        souscritereId
                                                                    ] =
                                                                        prodSousCritValeur;
                                                                }
                                                            }
                                                        );
                                                    }
                                                }
                                            );
                                        }
                                    }
                                }
                            );
                        } else if (produitValeur !== null) {
                            critereForm.value.criteres[critereId] =
                                produitValeur;
                        }
                    }
                });
            });
        }
    }
);

const resetFormCriteres = () => {
    critereForm.value.criteres = {};
    critereForm.value.souscriteres = {};
    selectedCriteres.value = [];
    filterProducts();
};

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
    selectedProduct.value = props.selectedProduit ?? null;
    filterProducts();
});
</script>

<template>
    <Head
        :title="activite.titre + ' - ' + activite.structure.name"
        :description="
            'Fiche détaillée de ' + activite.titre + '. Horaires et tarifs.'
        "
    />

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
                class="sticky left-0 right-0 top-16 z-[1199] bg-transparent backdrop-blur-md"
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
                    <div class="basis-full space-y-12 md:basis-3/4">
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
                                class="flex w-full justify-center md:w-auto"
                                type="button"
                                @click="resetFormCriteres"
                            >
                                <ArrowPathIcon
                                    class="h-6 w-6 text-gray-500 transition duration-200 hover:-rotate-90 hover:text-gray-700 md:h-8 md:w-8"
                                />
                            </button>
                        </div>

                        <div
                            v-if="criteres.length > 0"
                            class="mx-auto grid w-full grid-cols-1 gap-4 bg-gray-50 p-2 shadow md:grid-cols-3"
                        >
                            <div
                                v-for="critere in criteres"
                                :key="critere.id"
                                class="col-span-1"
                            >
                                <!-- select -->
                                <SelectForm
                                    :classes="'block'"
                                    class="max-w-sm"
                                    v-if="critere.type_champ_form === 'select'"
                                    :name="critere.nom"
                                    v-model="critereForm.criteres[critere.id]"
                                    :options="critere.valeurs"
                                />

                                <!-- checkbox -->
                                <CheckboxForm
                                    class="max-w-sm"
                                    v-if="
                                        critere.type_champ_form === 'checkbox'
                                    "
                                    :critere="critere"
                                    :name="critere.nom"
                                    v-model="critereForm.criteres[critere.id]"
                                    :options="critere.valeurs"
                                    :is-checkbox-selected="isCheckboxSelected"
                                    @update-selected-checkboxes="
                                        updateSelectedCheckboxes
                                    "
                                />

                                <!-- radio -->
                                <RadioForm
                                    class="max-w-sm"
                                    v-if="critere.type_champ_form === 'radio'"
                                    :name="critere.nom"
                                    v-model="critereForm.criteres[critere.id]"
                                    :options="critere.valeurs"
                                />

                                <!-- input text  -->
                                <div
                                    class="max-w-sm"
                                    v-if="critere.type_champ_form === 'text'"
                                >
                                    <label
                                        :for="critere.nom"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ critere.nom }}
                                    </label>
                                    <div class="mt-1 flex rounded-md">
                                        <TextInput
                                            type="text"
                                            v-model="
                                                critereForm.criteres[critere.id]
                                            "
                                            :name="critere.nom"
                                            :id="critere.nom"
                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                            placeholder=""
                                            autocomplete="none"
                                        />
                                    </div>
                                </div>

                                <!-- number text -->
                                <div
                                    class="max-w-sm"
                                    v-if="critere.type_champ_form === 'number'"
                                >
                                    <label
                                        :for="critere.nom"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ critere.nom }}
                                    </label>
                                    <div class="mt-1 flex rounded-md">
                                        <TextInput
                                            type="number"
                                            min="0"
                                            v-model="
                                                critereForm.criteres[critere.id]
                                            "
                                            :name="critere.nom"
                                            :id="critere.nom"
                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                            placeholder=""
                                            autocomplete="none"
                                        />
                                    </div>
                                </div>

                                <!-- Heure seule -->
                                <div
                                    v-if="critere.type_champ_form === 'time'"
                                    class="flex max-w-sm flex-col items-start space-y-3"
                                >
                                    <SingleTimeForm
                                        class="w-full"
                                        v-model="
                                            critereForm.criteres[critere.id]
                                        "
                                        :name="critere.nom"
                                    />
                                </div>

                                <!-- Heures x2 ouverture / fermeture -->
                                <div
                                    v-if="critere.type_champ_form === 'times'"
                                    class="flex max-w-sm flex-col items-start space-y-3"
                                >
                                    <OpenTimesForm
                                        class="w-full"
                                        v-model="
                                            critereForm.criteres[critere.id]
                                        "
                                        :name="critere.nom"
                                    />
                                </div>

                                <!-- Date seule -->
                                <div
                                    v-if="critere.type_champ_form === 'date'"
                                    class="flex max-w-sm flex-col items-start space-y-3"
                                >
                                    <SingleDateForm
                                        class="w-full"
                                        v-model="
                                            critereForm.criteres[critere.id]
                                        "
                                        :name="critere.nom"
                                    />
                                </div>

                                <!-- Dates x 2 -->
                                <div
                                    v-if="critere.type_champ_form === 'dates'"
                                    class="flex max-w-sm flex-col items-start space-y-3"
                                >
                                    <OpenDaysForm
                                        class="w-full"
                                        v-model="
                                            critereForm.criteres[critere.id]
                                        "
                                        :name="critere.nom"
                                    />
                                </div>

                                <!-- Mois -->
                                <div v-if="critere.type_champ_form === 'mois'">
                                    <div
                                        class="flex max-w-sm flex-col items-start space-y-3"
                                    >
                                        <OpenMonthsForm
                                            class="w-full"
                                            v-model="
                                                critereForm.criteres[critere.id]
                                            "
                                            :name="critere.nom"
                                        />
                                    </div>
                                </div>

                                <!-- Range km  -->
                                <div
                                    v-if="critere.type_champ_form === 'rayon'"
                                    class="flex w-full max-w-sm flex-col items-start space-y-3"
                                >
                                    <RangeInputForm
                                        class="w-full max-w-sm"
                                        v-model="
                                            critereForm.criteres[critere.id]
                                        "
                                        :name="critere.nom"
                                        :metric="`Km`"
                                    />
                                </div>
                                <!-- sous criteres -->
                                <div
                                    v-for="valeur in critere.valeurs"
                                    :key="valeur.id"
                                >
                                    <div
                                        v-for="souscritere in valeur.sous_criteres"
                                        :key="souscritere.id"
                                    >
                                        <!-- sous crit select -->
                                        <SelectForm
                                            :classes="'block'"
                                            class="max-w-sm py-2"
                                            v-if="
                                                critereForm.criteres[
                                                    critere.id
                                                ] === valeur &&
                                                souscritere.type_champ_form ===
                                                    'select' &&
                                                souscritere.dis_cat_crit_val_id ===
                                                    valeur.id
                                            "
                                            :name="souscritere.nom"
                                            v-model="
                                                critereForm.souscriteres[
                                                    souscritere.id
                                                ]
                                            "
                                            :options="
                                                souscritere.sous_criteres_valeurs
                                            "
                                        />
                                        <!-- sous crit number -->
                                        <InputLabel
                                            class="py-2"
                                            :for="souscritere.nom"
                                            :value="souscritere.nom"
                                            v-if="
                                                critereForm.criteres[
                                                    critere.id
                                                ] === valeur &&
                                                souscritere.type_champ_form ===
                                                    'number' &&
                                                souscritere.dis_cat_crit_val_id ===
                                                    valeur.id
                                            "
                                        />
                                        <TextInput
                                            class="w-full max-w-sm"
                                            type="number"
                                            min="0"
                                            :id="souscritere.nom"
                                            :name="souscritere.nom"
                                            v-if="
                                                critereForm.criteres[
                                                    critere.id
                                                ] === valeur &&
                                                souscritere.type_champ_form ===
                                                    'number' &&
                                                souscritere.dis_cat_crit_val_id ===
                                                    valeur.id
                                            "
                                            v-model="
                                                critereForm.souscriteres[
                                                    souscritere.id
                                                ]
                                            "
                                        />
                                        <!-- sous crit text -->
                                        <InputLabel
                                            class="py-2"
                                            :for="souscritere.nom"
                                            :value="souscritere.nom"
                                            v-if="
                                                critereForm.criteres[
                                                    critere.id
                                                ] === valeur &&
                                                souscritere.type_champ_form ===
                                                    'text' &&
                                                souscritere.dis_cat_crit_val_id ===
                                                    valeur.id
                                            "
                                        />
                                        <TextInput
                                            class="w-full max-w-sm"
                                            type="text"
                                            :id="souscritere.nom"
                                            :name="souscritere.nom"
                                            v-if="
                                                critereForm.criteres[
                                                    critere.id
                                                ] === valeur &&
                                                souscritere.type_champ_form ===
                                                    'text' &&
                                                souscritere.dis_cat_crit_val_id ===
                                                    valeur.id
                                            "
                                            v-model="
                                                critereForm.souscriteres[
                                                    souscritere.id
                                                ]
                                            "
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
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
                        </div>

                        <div
                            class="flex w-full items-center justify-center md:justify-end"
                        >
                            <button
                                v-if="
                                    reservationForm.produit &&
                                    reservationForm.formule
                                "
                                @click.prevent="openReservationModal"
                                type="button"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm text-white duration-300 hover:-translate-y-1 hover:bg-blue-700 disabled:pointer-events-none disabled:opacity-50 md:w-auto"
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
                :produits="produits"
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
