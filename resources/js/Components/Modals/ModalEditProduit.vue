<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch, computed, defineAsyncComponent } from "vue";
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
import InstructeurForm from "@/Components/Forms/InstructeurForm.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
    Switch,
} from "@headlessui/vue";

const emit = defineEmits(["close"]);

const props = defineProps({
    errors: Object,
    structure: Object,
    structureActivite: Object,
    produit: Object,
    show: Boolean,
    criteres: Object,
    latestAdresseId: Number,
});

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const addAddress = ref(false);
const addInstructeur = ref(false);
const filteredCriteres = computed(() => {
    return props.criteres.filter(
        (critere) =>
            critere.categorie_id === props.structureActivite.categorie_id
    );
});

const formEditProduit = useForm({
    actif: null,
    criteres: {},
    souscriteres: {},
    adresse: null,
    address: null,
    city: null,
    zip_code: null,
    country: null,
    address_lat: null,
    address_lng: null,
    date_seule: null,
    dates: null,
    time_seule: null,
    times: null,
    months: null,
    instructeurId: null,
    instructeur_email: null,
    instructeur_contact: null,
    instructeur_phone: null,
    rayon_km: 0,
});

const updateSelectedCheckboxes = (critereId, optionValue, checked) => {
    const selectedCriteria = formEditProduit.criteres[critereId];

    if (checked) {
        if (!Array.isArray(selectedCriteria)) {
            formEditProduit.criteres[critereId] = ref([optionValue]);
        } else {
            selectedCriteria.push(optionValue);
        }
    } else {
        if (Array.isArray(selectedCriteria)) {
            const index = selectedCriteria.indexOf(optionValue);
            if (index !== -1) {
                selectedCriteria.splice(index, 1);
            }
        }
    }
};

const isCheckboxSelected = computed(() => {
    return (critereId, optionValue) => {
        const isSelected =
            formEditProduit.criteres[critereId] &&
            formEditProduit.criteres[critereId].includes(optionValue);
        return isSelected;
    };
});

watch(
    () => props.produit,
    (newValue) => {
        if (newValue) {
            formEditProduit.adresse = newValue.lieu_id;
            formEditProduit.actif = !!newValue.actif;
            formEditProduit.criteres = {};
            formEditProduit.souscriteres = {};

            newValue.criteres.forEach((critere) => {
                const critereId = critere.critere_id;
                const critereValue = critere.critere_valeur;
                const sousCriteres = critere.sous_criteres;
                filteredCriteres.value.forEach((officialCritere) => {
                    if (officialCritere.type_champ_form === "time") {
                        alert("time");
                    } else if (officialCritere.type_champ_form === "date") {
                        alert("date");
                    } else if (officialCritere.type_champ_form === "dates") {
                        alert("dates");
                    } else if (officialCritere.type_champ_form === "times") {
                        alert("times");
                    } else {
                        officialCritere.valeurs.forEach(
                            (officialCritereValeur) => {
                                if (
                                    officialCritereValeur.id === critereValue.id
                                ) {
                                    if (!formEditProduit.criteres[critereId]) {
                                        if (
                                            officialCritere.type_champ_form ===
                                            "checkbox"
                                        ) {
                                            formEditProduit.criteres[
                                                critereId
                                            ] = [officialCritereValeur];
                                        } else {
                                            formEditProduit.criteres[
                                                critereId
                                            ] = officialCritereValeur;
                                        }
                                    } else {
                                        const existingValue =
                                            formEditProduit.criteres[critereId];

                                        if (!Array.isArray(existingValue)) {
                                            formEditProduit.criteres[
                                                critereId
                                            ] = [existingValue];
                                            if (
                                                !formEditProduit.criteres[
                                                    critereId
                                                ].includes(
                                                    officialCritereValeur
                                                )
                                            ) {
                                                formEditProduit.criteres[
                                                    critereId
                                                ].push(officialCritereValeur);
                                            }
                                        } else {
                                            if (
                                                !formEditProduit.criteres[
                                                    critereId
                                                ].includes(
                                                    officialCritereValeur
                                                )
                                            ) {
                                                formEditProduit.criteres[
                                                    critereId
                                                ].push(officialCritereValeur);
                                            }
                                        }
                                    }
                                    if (officialCritereValeur.sous_criteres) {
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
                                                                        formEditProduit.souscriteres[
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
                                                            formEditProduit.souscriteres[
                                                                souscritereId
                                                            ] =
                                                                prodSousCritValeur;
                                                        }
                                                    );
                                                }
                                            }
                                        );
                                    }
                                }
                            }
                        );
                    }
                });
            });

            if (newValue.dates) {
                newValue.dates.forEach((dateProduit) => {
                    if (dateProduit.time_debut) {
                        const [hours, minutes] = dateProduit.time_debut
                            .split(":")
                            .map(Number);
                        formEditProduit.time_seule = { hours, minutes };
                    }
                    if (dateProduit.houropen && dateProduit.hourclose) {
                        const [hoursopen, minutesopen] = dateProduit.houropen
                            .split(":")
                            .map(Number);
                        const [hoursclose, minutesclose] = dateProduit.hourclose
                            .split(":")
                            .map(Number);
                        formEditProduit.times = [
                            { hours: hoursopen, minutes: minutesopen },
                            { hours: hoursclose, minutes: minutesclose },
                        ];
                    }
                    if (dateProduit.date_debut) {
                        formEditProduit.date_seule = new Date(
                            dateProduit.date_debut
                        );
                    }
                    if (dateProduit.dayopen && dateProduit.dayclose) {
                        const dateOpen = new Date(dateProduit.dayopen);
                        const dateClose = new Date(dateProduit.dayclose);
                        formEditProduit.dates = [dateOpen, dateClose];
                    }
                    if (dateProduit.start_month && dateProduit.end_month) {
                        const start = {
                            month: new Date(dateProduit.start_month).getMonth(),
                            year: new Date(
                                dateProduit.start_month
                            ).getFullYear(),
                        };
                        const end = {
                            month: new Date(dateProduit.end_month).getMonth(),
                            year: new Date(dateProduit.end_month).getFullYear(),
                        };
                        formEditProduit.months = [start, end];
                    }
                });
            }
        }
    }
);

const onSubmitEditProduitForm = () => {
    formEditProduit.put(
        route("structures.activites.produits.update", {
            structure: props.structure.slug,
            activite: props.structureActivite.id,
            produit: props.produit.id,
        }),
        {
            preserveScroll: true,
            remember: false,
            onSuccess: () => {
                formEditProduit.reset();
                emit("close");
            },
        }
    );
};
</script>
<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="open = false" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-800 bg-opacity-50" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-6xl transform rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <form
                                @submit.prevent="onSubmitEditProduitForm()"
                                autocomplete="off"
                            >
                                <DialogTitle
                                    as="div"
                                    class="flex w-full items-center justify-between"
                                >
                                    <h3
                                        class="text-lg font-medium leading-6 text-gray-800"
                                    >
                                        Modifier le produit / déclinaison
                                        {{ produit.id }} pour l'activité
                                        <span class="text-blue-700">
                                            {{ structureActivite.titre }}</span
                                        >
                                    </h3>
                                    <button type="button">
                                        <XCircleIcon
                                            @click="emit('close')"
                                            class="h-6 w-6 text-gray-600 hover:text-red-600"
                                        />
                                    </button>
                                </DialogTitle>
                                <div class="mt-2 w-full">
                                    <div class="flex flex-col space-y-3">
                                        <div class="flex flex-col space-y-3">
                                            <div
                                                class="flex items-center space-x-2"
                                            >
                                                <Switch
                                                    v-model="
                                                        formEditProduit.actif
                                                    "
                                                    :class="
                                                        formEditProduit.actif
                                                            ? 'bg-green-600'
                                                            : 'bg-gray-200'
                                                    "
                                                    class="relative inline-flex h-6 w-11 items-center rounded-full"
                                                >
                                                    <span class="sr-only"
                                                        >Actif</span
                                                    >
                                                    <span
                                                        :class="
                                                            formEditProduit.actif
                                                                ? 'translate-x-6'
                                                                : 'translate-x-1'
                                                        "
                                                        class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                                                    />
                                                </Switch>
                                                <p
                                                    class="text-lg font-semibold text-green-600"
                                                    v-if="formEditProduit.actif"
                                                >
                                                    Actif
                                                </p>
                                                <p
                                                    class="text-lg font-semibold text-gray-600"
                                                    v-else
                                                >
                                                    Inactif
                                                </p>
                                            </div>

                                            <!-- Criteres -->
                                            <div
                                                v-if="
                                                    filteredCriteres.length > 0
                                                "
                                                class="mx-auto grid w-full grid-cols-1 gap-4 md:grid-cols-3"
                                            >
                                                <div
                                                    v-for="critere in filteredCriteres"
                                                    :key="critere.id"
                                                    class="col-span-1"
                                                >
                                                    <!-- select  -->
                                                    <SelectForm
                                                        :classes="'block'"
                                                        class="max-w-sm"
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'select'
                                                        "
                                                        :name="critere.nom"
                                                        v-model="
                                                            formEditProduit
                                                                .criteres[
                                                                critere.id
                                                            ]
                                                        "
                                                        :options="
                                                            critere.valeurs
                                                        "
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
                                                            formEditProduit
                                                                .criteres[
                                                                critere.id
                                                            ]
                                                        "
                                                        :options="
                                                            critere.valeurs
                                                        "
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
                                                            critere.type_champ_form ===
                                                            'radio'
                                                        "
                                                        :name="critere.nom"
                                                        v-model="
                                                            formEditProduit
                                                                .criteres[
                                                                critere.id
                                                            ]
                                                        "
                                                        :options="
                                                            critere.valeurs
                                                        "
                                                    />

                                                    <!-- input text -->
                                                    <div
                                                        class="max-w-sm"
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'text'
                                                        "
                                                    >
                                                        <label
                                                            :for="critere.nom"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            {{ critere.nom }}
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md"
                                                        >
                                                            <TextInput
                                                                type="text"
                                                                v-model="
                                                                    formEditProduit
                                                                        .criteres[
                                                                        critere
                                                                            .id
                                                                    ]
                                                                "
                                                                :name="
                                                                    critere.nom
                                                                "
                                                                :id="
                                                                    critere.nom
                                                                "
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
                                                            critere.type_champ_form ===
                                                            'number'
                                                        "
                                                    >
                                                        <label
                                                            :for="critere.nom"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            {{ critere.nom }}
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md"
                                                        >
                                                            <TextInput
                                                                type="number"
                                                                min="1"
                                                                max="59"
                                                                v-model="
                                                                    formEditProduit
                                                                        .criteres[
                                                                        critere
                                                                            .id
                                                                    ]
                                                                "
                                                                :name="
                                                                    critere.nom
                                                                "
                                                                :id="
                                                                    critere.nom
                                                                "
                                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                placeholder=""
                                                                autocomplete="none"
                                                            />
                                                        </div>
                                                    </div>

                                                    <!-- Heure seule -->
                                                    <div
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'time'
                                                        "
                                                        class="flex max-w-sm flex-col items-start space-y-3"
                                                    >
                                                        <SingleTimeForm
                                                            class="w-full"
                                                            v-model="
                                                                formEditProduit.time_seule
                                                            "
                                                            :name="critere.nom"
                                                        />
                                                    </div>

                                                    <!-- Heures x2 ouverture / fermeture -->
                                                    <div
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'times'
                                                        "
                                                        class="flex max-w-sm flex-col items-start space-y-3"
                                                    >
                                                        <OpenTimesForm
                                                            class="w-full"
                                                            v-model="
                                                                formEditProduit.times
                                                            "
                                                            :name="critere.nom"
                                                        />
                                                    </div>

                                                    <!-- Date seule -->
                                                    <div
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'date'
                                                        "
                                                        class="flex max-w-sm flex-col items-start space-y-3"
                                                    >
                                                        <SingleDateForm
                                                            class="w-full"
                                                            v-model="
                                                                formEditProduit.date_seule
                                                            "
                                                            :name="critere.nom"
                                                        />
                                                    </div>

                                                    <!-- Dates x 2 -->
                                                    <div
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'dates'
                                                        "
                                                        class="flex max-w-sm flex-col items-start space-y-3"
                                                    >
                                                        <OpenDaysForm
                                                            class="w-full"
                                                            v-model="
                                                                formEditProduit.dates
                                                            "
                                                            :name="critere.nom"
                                                        />
                                                    </div>

                                                    <!-- Mois -->
                                                    <div
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'mois'
                                                        "
                                                    >
                                                        <div
                                                            class="flex max-w-sm flex-col items-start space-y-3"
                                                        >
                                                            <OpenMonthsForm
                                                                class="w-full"
                                                                v-model="
                                                                    formEditProduit.months
                                                                "
                                                                :name="
                                                                    critere.nom
                                                                "
                                                            />
                                                            <div
                                                                v-if="
                                                                    errors.months
                                                                "
                                                                class="mt-2 text-xs text-red-500"
                                                            >
                                                                {{
                                                                    errors.months
                                                                }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Adresse -->
                                                    <div
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'adresse'
                                                        "
                                                        class="flex w-full max-w-sm flex-col space-y-2"
                                                    >
                                                        <div
                                                            v-if="!addAddress"
                                                            class="flex-1"
                                                        >
                                                            <label
                                                                for="
                                                            adresse
                                                        "
                                                                class="block text-sm font-medium text-gray-700"
                                                            >
                                                                Adresse
                                                            </label>
                                                            <div
                                                                class="mt-1 flex rounded-md"
                                                            >
                                                                <select
                                                                    name="
                                                                adresse
                                                            "
                                                                    id="
                                                                adresse
                                                            "
                                                                    v-model="
                                                                        formEditProduit.adresse
                                                                    "
                                                                    class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                                >
                                                                    <option
                                                                        v-for="adresse in structure.adresses"
                                                                        :key="
                                                                            adresse.id
                                                                        "
                                                                        :value="
                                                                            adresse.id
                                                                        "
                                                                    >
                                                                        {{
                                                                            adresse.address
                                                                        }}
                                                                        -
                                                                        {{
                                                                            adresse.zip_code
                                                                        }},
                                                                        {{
                                                                            adresse.city
                                                                        }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="flex items-center"
                                                        >
                                                            <input
                                                                v-model="
                                                                    addAddress
                                                                "
                                                                id="addAddress"
                                                                type="checkbox"
                                                                class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                            />
                                                            <label
                                                                for="addAddress"
                                                                class="ml-2 text-sm font-medium text-gray-700"
                                                                >Ajouter une
                                                                adresse</label
                                                            >
                                                        </div>
                                                    </div>

                                                    <!-- Range km  -->
                                                    <div
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'rayon'
                                                        "
                                                        class="flex w-full max-w-sm flex-col items-start space-y-3"
                                                    >
                                                        <RangeInputForm
                                                            class="w-full max-w-sm"
                                                            v-model="
                                                                formEditProduit.rayon_km
                                                            "
                                                            :min="0"
                                                            :max="200"
                                                            :name="`Rayon de déplacement (en km)`"
                                                            :metric="`Km`"
                                                        />
                                                        <!-- Instructeur -->
                                                        <div
                                                            v-if="
                                                                critere.type_champ_form ===
                                                                'instructeur'
                                                            "
                                                            class="flex w-full items-start"
                                                        >
                                                            <input
                                                                v-model="
                                                                    addInstructeur
                                                                "
                                                                id="addInstructeur"
                                                                type="checkbox"
                                                                class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                            />
                                                            <label
                                                                for="addInstructeur"
                                                                class="ml-2 text-sm font-medium text-gray-700"
                                                                >Ajouter un
                                                                instructeur
                                                                <span
                                                                    class="text-xs italic text-gray-600"
                                                                    >(celui-ci
                                                                    doit
                                                                    préalablement
                                                                    être inscrit
                                                                    sur
                                                                    sports-et-loisirs)</span
                                                                >
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- sous criteres -->
                                                    <div
                                                        v-for="valeur in critere.valeurs"
                                                        :key="valeur.id"
                                                    >
                                                        <div
                                                            v-for="souscritere in valeur.sous_criteres"
                                                            :key="
                                                                souscritere.id
                                                            "
                                                        >
                                                            <SelectForm
                                                                :classes="'block'"
                                                                class="max-w-sm py-2"
                                                                v-if="
                                                                    formEditProduit
                                                                        .criteres[
                                                                        critere
                                                                            .id
                                                                    ] ===
                                                                        valeur &&
                                                                    souscritere.type_champ_form ===
                                                                        'select' &&
                                                                    souscritere.dis_cat_crit_val_id ===
                                                                        valeur.id
                                                                "
                                                                :name="
                                                                    souscritere.nom
                                                                "
                                                                v-model="
                                                                    formEditProduit
                                                                        .souscriteres[
                                                                        souscritere
                                                                            .id
                                                                    ]
                                                                "
                                                                :options="
                                                                    souscritere.sous_criteres_valeurs
                                                                "
                                                            />

                                                            <InputLabel
                                                                class="py-2"
                                                                for="
                                                                Quantité
                                                            "
                                                                value="
                                                                Quantité
                                                            "
                                                                v-if="
                                                                    formEditProduit
                                                                        .criteres[
                                                                        critere
                                                                            .id
                                                                    ] ===
                                                                        valeur &&
                                                                    souscritere.type_champ_form ===
                                                                        'number' &&
                                                                    souscritere.dis_cat_crit_val_id ===
                                                                        valeur.id
                                                                "
                                                            />
                                                            <TextInput
                                                                class="w-full"
                                                                type="number"
                                                                id="
                                                                Nombre
                                                            "
                                                                name="
                                                                Nombre
                                                            "
                                                                v-if="
                                                                    formEditProduit
                                                                        .criteres[
                                                                        critere
                                                                            .id
                                                                    ] ===
                                                                        valeur &&
                                                                    souscritere.type_champ_form ===
                                                                        'number' &&
                                                                    souscritere.dis_cat_crit_val_id ===
                                                                        valeur.id
                                                                "
                                                                v-model="
                                                                    formEditProduit
                                                                        .souscriteres[
                                                                        souscritere
                                                                            .id
                                                                    ]
                                                                "
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- newAddress -->
                                            <AddressForm
                                                v-if="addAddress"
                                                :errors="errors"
                                                v-model:address="
                                                    formEditProduit.address
                                                "
                                                v-model:city="
                                                    formEditProduit.city
                                                "
                                                v-model:zip_code="
                                                    formEditProduit.zip_code
                                                "
                                                v-model:country="
                                                    formEditProduit.country
                                                "
                                                v-model:address_lat="
                                                    formEditProduit.address_lat
                                                "
                                                v-model:address_lng="
                                                    formEditProduit.address_lng
                                                "
                                            />

                                            <InstructeurForm
                                                v-if="addInstructeur"
                                                v-model:instructeur_email="
                                                    formEditProduit.instructeur_email
                                                "
                                                v-model:instructeur_contact="
                                                    formEditProduit.instructeur_contact
                                                "
                                                v-model:instructeur_phone="
                                                    formEditProduit.instructeur_phone
                                                "
                                                :errors="errors"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="mt-4 flex w-full items-center justify-between"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                        @click="emit('close')"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        :class="{
                                            'opacity-25':
                                                formEditProduit.processing,
                                        }"
                                        :disabled="formEditProduit.processing"
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="formEditProduit.processing"
                                        />
                                        Enregistrer
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
