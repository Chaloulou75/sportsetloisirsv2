<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    ref,
    computed,
    defineAsyncComponent,
    provide,
    watch,
    onMounted,
} from "vue";
import { debounce } from "lodash";
import CheckboxForm from "@/Components/Forms/CheckboxForm.vue";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import RangeInputForm from "@/Components/Forms/RangeInputForm.vue";
import OpenDaysForm from "@/Components/Forms/DayTime/OpenDaysForm.vue";
import SingleDateForm from "@/Components/Forms/DayTime/SingleDateForm.vue";
import SingleTimeForm from "@/Components/Forms/DayTime/SingleTimeForm.vue";
import OpenTimesForm from "@/Components/Forms/DayTime/OpenTimesForm.vue";
import OpenMonthsForm from "@/Components/Forms/DayTime/OpenMonthsForm.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import CategoriesResultNavigation from "@/Components/Categories/CategoriesResultNavigation.vue";
import autoAnimate from "@formkit/auto-animate";
import { TransitionRoot } from "@headlessui/vue";
import {
    AdjustmentsHorizontalIcon,
    ArrowPathIcon,
    HomeIcon,
    ListBulletIcon,
    MapIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { useElementVisibility } from "@vueuse/core";
import dayjs from "dayjs";
import "dayjs/locale/fr";
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
    familles: Object,
    category: Object,
    categories: Object,
    firstCategories: Object,
    categoriesNotInFirst: Object,
    allStructureTypes: Object,
    criteres: Object,
    discipline: Object,
    listDisciplines: Object,
    allCities: Object,
    produits: Object,
    structures: Object,
    posts: Object,
});

const ProduitCard = defineAsyncComponent(() =>
    import("@/Components/Produits/ProduitCard.vue")
);

const StructureCard = defineAsyncComponent(() =>
    import("@/Components/Structures/StructureCard.vue")
);

const LeafletMapProduitMultiple = defineAsyncComponent(() =>
    import("@/Components/Maps/LeafletMapProduitMultiple.vue")
);

const PostFeaturedCard = defineAsyncComponent(() =>
    import("@/Components/Posts/PostFeaturedCard.vue")
);

const DisciplinesSimilaires = defineAsyncComponent(() =>
    import("@/Components/Disciplines/DisciplinesSimilaires.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/Components/Pagination.vue")
);

const listToAnimate = ref();

const mapStructure = ref(null);
const mapIsVisible = useElementVisibility(mapStructure);
const listeStructure = ref(null);
const listeIsVisible = useElementVisibility(listeStructure);

const displayProduits = ref(true);
const displayMap = ref(false);

const goToMap = () => {
    displayProduits.value = !displayProduits.value;
    displayMap.value = !displayMap.value;
    // mapStructure.value.scrollIntoView({ behavior: "smooth" });
};

const goToListe = () => {
    displayProduits.value = !displayProduits.value;
    displayMap.value = !displayMap.value;
    // listeStructure.value.scrollIntoView({ behavior: "smooth" });
};

const criteresEl = ref(null);
const isCriteresVisible = useElementVisibility(criteresEl);
const scrollToCriteres = () => {
    if (criteresEl.value) {
        const offset = window.innerWidth >= 768 ? -125 : -135;
        const scrollY =
            window.scrollY +
            criteresEl.value.getBoundingClientRect().top +
            offset;
        window.scroll({
            top: scrollY,
            behavior: "smooth",
        });
    }
};

provide("scrollToCriteres", scrollToCriteres);

const formatCityName = (ville) => {
    return ville.charAt(0).toUpperCase() + ville.slice(1).toLowerCase();
};

const hoveredProduit = ref(null);
const hoveredStructure = ref(null);

const showTooltip = (produit) => {
    hoveredProduit.value = produit.id;
};
const hideTooltip = () => {
    hoveredProduit.value = null;
};

const showStructureTooltip = (structure) => {
    hoveredStructure.value = structure.id;
};

const hideStructureTooltip = () => {
    hoveredStructure.value = null;
};

const showCriteres = ref(false);

const filteredCriteresByChamp = computed(() => {
    return props.criteres.filter((critere) => {
        return [
            "select",
            "checkbox",
            "radio",
            "text",
            "number",
            "rayon",
            "range",
        ].includes(critere.type_champ_form);
    });
});

const toggleCriteres = () => {
    showCriteres.value = !showCriteres.value;
};

const showCriteresLg = ref(true);

const toggleCriteresLg = () => {
    showCriteresLg.value = !showCriteresLg.value;
};

const formCriteres = ref({
    criteres: {},
    sousCriteres: {},
});
const selectedCriteres = ref([]);
const selectedSousCriteres = ref([]);
const filteredProduits = ref(props.produits.data);
const filteredStructures = ref(props.structures.data);

const onFilteredProduitsUpdate = (filtered) => {
    filteredProduits.value = filtered;
};

const onfilteredStructuresUpdate = (filteredStr) => {
    filteredStructures.value = filteredStr;
};

const updateSelectedCheckboxes = (critereId, optionValue, checked) => {
    if (checked) {
        if (!formCriteres.value.criteres[critereId]) {
            formCriteres.value.criteres[critereId] = [optionValue];
        } else {
            formCriteres.value.criteres[critereId].push(optionValue);
        }
    } else {
        const selectedCritere = formCriteres.value.criteres[critereId];
        if (selectedCritere) {
            const index = selectedCritere.indexOf(optionValue);
            if (index !== -1) {
                selectedCritere.splice(index, 1);
            }
            if (selectedCritere.length === 0) {
                delete formCriteres.value.criteres[critereId];
            }
        }
    }
};

const isCheckboxSelected = computed(() => {
    return (critereId, optionValue) => {
        return (
            formCriteres.value.criteres[critereId] &&
            formCriteres.value.criteres[critereId].includes(optionValue)
        );
    };
});

const filterProducts = () => {
    if (selectedCriteres.value.length === 0) {
        filteredProduits.value = props.produits.data;
    } else {
        filteredProduits.value = props.produits.data.filter((produit) => {
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
                                    produitCritere.critere.type_champ_form ===
                                        "range" ||
                                    produitCritere.critere.type_champ_form ===
                                        "rayon"
                                ) {
                                    const minValue = 0;
                                    const maxValue = produitCritere.valeur;

                                    if (!isNaN(minValue) && !isNaN(maxValue)) {
                                        return (
                                            selectedCritere <= maxValue &&
                                            selectedCritere >= minValue
                                        );
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
                                // console.log(sousCritere, selectedSousCritere);
                                if (
                                    sousCritere.sous_critere.type_champ_form ===
                                        "range" &&
                                    selectedSousCritere !== null &&
                                    numericSousCritereId ===
                                        sousCritere.sous_critere_id
                                ) {
                                    const minValue = 0;
                                    const maxValue = sousCritere.valeur;
                                    if (!isNaN(minValue) && !isNaN(maxValue)) {
                                        return (
                                            selectedSousCritere <= maxValue &&
                                            selectedSousCritere >= minValue
                                        );
                                    } else {
                                        return false;
                                    }
                                } else if (
                                    sousCritere.sous_critere.type_champ_form ===
                                        "number" &&
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
                                    sousCritere.sous_critere.type_champ_form ===
                                        "string" &&
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

const debouncedFilterProducts = debounce(filterProducts, 300);

watch(
    () => formCriteres.value.criteres,
    (newCriteres) => {
        selectedCriteres.value = Object.entries(newCriteres);
        debouncedFilterProducts();
    },
    { deep: true }
);

watch(
    () => formCriteres.value.sousCriteres,
    (newSousCriteres) => {
        selectedSousCriteres.value = Object.entries(newSousCriteres);
        debouncedFilterProducts();
    },
    { deep: true }
);

const resetFormCriteres = () => {
    formCriteres.value.criteres = {};
    formCriteres.value.sousCriteres = {};
    selectedCriteres.value = [];
    debouncedFilterProducts();
};

onMounted(() => {
    if (listToAnimate.value) {
        autoAnimate(listToAnimate.value);
    }
    debouncedFilterProducts();
});
</script>

<template>
    <Head
        :title="`${category.nom_categorie_client} de ${discipline.name}`"
        :description="`${category.nom_categorie_client} de ${discipline.name}.`"
    />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
        :discipline="discipline"
        :current-category="category"
        :is-criteres-visible="isCriteresVisible"
    >
        <template #header>
            <ResultsHeader :discipline="discipline">
                <template v-slot:title>
                    {{ discipline.name }}
                </template>
                <template v-slot:subtitle>
                    {{ category.nom_categorie_client }}
                </template>
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
                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="
                                        route('disciplines.categories.show', {
                                            discipline: discipline.slug,
                                            category: category.slug,
                                        })
                                    "
                                    class="flex h-10 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    {{ category.nom_categorie_client }}
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>
        <template #default>
            <div
                ref="criteresEl"
                class="sticky left-0 right-0 top-16 z-[1199] bg-transparent backdrop-blur-md"
            >
                <CategoriesResultNavigation
                    :category="category"
                    :discipline="discipline"
                    :all-structure-types="allStructureTypes"
                    :categories="categories"
                    :first-categories="firstCategories"
                    :categories-not-in-first="categoriesNotInFirst"
                    :show-criteres="showCriteresLg"
                    @call-toggle-criteres="toggleCriteresLg"
                />
                <!-- Criteres -->
                <div
                    class="flex w-full items-center justify-between border-b border-gray-300 px-2 py-3 md:hidden"
                >
                    <h3 class="font-semibold">
                        {{ category.nom_categorie_client }}
                    </h3>
                    <button type="button" @click="toggleCriteres">
                        <XMarkIcon v-if="showCriteres" class="h-6 w-6" />
                        <AdjustmentsHorizontalIcon v-else class="h-6 w-6" />
                    </button>
                </div>

                <div
                    v-if="criteres"
                    class="mx-auto w-full flex-col items-start justify-center gap-4 overflow-x-auto rounded bg-transparent px-2 py-2 backdrop-blur-md md:flex-row md:items-center md:justify-between md:space-y-0 md:px-6"
                    :class="{
                        flex: showCriteres,
                        hidden: !showCriteres,
                        'md:flex': showCriteresLg,
                        'md:hidden': !showCriteresLg,
                    }"
                >
                    <div
                        v-for="critere in filteredCriteresByChamp"
                        :key="critere.id"
                        class="w-full max-w-sm shrink-0 md:w-auto"
                    >
                        <!-- select -->
                        <SelectForm
                            :classes="'flex items-center space-x-2'"
                            class="max-w-sm"
                            v-if="critere.type_champ_form === 'select'"
                            :name="critere.nom"
                            v-model="formCriteres.criteres[critere.id]"
                            :options="critere.valeurs"
                        />

                        <!-- checkbox -->
                        <CheckboxForm
                            :classes="'flex items-center space-x-2'"
                            class="max-w-sm"
                            v-if="critere.type_champ_form === 'checkbox'"
                            :critere="critere"
                            :name="critere.nom"
                            v-model="formCriteres.criteres[critere.id]"
                            :options="critere.valeurs"
                            :is-checkbox-selected="isCheckboxSelected"
                            @update-selected-checkboxes="
                                updateSelectedCheckboxes
                            "
                        />
                        <!-- radio -->
                        <div v-if="critere.type_champ_form === 'radio'">
                            <div class="flex items-center space-x-2">
                                <label
                                    :for="critere.nom"
                                    class="block text-sm font-medium normal-case text-gray-700"
                                >
                                    {{ critere.nom }}
                                </label>

                                <div class="flex rounded-md">
                                    <div>
                                        <label
                                            class="inline-flex items-center"
                                            v-for="(
                                                option, index
                                            ) in critere.valeurs"
                                            :key="option.id"
                                        >
                                            <input
                                                v-model="
                                                    formCriteres.criteres[
                                                        critere.id
                                                    ]
                                                "
                                                type="radio"
                                                class="form-radio"
                                                :name="option.valeur"
                                                :value="option.valeur"
                                                checked
                                            />
                                            <span class="ml-2">{{
                                                option.valeur
                                            }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- input text -->
                        <div
                            v-if="critere.type_champ_form === 'text'"
                            class="flex items-center space-x-2"
                        >
                            <label
                                :for="critere.nom"
                                class="block text-sm font-medium normal-case text-gray-700"
                            >
                                {{ critere.nom }}
                            </label>
                            <div class="flex-1">
                                <TextInput
                                    type="text"
                                    v-model="formCriteres.criteres[critere.id]"
                                    :name="critere.nom"
                                    :id="critere.nom"
                                />
                            </div>
                        </div>
                        <!-- input Number -->
                        <div
                            v-if="critere.type_champ_form === 'number'"
                            class="flex items-center space-x-2"
                        >
                            <label
                                :for="critere.nom"
                                class="block text-sm font-medium normal-case text-gray-700"
                            >
                                {{ critere.nom }}
                            </label>
                            <div class="flex-1">
                                <TextInput
                                    type="number"
                                    min="0"
                                    v-model="formCriteres.criteres[critere.id]"
                                    :name="critere.nom"
                                    :id="critere.nom"
                                />
                            </div>
                        </div>
                        <!-- Range km  -->
                        <RangeInputForm
                            v-if="critere.type_champ_form === 'rayon'"
                            class="w-full max-w-sm"
                            v-model="formCriteres.criteres[critere.id]"
                            :name="critere.nom"
                            :metric="critere.nom"
                        />

                        <RangeInputForm
                            v-if="critere.type_champ_form === 'range'"
                            class="w-full max-w-sm"
                            v-model="formCriteres.criteres[critere.id]"
                            :name="critere.nom"
                            :metric="critere.nom"
                        />

                        <!-- Heure seule -->
                        <div
                            v-if="critere.type_champ_form === 'time'"
                            class="flex max-w-sm flex-col items-start"
                        >
                            <SingleTimeForm
                                class="w-full"
                                v-model="formCriteres.criteres[critere.id]"
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
                                v-model="formCriteres.criteres[critere.id]"
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
                                v-model="formCriteres.criteres[critere.id]"
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
                                v-model="formCriteres.criteres[critere.id]"
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
                                    v-model="formCriteres.criteres[critere.id]"
                                    :name="critere.nom"
                                />
                            </div>
                        </div>

                        <!-- sous criteres -->
                        <div v-for="valeur in critere.valeurs" :key="valeur.id">
                            <div
                                v-for="souscritere in valeur.sous_criteres"
                                :key="souscritere.id"
                                class=""
                            >
                                <!-- select -->
                                <SelectForm
                                    :classes="'flex items-center space-x-4'"
                                    class="max-w-sm py-2"
                                    v-if="
                                        formCriteres.criteres[critere.id] ===
                                            valeur &&
                                        souscritere.type_champ_form ===
                                            'select' &&
                                        souscritere.dis_cat_crit_val_id ===
                                            valeur.id
                                    "
                                    :name="souscritere.nom"
                                    v-model="
                                        formCriteres.sousCriteres[
                                            souscritere.id
                                        ]
                                    "
                                    :options="souscritere.sous_criteres_valeurs"
                                />
                                <!-- number -->
                                <div
                                    v-if="
                                        formCriteres.criteres[critere.id] ===
                                            valeur &&
                                        souscritere.type_champ_form ===
                                            'number' &&
                                        souscritere.dis_cat_crit_val_id ===
                                            valeur.id
                                    "
                                    class="mt-2 flex items-center space-x-4"
                                >
                                    <InputLabel
                                        class="py-2"
                                        :for="souscritere.nom"
                                        :value="souscritere.nom"
                                    />
                                    <TextInput
                                        class="w-full"
                                        type="number"
                                        min="0"
                                        :id="souscritere.nom"
                                        :name="souscritere.nom"
                                        v-model="
                                            formCriteres.sousCriteres[
                                                souscritere.id
                                            ]
                                        "
                                    />
                                </div>
                                <!-- text -->
                                <div
                                    v-if="
                                        formCriteres.criteres[critere.id] ===
                                            valeur &&
                                        souscritere.type_champ_form ===
                                            'text' &&
                                        souscritere.dis_cat_crit_val_id ===
                                            valeur.id
                                    "
                                    class="mt-2 flex items-center space-x-4"
                                >
                                    <InputLabel
                                        class="py-2"
                                        :for="souscritere.nom"
                                        :value="souscritere.nom"
                                    />
                                    <TextInput
                                        class="w-full"
                                        type="text"
                                        :id="souscritere.nom"
                                        :name="souscritere.nom"
                                        v-model="
                                            formCriteres.sousCriteres[
                                                souscritere.id
                                            ]
                                        "
                                    />
                                </div>
                                <!-- range -->
                                <RangeInputForm
                                    v-if="
                                        formCriteres.criteres[critere.id] ===
                                            valeur &&
                                        souscritere.type_champ_form ===
                                            'range' &&
                                        souscritere.dis_cat_crit_val_id ===
                                            valeur.id
                                    "
                                    class="w-full max-w-sm"
                                    v-model="
                                        formCriteres.sousCriteres[
                                            souscritere.id
                                        ]
                                    "
                                    :name="souscritere.nom"
                                    :metric="souscritere.nom"
                                />
                            </div>
                        </div>
                    </div>
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
            </div>

            <template v-if="produits.data.length > 0">
                <div class="mx-auto py-6 md:py-12">
                    <TransitionRoot
                        as="div"
                        :show="displayProduits"
                        enter="transition-opacity duration-150"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="transition-opacity duration-150"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <h2
                            class="mb-4 w-full text-center text-lg font-semibold text-gray-600 md:mb-8 md:w-1/2 md:text-2xl"
                        >
                            Les activités
                        </h2>
                        <div
                            class="mx-auto flex w-full flex-col px-2 md:flex-row md:space-x-4"
                        >
                            <div
                                ref="listeStructure"
                                class="w-full px-2 md:w-1/2"
                            >
                                <div
                                    ref="listToAnimate"
                                    class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-2"
                                >
                                    <ProduitCard
                                        v-for="(
                                            produit, index
                                        ) in filteredProduits"
                                        :key="produit.id"
                                        :index="index"
                                        :produit="produit"
                                        :discipline="discipline"
                                        @card-hover="showTooltip(produit)"
                                        @card-out="hideTooltip"
                                        :link="
                                            route(
                                                'disciplines.categories.activites.show',
                                                {
                                                    discipline: discipline,
                                                    category: category.slug,
                                                    activite: produit.activite,
                                                    slug: produit.activite
                                                        .slug_title,
                                                    produit: produit.id,
                                                }
                                            )
                                        "
                                        :data="{}"
                                    />
                                </div>
                                <div v-if="filteredProduits.length === 0">
                                    <p
                                        class="text-lg font-medium text-gray-700"
                                    >
                                        Pas d'activité dans cette zone de la
                                        carte, ou avec ces critères
                                        <span class="text-base italic"
                                            >(Utilisez la carte ou les liens de
                                            pagination ci dessous pour trouver
                                            des activités dans votre
                                            région).</span
                                        >
                                    </p>
                                </div>
                                <div class="flex justify-end p-10">
                                    <Pagination
                                        :links="produits.links"
                                        :only="['produits']"
                                    />
                                </div>

                                <!-- les structures -->
                                <h2
                                    v-if="structures.data.length > 0"
                                    class="mb-4 text-center text-lg font-semibold text-gray-600 md:mb-8 md:text-2xl"
                                >
                                    Les structures
                                </h2>
                                <div
                                    class="grid h-auto grid-cols-1 place-content-stretch place-items-stretch gap-4 lg:grid-cols-2"
                                >
                                    <StructureCard
                                        v-for="(
                                            structure, index
                                        ) in filteredStructures"
                                        :key="structure.id"
                                        :index="index"
                                        :structure="structure"
                                        @card-hover="
                                            showStructureTooltip(structure)
                                        "
                                        @card-out="hideStructureTooltip"
                                        :link="
                                            route(
                                                'disciplines.categories.structures.show',
                                                {
                                                    discipline: discipline,
                                                    category: category.slug,
                                                    structure: structure,
                                                }
                                            )
                                        "
                                        :data="{}"
                                    />
                                </div>
                                <div v-if="filteredStructures.length === 0">
                                    <p
                                        class="text-lg font-medium text-gray-700"
                                    >
                                        Pas de structure dans cette zone de la
                                        carte
                                        <span class="text-base italic"
                                            >(Utilisez la carte ou les liens de
                                            pagination pour trouver des
                                            structures dans votre région).</span
                                        >
                                    </p>
                                </div>
                                <div class="flex justify-end p-10">
                                    <Pagination
                                        :links="structures.links"
                                        :only="['structures']"
                                    />
                                </div>

                                <button
                                    v-if="displayProduits"
                                    type="button"
                                    class="fixed inset-x-2 bottom-4 z-[999] mx-auto flex w-3/4 max-w-xs items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-xs text-white transition duration-75 hover:scale-105 hover:bg-gray-800 hover:font-semibold md:hidden md:w-auto md:text-sm"
                                    @click="goToMap"
                                >
                                    <MapIcon class="mr-2 h-5 w-5" />
                                    Carte
                                </button>
                            </div>
                            <LeafletMapProduitMultiple
                                class="sticky top-48 hidden md:block md:w-1/2"
                                :produits="produits.data"
                                :hovered-produit="hoveredProduit"
                                :structures="structures.data"
                                :hovered-structure="hoveredStructure"
                                v-model:filteredProduits="filteredProduits"
                                @update:filteredProduits="
                                    onFilteredProduitsUpdate
                                "
                                v-model:filteredStructures="filteredStructures"
                                @update:filteredStructures="
                                    onfilteredStructuresUpdate
                                "
                                :zoom="11"
                            />
                        </div>
                        <!-- Blog -->
                        <div
                            v-if="posts.length > 0"
                            class="my-8 px-3 md:my-16 md:px-6 lg:px-8"
                        >
                            <h2
                                class="my-4 text-center text-lg font-semibold text-gray-600 md:my-8 md:text-2xl"
                            >
                                Les derniers articles
                            </h2>
                            <div
                                v-if="posts.length > 0"
                                class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                            >
                                <PostFeaturedCard
                                    v-for="post in posts"
                                    :key="post.id"
                                    :post="post"
                                />
                            </div>
                        </div>
                        <!-- les disciplines similaires -->
                        <DisciplinesSimilaires
                            v-if="discipline.disciplines_similaires.length > 0"
                            :disciplines-similaires="
                                discipline.disciplines_similaires
                            "
                        />
                    </TransitionRoot>

                    <TransitionRoot
                        as="template"
                        :show="displayMap"
                        enter="transition-opacity duration-150"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="transition-opacity duration-150"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="mx-auto flex w-full flex-col space-y-4">
                            <div ref="mapStructure" class="w-full">
                                <LeafletMapProduitMultiple
                                    class="md:top-2"
                                    :produits="produits.data"
                                    :hovered-produit="hoveredProduit"
                                    :structures="structures.data"
                                    :hovered-structure="hoveredStructure"
                                    :zoom="12"
                                />
                                <button
                                    v-if="displayMap"
                                    type="button"
                                    class="fixed inset-x-2 bottom-4 z-[999] mx-auto flex w-3/4 max-w-xs items-center justify-center rounded-full bg-gray-900 px-4 py-3 text-xs text-white transition duration-75 hover:scale-105 hover:bg-gray-800 hover:font-semibold md:hidden md:w-auto md:text-sm"
                                    @click="goToListe"
                                >
                                    <ListBulletIcon class="mr-2 h-5 w-5" />
                                    Liste
                                </button>
                            </div>
                        </div>
                    </TransitionRoot>
                </div>
            </template>
            <template v-else>
                <div
                    class="mx-auto flex min-h-full max-w-full flex-col px-2 py-6 sm:px-6 md:flex-row md:space-x-4 md:py-12 lg:px-8"
                >
                    <p class="w-full font-medium text-gray-700 md:w-2/3">
                        Il n'y a pas encore d'activités en
                        <span class="font-semibold">{{ discipline.name }}</span
                        >.
                    </p>

                    <div
                        v-if="discipline.disciplines_similaires.length > 0"
                        class="w-full px-4 md:w-1/3"
                    >
                        <DisciplinesSimilaires
                            :disciplines-similaires="
                                discipline.disciplines_similaires
                            "
                        />
                    </div>
                </div>
                <!-- Blog -->
                <div
                    v-if="posts.length > 0"
                    class="my-8 px-3 md:my-16 md:px-6 lg:px-8"
                >
                    <h2
                        class="my-4 text-center text-lg font-semibold text-gray-600 md:my-8 md:text-2xl"
                    >
                        Les derniers articles
                    </h2>
                    <div
                        v-if="posts.length > 0"
                        class="grid h-auto grid-cols-1 place-items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3"
                    >
                        <PostFeaturedCard
                            v-for="post in posts"
                            :key="post.id"
                            :post="post"
                        />
                    </div>
                </div>
            </template>
        </template>
    </ResultLayout>
</template>
