<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted } from "vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import ActiviteCard from "@/Components/Structures/ActiviteCard.vue";
import ProduitFormuleCard from "@/Components/Produits/ProduitFormuleCard.vue";
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
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import autoAnimate from "@formkit/auto-animate";
import dayjs from "dayjs";
import "dayjs/locale/fr";
import { HomeIcon, ArrowPathIcon } from "@heroicons/vue/24/outline";
import { StarIcon } from "@heroicons/vue/24/solid";
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
});

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
                selectedCriteres.value.every((selectedCritere) => {
                    console.log(selectedCritere, produit.criteres);
                    if (!!selectedCritere.inclus_all === true) {
                        return true;
                    } else if (Array.isArray(selectedCritere)) {
                        return selectedCritere.some((critereInArray) => {
                            return produit.criteres.some(
                                (produitCritere) =>
                                    produitCritere.valeur_id ===
                                    critereInArray.id
                            );
                        });
                    } else {
                        return produit.criteres.some((produitCritere) => {
                            // Check if 'valeur_id' et 'critere_valeur' exists in produitCritere
                            const valeurIdExists =
                                produitCritere.hasOwnProperty("valeur_id");

                            return (
                                (valeurIdExists &&
                                    produitCritere.valeur_id ===
                                        selectedCritere.id) ||
                                (!!produitCritere.critere_valeur &&
                                    !!produitCritere.critere_valeur
                                        .inclus_all === true)
                            );
                        });
                    }
                }) &&
                selectedSousCriteres.value.every((selectedSousCritere) => {
                    return produit.criteres.some((produitCritere) => {
                        return (
                            produitCritere.sous_criteres &&
                            produitCritere.sous_criteres.some(
                                (sousCritere) =>
                                    sousCritere.sous_critere_valeur_id ===
                                    selectedSousCritere.id
                            )
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
        selectedCriteres.value = Object.values(newCriteres).filter(Boolean);
        filterProducts();
    },
    { deep: true }
);

watch(
    () => critereForm.value.souscriteres,
    (newSousCriteres) => {
        selectedSousCriteres.value =
            Object.values(newSousCriteres).filter(Boolean);
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
    produit: null,
    formule: null,
    planning: null,
    remember: false,
});

const submitReservation = () => {
    reservationForm.post("/product_reservations", {
        preserveScroll: true,
        onSuccess: () => {
            reservationForm.reset();
            displayPlanning.value = false;
        },
    });
};
const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

onMounted(() => {
    autoAnimate(listToAnimate.value);
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

                            <li v-if="city" class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="route('villes.show', city.slug)"
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ formatCityName(city.ville) }}
                                </Link>
                            </li>

                            <li
                                v-if="departement"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route(
                                            'departements.show',
                                            departement.slug
                                        )
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ departement.departement }}
                                </Link>
                            </li>

                            <li
                                v-if="discipline"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route(
                                            'disciplines.show',
                                            discipline.slug
                                        )
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ discipline.name }}
                                </Link>
                            </li>

                            <li
                                v-if="requestCategory"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('disciplines.categories.show', {
                                            discipline: discipline.slug,
                                            category: requestCategory.slug,
                                        })
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ requestCategory.nom_categorie_client }}
                                </Link>
                            </li>

                            <li
                                v-if="structuretypeElected"
                                class="relative flex items-center"
                            >
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route(
                                            'disciplines.structuretypes.show',
                                            {
                                                discipline: discipline.slug,
                                                structuretype:
                                                    structuretypeElected.id,
                                            }
                                        )
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ structuretypeElected.name }}
                                </Link>
                            </li>

                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('structures.activites.show', {
                                            activite: activite,
                                        })
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ activite.titre }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
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

            <section class="mx-auto my-4 max-w-full px-0 py-6 sm:px-4 lg:px-8">
                <div
                    class="flex flex-col justify-between rounded-lg bg-white px-4 py-6 text-slate-600 shadow md:flex-row md:items-start md:space-x-6"
                >
                    <div class="w-full">
                        <div class="relative space-y-12">
                            <!-- titre -->
                            <div
                                class="my-4 flex items-center justify-start space-x-4"
                            >
                                <h1
                                    class="inline-block w-full text-center text-xl font-semibold sm:text-2xl sm:leading-7 md:text-3xl"
                                >
                                    Page en refonte:
                                    {{ activite.titre }}
                                </h1>
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
                            <div
                                class="flex w-full items-center justify-between"
                            >
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
                                        v-if="
                                            critere.type_champ_form === 'select'
                                        "
                                        :name="critere.nom"
                                        v-model="
                                            critereForm.criteres[critere.id]
                                        "
                                        :options="critere.valeurs"
                                    />

                                    <!-- checkbox -->
                                    <CheckboxForm
                                        class="max-w-sm"
                                        v-if="
                                            critere.type_champ_form ===
                                            'checkbox'
                                        "
                                        :critere="critere"
                                        :name="critere.nom"
                                        v-model="
                                            critereForm.criteres[critere.id]
                                        "
                                        :options="critere.valeurs"
                                        :is-checkbox-selected="
                                            isCheckboxSelected
                                        "
                                        @update-selected-checkboxes="
                                            updateSelectedCheckboxes
                                        "
                                    />

                                    <!-- radio -->
                                    <RadioForm
                                        class="max-w-sm"
                                        v-if="
                                            critere.type_champ_form === 'radio'
                                        "
                                        :name="critere.nom"
                                        v-model="
                                            critereForm.criteres[critere.id]
                                        "
                                        :options="critere.valeurs"
                                    />

                                    <!-- input text  -->
                                    <div
                                        class="max-w-sm"
                                        v-if="
                                            critere.type_champ_form === 'text'
                                        "
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
                                                    critereForm.criteres[
                                                        critere.id
                                                    ]
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
                                        v-if="
                                            critere.type_champ_form === 'number'
                                        "
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
                                                v-model="
                                                    critereForm.criteres[
                                                        critere.id
                                                    ]
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
                                        v-if="
                                            critere.type_champ_form === 'time'
                                        "
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
                                        v-if="
                                            critere.type_champ_form === 'times'
                                        "
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
                                        v-if="
                                            critere.type_champ_form === 'date'
                                        "
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
                                        v-if="
                                            critere.type_champ_form === 'dates'
                                        "
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
                                    <div
                                        v-if="
                                            critere.type_champ_form === 'mois'
                                        "
                                    >
                                        <div
                                            class="flex max-w-sm flex-col items-start space-y-3"
                                        >
                                            <OpenMonthsForm
                                                class="w-full"
                                                v-model="
                                                    critereForm.criteres[
                                                        critere.id
                                                    ]
                                                "
                                                :name="critere.nom"
                                            />
                                        </div>
                                    </div>

                                    <!-- Range km  -->
                                    <div
                                        v-if="
                                            critere.type_champ_form === 'rayon'
                                        "
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
                                                class="w-full"
                                                type="number"
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
                                                class="w-full"
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
                                class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-2"
                            >
                                <ProduitFormuleCard
                                    v-for="produit in filteredProduits"
                                    :key="produit.id"
                                    :produit="produit"
                                    :discipline="produit.discipline"
                                />
                            </div>
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
                        class="mt-12 grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-8"
                    >
                        <blockquote class="rounded-lg bg-gray-100 p-8">
                            <div class="flex items-center gap-4">
                                <img
                                    alt="Man"
                                    src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
                                    class="h-16 w-16 rounded-full object-cover"
                                />

                                <div>
                                    <div
                                        class="flex justify-center gap-0.5 text-yellow-500"
                                    >
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                    </div>

                                    <p
                                        class="mt-1 text-lg font-medium text-gray-700"
                                    >
                                        Paul Jacquemimou
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-4 line-clamp-2 text-gray-500 sm:line-clamp-none"
                            >
                                Très mauvaise expérience! A fuir!
                            </p>
                        </blockquote>

                        <blockquote class="rounded-lg bg-gray-100 p-8">
                            <div class="flex items-center gap-4">
                                <img
                                    alt="Man"
                                    src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
                                    class="h-16 w-16 rounded-full object-cover"
                                />

                                <div>
                                    <div
                                        class="flex justify-center gap-0.5 text-yellow-500"
                                    >
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                    </div>

                                    <p
                                        class="mt-1 text-lg font-medium text-gray-700"
                                    >
                                        Jacques Miteux
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-4 line-clamp-2 text-gray-500 sm:line-clamp-none"
                            >
                                C'était à chier, mais je mets 5 étoiles pour le
                                sourire de Roberta.
                            </p>
                        </blockquote>

                        <blockquote class="rounded-lg bg-gray-100 p-8">
                            <div class="flex items-center gap-4">
                                <img
                                    alt="Man"
                                    src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1180&q=80"
                                    class="h-16 w-16 rounded-full object-cover"
                                />

                                <div>
                                    <div
                                        class="flex justify-center gap-0.5 text-yellow-500"
                                    >
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4" />
                                        <StarIcon class="h-4 w-4 text-white" />
                                    </div>

                                    <p
                                        class="mt-1 text-lg font-medium text-gray-700"
                                    >
                                        Antoine Boss
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-4 line-clamp-2 text-gray-500 sm:line-clamp-none"
                            >
                                C'était vraiment sensationnel.
                            </p>
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
                                })
                            "
                        />
                    </div>
                </div>
            </section>
        </template>
    </ResultLayout>
</template>

<style>
.course {
    @apply bg-blue-400 text-white;
}
</style>
