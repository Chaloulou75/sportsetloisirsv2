<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted, computed, defineAsyncComponent } from "vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
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
import RangeMultiple from "@/Components/Forms/RangeMultiple.vue";
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
    show: Boolean,
});

const filteredCriteres = ref([]);

const addAddress = ref(false);
const latestAdresseId = computed(() => {
    if (props.structure.adresses.length > 0) {
        const latestAdresse = props.structure.adresses[0];
        return latestAdresse.id;
    }
    return null;
});

const addInstructeur = ref(false);

const form = useForm({
    structure_id: props.structure.id,
    discipline_id: props.discipline.id,
    categorie_id: null,
    titre: null,
    description: null,
    image: null,
    actif: true,
    criteres: {},
    souscriteres: {},
    adresse: ref(latestAdresseId.value),
    address: null,
    city: null,
    zip_code: null,
    country: null,
    address_lat: null,
    address_lng: null,
    instructeurId: null,
    instructeur_email: null,
    instructeur_contact: null,
    instructeur_phone: null,
});

watch(
    () => form.categorie_id,
    (newCategorieId) => {
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

const onSubmit = () => {
    form.post(
        route("structures.activites.newactivitystore", {
            structure: props.structure.slug,
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
    if (props.category) {
        form.categorie_id = ref(props.category?.id ?? null);
    } else {
        form.categorie_id = ref(
            props.discipline?.categories[0]?.pivot.id ?? null
        );
    }
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
                <div
                    class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                />
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
                            class="max-h-full w-full max-w-6xl transform rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
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
                                            v-if="discipline.categories"
                                            class="flex w-full flex-col items-center justify-start space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0"
                                        >
                                            <label
                                                for="categorie"
                                                class="block text-sm font-medium normal-case text-gray-700"
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
                                                        v-for="categorie in discipline.categories"
                                                        :key="
                                                            categorie.pivot.id
                                                        "
                                                        :value="
                                                            categorie.pivot.id
                                                        "
                                                    >
                                                        {{
                                                            categorie.pivot
                                                                .nom_categorie_pro
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
                                                class="block text-sm font-medium normal-case text-gray-700"
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
                                                class="block text-sm font-medium normal-case text-gray-700"
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
                                                class="block text-sm font-medium normal-case text-gray-700"
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

                                        <h3
                                            class="py-2 text-sm font-semibold text-gray-700"
                                        >
                                            Déclinaison de cette activité:
                                            <span class="text-xs italic"
                                                >(Ceci est la première
                                                déclinaison de votre activité,
                                                vous pourrez en ajouter dans
                                                votre panel
                                                d'administration)</span
                                            >
                                        </h3>

                                        <div
                                            v-if="filteredCriteres.length > 0"
                                            class="mx-auto grid w-full grid-cols-1 gap-4 bg-gray-50 p-4 shadow md:grid-cols-3 md:gap-8"
                                        >
                                            <div
                                                v-for="critere in filteredCriteres"
                                                :key="critere.id"
                                                class="col-span-1"
                                            >
                                                <!-- select -->
                                                <SelectForm
                                                    :classes="'block'"
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

                                                <!-- input text  -->
                                                <div
                                                    class="max-w-sm"
                                                    v-if="
                                                        critere.type_champ_form ===
                                                        'text'
                                                    "
                                                >
                                                    <label
                                                        :for="critere.nom"
                                                        class="block text-sm font-medium normal-case text-gray-700"
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
                                                        class="block text-sm font-medium normal-case text-gray-700"
                                                    >
                                                        {{ critere.nom }}
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <TextInput
                                                            type="number"
                                                            min="0"
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
                                                            form.criteres[
                                                                critere.id
                                                            ]
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
                                                            form.criteres[
                                                                critere.id
                                                            ]
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
                                                            form.criteres[
                                                                critere.id
                                                            ]
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
                                                            form.criteres[
                                                                critere.id
                                                            ]
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
                                                                form.criteres[
                                                                    critere.id
                                                                ]
                                                            "
                                                            :name="critere.nom"
                                                        />
                                                        <div
                                                            v-if="errors.months"
                                                            class="mt-2 text-xs text-red-500"
                                                        >
                                                            {{ errors.months }}
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
                                                            class="block text-sm font-medium normal-case text-gray-700"
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
                                                                        adresse.city_name
                                                                    }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <Checkbox
                                                        v-model:checked="
                                                            addAddress
                                                        "
                                                        name="Ajouter une adresse"
                                                    />
                                                </div>

                                                <!-- Range  -->
                                                <RangeInputForm
                                                    v-if="
                                                        critere.type_champ_form ===
                                                        'range'
                                                    "
                                                    class="w-full max-w-sm"
                                                    v-model="
                                                        form.criteres[
                                                            critere.id
                                                        ]
                                                    "
                                                    :name="critere.nom"
                                                    :min="critere.min"
                                                    :max="critere.max"
                                                    :unite="critere.unite"
                                                />

                                                <!-- Range Multiple -->
                                                <RangeMultiple
                                                    v-if="
                                                        critere.type_champ_form ===
                                                        'range multiple'
                                                    "
                                                    class="w-full max-w-sm"
                                                    v-model="
                                                        form.criteres[
                                                            critere.id
                                                        ]
                                                    "
                                                    :name="critere.nom"
                                                    :unite="critere.unite"
                                                    :min="critere.min"
                                                    :max="critere.max"
                                                />

                                                <!-- Instructeur -->
                                                <div
                                                    v-if="
                                                        critere.type_champ_form ===
                                                        'instructeur'
                                                    "
                                                    class="flex w-full items-start"
                                                >
                                                    <Checkbox
                                                        v-model:checked="
                                                            addInstructeur
                                                        "
                                                        name="Ajouter un instructeur"
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

                                                        <!-- sous crit checkbox -->
                                                        <CheckboxForm
                                                            class="max-w-sm"
                                                            v-if="
                                                                form.criteres[
                                                                    critere.id
                                                                ] === valeur &&
                                                                souscritere.type_champ_form ===
                                                                    'checkbox'
                                                            "
                                                            :critere="
                                                                souscritere
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
                                                        <!-- :is-checkbox-selected="
                                                                isCheckboxSelected
                                                            "
                                                            @update-selected-checkboxes="
                                                                updateSelectedCheckboxes
                                                            " -->
                                                        <!-- sous crit number -->
                                                        <InputLabel
                                                            class="py-2"
                                                            :for="
                                                                souscritere.nom
                                                            "
                                                            :value="
                                                                souscritere.nom
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
                                                            min="0"
                                                            :id="
                                                                souscritere.nom
                                                            "
                                                            :name="
                                                                souscritere.nom
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
                                                        <!-- sous crit text -->
                                                        <InputLabel
                                                            class="py-2"
                                                            :for="
                                                                souscritere.nom
                                                            "
                                                            :value="
                                                                souscritere.nom
                                                            "
                                                            v-if="
                                                                form.criteres[
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
                                                            :id="
                                                                souscritere.nom
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
                                                            v-if="
                                                                form.criteres[
                                                                    critere.id
                                                                ] === valeur &&
                                                                souscritere.type_champ_form ===
                                                                    'text' &&
                                                                souscritere.dis_cat_crit_val_id ===
                                                                    valeur.id
                                                            "
                                                        />

                                                        <RangeInputForm
                                                            v-if="
                                                                form.criteres[
                                                                    critere.id
                                                                ] === valeur &&
                                                                souscritere.type_champ_form ===
                                                                    'range' &&
                                                                souscritere.dis_cat_crit_val_id ===
                                                                    valeur.id
                                                            "
                                                            class="mt-1 w-full max-w-sm"
                                                            v-model="
                                                                form
                                                                    .souscriteres[
                                                                    souscritere
                                                                        .id
                                                                ]
                                                            "
                                                            :name="
                                                                souscritere.nom
                                                            "
                                                            :metric="
                                                                souscritere.nom
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
                                        :class="{
                                            'opacity-25': form.processing,
                                        }"
                                        type="submit"
                                        class="inline-flex justify-between rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-normal text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG v-if="form.processing" />
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
