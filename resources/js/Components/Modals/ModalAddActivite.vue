<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted, computed, defineAsyncComponent } from "vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";
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
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";

const emit = defineEmits(["close"]);

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const props = defineProps({
    errors: Object,
    structure: Object,
    discipline: Object,
    criteres: Object,
    category: Object,
    categories: Object,
    show: Boolean,
});

const categorieId = ref(null);

const filteredCriteres = ref([]);

const addAddress = ref(false);
const latestAdresseId = computed(() => {
    if (props.structure.adresses.length > 0) {
        const latestAdresse = props.structure.adresses[0];
        return latestAdresse.id;
    }
    return null; // Return a default value if there are no adresses
});

// const showNewSelect = ref(false);
const addInstructeur = ref(false);
const addDatesOpened = ref(false);
const addHoursOpened = ref(false);
const addDateOpen = ref(false);
const addHourOpen = ref(false);
const addMonthsOpen = ref(false);
const addRayon = ref(false);

const form = useForm({
    structure_id: props.structure.id,
    discipline_id: props.discipline.id,
    categorie_id: null,
    titre: null,
    description: null,
    image: null,
    actif: 1,
    criteres: {},
    souscriteres: {},
    adresse: ref(latestAdresseId.value),
    address: null,
    city: null,
    zip_code: null,
    country: null,
    address_lat: null,
    address_lng: null,
    date: null,
    time: null,
    date_debut: null,
    time_debut: null,
    months: null,
    instructeurId: null,
    instructeur_email: null,
    instructeur_contact: null,
    instructeur_phone: null,
    rayon_km: 0,
});

watch(
    () => form.categorie_id,
    (newCategorieId) => {
        // showNewSelect.value = false;
        filteredCriteres.value = props.criteres.filter(
            (critere) => critere.categorie_id === newCategorieId
        );
        form.criteres = {};
        form.souscriteres = {};
    }
);

watch(
    () => filteredCriteres.value,
    (newFilteredCriteres) => {
        newFilteredCriteres.forEach((critere) => {
            if (
                critere.type_champ_form === "select" &&
                critere.valeurs.length > 0
            ) {
                form.criteres[critere.id] = critere.valeurs[0];
            }
        });
    },
    { immediate: true }
);

const updateSelectedCheckboxes = (critereId, optionValue, checked) => {
    if (checked) {
        if (!form.criteres[critereId]) {
            // If the critereId doesn't exist in the form object, create a new array with the optionValue
            form.criteres[critereId] = [optionValue];
        } else {
            // If the critereId exists, push the optionValue to the existing array
            form.criteres[critereId].push(optionValue);
        }
    } else {
        // Remove the optionValue from the array
        const index = form.criteres[critereId].indexOf(optionValue);
        if (index !== -1) {
            form.criteres[critereId].splice(index, 1);
        }
    }
};

// Check if a checkbox is selected
const isCheckboxSelected = (critereId, optionValue) => {
    return (
        form.criteres[critereId] &&
        form.criteres[critereId].includes(optionValue)
    );
};

const onSubmit = () => {
    form.post(
        route("structures.activites.newactivitystore", {
            structure: props.structure.slug,
            discipline: props.discipline.slug,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                emit("close");
            },
        }
    );
};

onMounted(() => {
    form.categorie_id = ref(props.category?.id ?? null);
});
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
                <div class="fixed inset-0 bg-black bg-opacity-50" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
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
                            class="w-full max-w-6xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <form
                                @submit.prevent="onSubmit"
                                enctype="multipart/form-data"
                                autocomplete="off"
                            >
                                <DialogTitle
                                    as="div"
                                    class="flex w-full items-center justify-between"
                                >
                                    <h3
                                        class="text-lg font-medium leading-6 text-gray-800"
                                    >
                                        Ajouter une activité à
                                        <span class="text-indigo-500">{{
                                            discipline.name
                                        }}</span>
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
                                        <!-- categories -->
                                        <div
                                            v-if="categories"
                                            class="flex w-full flex-col items-center justify-start space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0"
                                        >
                                            <label
                                                for="categorie"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Categorie
                                            </label>
                                            <div
                                                class="mt-1 flex w-full rounded-md md:w-1/2"
                                            >
                                                <select
                                                    name="categorie"
                                                    id="categorie"
                                                    v-model="form.categorie_id"
                                                    class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                >
                                                    <option
                                                        v-for="categorie in categories"
                                                        :key="categorie.id"
                                                        :value="categorie.id"
                                                    >
                                                        {{
                                                            categorie.nom_categorie_pro
                                                        }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div
                                                v-if="form.errors.categorie_id"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ form.errors.categorie_id }}
                                            </div>
                                        </div>
                                        <!-- image -->
                                        <div>
                                            <label
                                                for="image"
                                                class="block text-sm font-medium text-gray-700"
                                                >Ajouter ou modifier la photo ou
                                                l'image:</label
                                            >
                                            <input
                                                class="mt-1 text-sm text-gray-700 focus:outline-none"
                                                type="file"
                                                id="image"
                                                @input="
                                                    form.image =
                                                        $event.target.files[0]
                                                "
                                            />
                                            <span
                                                v-if="errors.image"
                                                class="mt-2 text-xs text-red-500"
                                                >{{ errors.image[0] }}</span
                                            >
                                        </div>
                                        <!-- titre -->
                                        <div>
                                            <label
                                                for="titre"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Titre de l'activité
                                            </label>
                                            <div class="mt-1 flex rounded-md">
                                                <input
                                                    v-model="form.titre"
                                                    type="text"
                                                    name="titre"
                                                    id="titre"
                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                    :placeholder="`Tournois de ${discipline.name}`"
                                                    autocomplete="none"
                                                />
                                            </div>
                                            <div
                                                v-if="errors.titre"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ errors.titre }}
                                            </div>
                                        </div>
                                        <!-- description -->
                                        <div>
                                            <label
                                                for="description"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Description
                                            </label>
                                            <div class="mt-1">
                                                <textarea
                                                    v-model="form.description"
                                                    id="description"
                                                    name="description"
                                                    rows="2"
                                                    class="mt-1 block h-32 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    :class="{
                                                        errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                    }"
                                                    placeholder="Presentez votre activité"
                                                    autocomplete="none"
                                                />
                                            </div>
                                            <div
                                                v-if="errors.description"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ errors.description }}
                                            </div>
                                        </div>

                                        <!-- Criteres flex w-full flex-col flex-wrap items-start justify-start gap-4 space-y-2 md:flex-row md:space-y-0-->
                                        <div
                                            v-if="filteredCriteres.length > 0"
                                            class="mx-auto grid w-full grid-cols-1 gap-4 md:grid-cols-3"
                                        >
                                            <div
                                                v-for="critere in filteredCriteres"
                                                :key="critere.id"
                                                class="col-span-1"
                                            >
                                                <!-- select -->
                                                <SelectForm
                                                    class="max-w-sm"
                                                    v-if="
                                                        critere.type_champ_form ===
                                                        'select'
                                                    "
                                                    :name="critere.nom"
                                                    v-model="
                                                        form.criteres[
                                                            critere.id
                                                        ]
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
                                                        form.criteres[
                                                            critere.id
                                                        ]
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
                                                        critere.type_champ_form ===
                                                        'radio'
                                                    "
                                                    :name="critere.nom"
                                                    v-model="
                                                        form.criteres[
                                                            critere.id
                                                        ]
                                                    "
                                                    :options="critere.valeurs"
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
                                                                form.criteres[
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
                                                                form.criteres[
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

                                                <!-- Dates x 2 -->
                                                <div
                                                    v-if="
                                                        critere.type_champ_form ===
                                                        'dates'
                                                    "
                                                    class="flex max-w-sm flex-col items-start space-y-3"
                                                >
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <input
                                                            v-model="
                                                                addDatesOpened
                                                            "
                                                            id="addDatesOpened"
                                                            type="checkbox"
                                                            class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                        />
                                                        <label
                                                            for="addDatesOpened"
                                                            class="ml-2 text-sm font-medium text-gray-700"
                                                            >Ajouter vos dates
                                                            d'ouvertures
                                                        </label>
                                                    </div>
                                                    <OpenDaysForm
                                                        v-if="addDatesOpened"
                                                        class="w-full"
                                                        v-model="form.date"
                                                        :name="`Dates d'ouvertures`"
                                                    />
                                                </div>

                                                <!-- Heures ouverture / fermeture -->
                                                <div
                                                    v-if="
                                                        critere.type_champ_form ===
                                                        'times'
                                                    "
                                                    class="flex max-w-sm flex-col items-start space-y-3"
                                                >
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <input
                                                            v-model="
                                                                addHoursOpened
                                                            "
                                                            id="addHoursOpened"
                                                            type="checkbox"
                                                            class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                        />
                                                        <label
                                                            for="addHoursOpened"
                                                            class="ml-2 text-sm font-medium text-gray-700"
                                                            >Ajouter vos
                                                            horaires
                                                            d'ouvertures
                                                        </label>
                                                    </div>
                                                    <OpenTimesForm
                                                        v-if="addHoursOpened"
                                                        class="w-full"
                                                        v-model="form.time"
                                                        :name="`Horaires (ouverture /
                                                    fermeture)`"
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
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <input
                                                            v-model="
                                                                addDateOpen
                                                            "
                                                            id="addDateOpen"
                                                            type="checkbox"
                                                            class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                        />
                                                        <label
                                                            for="addDateOpen"
                                                            class="ml-2 text-sm font-medium text-gray-700"
                                                            >Ajouter une date de
                                                            début
                                                        </label>
                                                    </div>
                                                    <SingleDateForm
                                                        v-if="addDateOpen"
                                                        class="w-full"
                                                        v-model="
                                                            form.date_debut
                                                        "
                                                        :name="`Date de début`"
                                                    />
                                                </div>

                                                <!-- Heure seule -->
                                                <div
                                                    v-if="
                                                        critere.type_champ_form ===
                                                        'time'
                                                    "
                                                    class="flex max-w-sm flex-col items-start space-y-3"
                                                >
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <input
                                                            v-model="
                                                                addHourOpen
                                                            "
                                                            id="addHourOpen"
                                                            type="checkbox"
                                                            class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                        />
                                                        <label
                                                            for="addHourOpen"
                                                            class="ml-2 text-sm font-medium text-gray-700"
                                                            >Ajouter une heure
                                                            d'ouverture
                                                        </label>
                                                    </div>
                                                    <SingleTimeForm
                                                        v-if="addHourOpen"
                                                        class="w-full"
                                                        v-model="
                                                            form.time_debut
                                                        "
                                                        :name="`Horaire de début`"
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
                                                        <div
                                                            class="flex items-center"
                                                        >
                                                            <input
                                                                v-model="
                                                                    addMonthsOpen
                                                                "
                                                                id="addMonthsOpen"
                                                                type="checkbox"
                                                                class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                            />
                                                            <label
                                                                for="addMonthsOpen"
                                                                class="ml-2 text-sm font-medium text-gray-700"
                                                                >Ajouter vos
                                                                mois
                                                                d'ouvertures
                                                            </label>
                                                        </div>
                                                        <OpenMonthsForm
                                                            v-if="addMonthsOpen"
                                                            class="w-full"
                                                            v-model="
                                                                form.months
                                                            "
                                                            :name="`Mois d'ouverture`"
                                                        />
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
                                                                    form.adresse
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
                                                            v-model="addAddress"
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
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <input
                                                            v-model="addRayon"
                                                            id="addRayon"
                                                            type="checkbox"
                                                            class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                        />
                                                        <label
                                                            for="addRayon"
                                                            class="ml-2 text-sm font-medium text-gray-700"
                                                            >Ajouter un rayon
                                                            d'action
                                                        </label>
                                                    </div>
                                                    <RangeInputForm
                                                        class="w-full max-w-sm"
                                                        v-if="addRayon"
                                                        v-model="form.rayon_km"
                                                        :min="0"
                                                        :max="200"
                                                        :name="`Rayon de déplacement (en km)`"
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
                                                        class=""
                                                    >
                                                        <SelectForm
                                                            class="max-w-sm py-2"
                                                            v-if="
                                                                form.criteres[
                                                                    critere.id
                                                                ] === valeur &&
                                                                souscritere.type_champ_form ===
                                                                    'select' &&
                                                                souscritere.dis_cat_crit_val_id ===
                                                                    valeur.id
                                                            "
                                                            :name="
                                                                souscritere.nom
                                                            "
                                                            v-model="
                                                                form
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
                                                                Nombre
                                                            "
                                                            value="
                                                                Nombre
                                                            "
                                                            v-if="
                                                                form.criteres[
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
                                                            id="
                                                                Nombre
                                                            "
                                                            name="
                                                                Nombre
                                                            "
                                                            v-if="
                                                                form.criteres[
                                                                    critere.id
                                                                ] === valeur &&
                                                                souscritere.type_champ_form ===
                                                                    'number' &&
                                                                souscritere.dis_cat_crit_val_id ===
                                                                    valeur.id
                                                            "
                                                            v-model="
                                                                form
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

                                        <!-- Adresse By itself -->
                                        <!-- <div
                                            class="flex w-full items-end justify-between space-x-4"
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
                                                        v-model="form.adresse"
                                                        class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                    >
                                                        <option
                                                            v-for="adresse in structure.adresses"
                                                            :key="adresse.id"
                                                            :value="adresse.id"
                                                        >
                                                            {{
                                                                adresse.address
                                                            }}
                                                            -
                                                            {{
                                                                adresse.zip_code
                                                            }},
                                                            {{ adresse.city }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <input
                                                    v-model="addAddress"
                                                    id="addAddress"
                                                    type="checkbox"
                                                    class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                />
                                                <label
                                                    for="addAddress"
                                                    class="ml-2 text-sm font-medium text-gray-700"
                                                    >Ajouter une adresse</label
                                                >
                                            </div>
                                        </div> -->

                                        <!-- newAddress -->
                                        <AddressForm
                                            v-if="addAddress"
                                            :errors="errors"
                                            v-model:address="form.address"
                                            v-model:city="form.city"
                                            v-model:zip_code="form.zip_code"
                                            v-model:country="form.country"
                                            v-model:address_lat="
                                                form.address_lat
                                            "
                                            v-model:address_lng="
                                                form.address_lng
                                            "
                                        />

                                        <!-- Jours et Heures -->
                                        <!-- <div
                                            class="grid grid-cols-1 gap-4 md:grid-cols-2"
                                        >
                                            <div
                                                class="flex flex-col items-start space-y-3"
                                            >
                                                <div class="flex items-center">
                                                    <input
                                                        v-model="addDatesOpened"
                                                        id="addDatesOpened"
                                                        type="checkbox"
                                                        class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                    />
                                                    <label
                                                        for="addDatesOpened"
                                                        class="ml-2 text-sm font-medium text-gray-700"
                                                        >Ajouter vos dates
                                                        d'ouvertures
                                                    </label>
                                                </div>
                                                <OpenDaysForm
                                                    v-if="addDatesOpened"
                                                    class="w-full"
                                                    v-model="form.date"
                                                    :name="`Dates d'ouvertures`"
                                                />
                                            </div>
                                            <div
                                                class="flex flex-col items-start space-y-3"
                                            >
                                                <div class="flex items-center">
                                                    <input
                                                        v-model="addHoursOpened"
                                                        id="addHoursOpened"
                                                        type="checkbox"
                                                        class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                    />
                                                    <label
                                                        for="addHoursOpened"
                                                        class="ml-2 text-sm font-medium text-gray-700"
                                                        >Ajouter vos horaires
                                                        d'ouvertures
                                                    </label>
                                                </div>
                                                <OpenTimesForm
                                                    v-if="addHoursOpened"
                                                    class="w-full"
                                                    v-model="form.time"
                                                    :name="`Horaires (ouverture /
                                                    fermeture)`"
                                                />
                                            </div>
                                            <div
                                                class="flex flex-col items-start space-y-3"
                                            >
                                                <div class="flex items-center">
                                                    <input
                                                        v-model="addDateOpen"
                                                        id="addDateOpen"
                                                        type="checkbox"
                                                        class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                    />
                                                    <label
                                                        for="addDateOpen"
                                                        class="ml-2 text-sm font-medium text-gray-700"
                                                        >Ajouter une date de
                                                        début
                                                    </label>
                                                </div>
                                                <SingleDateForm
                                                    v-if="addDateOpen"
                                                    class="w-full"
                                                    v-model="form.date_debut"
                                                    :name="`Date de début`"
                                                />
                                            </div>
                                            <div
                                                class="flex flex-col items-start space-y-3"
                                            >
                                                <div class="flex items-center">
                                                    <input
                                                        v-model="addHourOpen"
                                                        id="addHourOpen"
                                                        type="checkbox"
                                                        class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                    />
                                                    <label
                                                        for="addHourOpen"
                                                        class="ml-2 text-sm font-medium text-gray-700"
                                                        >Ajouter une heure
                                                        d'ouverture
                                                    </label>
                                                </div>
                                                <SingleTimeForm
                                                    v-if="addHourOpen"
                                                    class="w-full"
                                                    v-model="form.time_debut"
                                                    :name="`Horaire de début`"
                                                />
                                            </div>
                                            <div
                                                class="flex flex-col items-start space-y-3"
                                            >
                                                <div class="flex items-center">
                                                    <input
                                                        v-model="addMonthsOpen"
                                                        id="addMonthsOpen"
                                                        type="checkbox"
                                                        class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                                    />
                                                    <label
                                                        for="addMonthsOpen"
                                                        class="ml-2 text-sm font-medium text-gray-700"
                                                        >Ajouter vos mois
                                                        d'ouvertures
                                                    </label>
                                                </div>
                                                <OpenMonthsForm
                                                    v-if="addMonthsOpen"
                                                    class="w-full"
                                                    v-model="form.months"
                                                    :name="`Mois d'ouverture`"
                                                />
                                            </div>
                                        </div> -->

                                        <!-- Instructeur -->
                                        <div class="flex w-full items-start">
                                            <input
                                                v-model="addInstructeur"
                                                id="addInstructeur"
                                                type="checkbox"
                                                class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                            />
                                            <label
                                                for="addInstructeur"
                                                class="ml-2 text-sm font-medium text-gray-700"
                                                >Ajouter un instructeur
                                                <span
                                                    class="text-xs italic text-gray-600"
                                                    >(celui-ci doit
                                                    préalablement être inscrit
                                                    sur sports-et-loisirs)</span
                                                >
                                            </label>
                                        </div>
                                        <InstructeurForm
                                            v-if="addInstructeur"
                                            v-model:instructeur_email="
                                                form.instructeur_email
                                            "
                                            v-model:instructeur_contact="
                                                form.instructeur_contact
                                            "
                                            v-model:instructeur_phone="
                                                form.instructeur_phone
                                            "
                                            :errors="errors"
                                        />

                                        <!-- Range km  -->
                                        <!-- <div class="flex items-center">
                                            <input
                                                v-model="addRayon"
                                                id="addRayon"
                                                type="checkbox"
                                                class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
                                            />
                                            <label
                                                for="addRayon"
                                                class="ml-2 text-sm font-medium text-gray-700"
                                                >Ajouter un rayon d'action
                                            </label>
                                        </div>
                                        <RangeInputForm
                                            v-if="addRayon"
                                            v-model="form.rayon_km"
                                            :min="0"
                                            :max="200"
                                            :name="`Rayon de déplacement (en km)`"
                                            :metric="`Km`"
                                        /> -->
                                    </div>
                                </div>
                                <div
                                    class="mt-4 flex w-full items-center justify-between"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-normal text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                        @click="emit('close')"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        :disabled="form.processing"
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-normal text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
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
