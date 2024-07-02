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
import RangeMultiple from "@/Components/Forms/RangeMultiple.vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";
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
    activite: Object,
    show: Boolean,
    criteres: Object,
    latestAdresseId: Number,
});

const addAddress = ref(false);
const addInstructeur = ref(false);
const filteredCriteres = computed(() => {
    return props.criteres.filter(
        (critere) => critere.categorie_id === props.activite.categorie_id
    );
});

const formAddProduit = useForm({
    structure_id: props.structure.id,
    discipline_id: props.activite.discipline_id,
    categorie_id: props.activite.categorie_id,
    criteres: {},
    souscriteres: {},
    adresse: props.latestAdresseId,
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
    rayon_km: 0,
});

watch(
    () => filteredCriteres.value,
    (newFilteredCriteres) => {
        newFilteredCriteres.forEach((critere) => {
            if (
                critere.type_champ_form === "select" &&
                critere.valeurs.length > 0
            ) {
                formAddProduit.criteres[critere.id] = critere.valeurs[0];
            }
        });
    },
    { immediate: true }
);

const updateSelectedCheckboxes = (critereId, optionValue, checked) => {
    const selectedCriteria = formAddProduit.criteres[critereId];

    if (checked) {
        if (!Array.isArray(selectedCriteria)) {
            formAddProduit.criteres[critereId] = ref([optionValue]);
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
        return (
            formAddProduit.criteres[critereId] &&
            formAddProduit.criteres[critereId].includes(optionValue)
        );
    };
});

const onSubmitAddProduitForm = () => {
    formAddProduit.post(
        route("structures.activites.produits.store", {
            structure: props.structure.slug,
            activite: props.activite.id,
        }),
        {
            preserveScroll: true,
            remember: false,
            onSuccess: () => {
                formAddProduit.reset();
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
                            class="w-full max-w-6xl transform rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <form
                                @submit.prevent="onSubmitAddProduitForm()"
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
                                        Ajouter un produit / déclinaison pour
                                        l'activité
                                        <span class="text-blue-700">
                                            {{ activite.titre }}</span
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
                                                            formAddProduit
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
                                                            formAddProduit
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
                                                            formAddProduit
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
                                                                    formAddProduit
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
                                                                    formAddProduit
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
                                                                formAddProduit
                                                                    .criteres[
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
                                                                formAddProduit
                                                                    .criteres[
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
                                                                formAddProduit
                                                                    .criteres[
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
                                                                formAddProduit
                                                                    .criteres[
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
                                                                    formAddProduit
                                                                        .criteres[
                                                                        critere
                                                                            .id
                                                                    ]
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
                                                                        formAddProduit.adresse
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

                                                    <!-- Range  -->
                                                    <div
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'range'
                                                        "
                                                    >
                                                        <RangeInputForm
                                                            class="w-full max-w-sm"
                                                            v-model="
                                                                formAddProduit
                                                                    .criteres[
                                                                    critere.id
                                                                ]
                                                            "
                                                            :name="critere.nom"
                                                            :min="critere.min"
                                                            :max="critere.max"
                                                            :unite="
                                                                critere.unite
                                                            "
                                                        />
                                                    </div>
                                                    <!-- Range Multiple -->
                                                    <RangeMultiple
                                                        v-if="
                                                            critere.type_champ_form ===
                                                            'range multiple'
                                                        "
                                                        class="w-full max-w-sm"
                                                        v-model="
                                                            formAddProduit
                                                                .criteres[
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
                                                                >(celui-ci doit
                                                                préalablement
                                                                être inscrit sur
                                                                sports-et-loisirs)</span
                                                            >
                                                        </label>
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
                                                            class=""
                                                        >
                                                            <SelectForm
                                                                :classes="'block'"
                                                                class="max-w-sm py-2"
                                                                v-if="
                                                                    formAddProduit
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
                                                                    formAddProduit
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
                                                                :for="
                                                                    souscritere.nom
                                                                "
                                                                :value="
                                                                    souscritere.nom
                                                                "
                                                                v-if="
                                                                    formAddProduit
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
                                                                min="0"
                                                                :id="
                                                                    souscritere.nom
                                                                "
                                                                :name="
                                                                    souscritere.nom
                                                                "
                                                                v-if="
                                                                    formAddProduit
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
                                                                    formAddProduit
                                                                        .souscriteres[
                                                                        souscritere
                                                                            .id
                                                                    ]
                                                                "
                                                            />
                                                            <InputLabel
                                                                class="py-2"
                                                                :for="
                                                                    souscritere.nom
                                                                "
                                                                :value="
                                                                    souscritere.nom
                                                                "
                                                                v-if="
                                                                    formAddProduit
                                                                        .criteres[
                                                                        critere
                                                                            .id
                                                                    ] ===
                                                                        valeur &&
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
                                                                v-if="
                                                                    formAddProduit
                                                                        .criteres[
                                                                        critere
                                                                            .id
                                                                    ] ===
                                                                        valeur &&
                                                                    souscritere.type_champ_form ===
                                                                        'text' &&
                                                                    souscritere.dis_cat_crit_val_id ===
                                                                        valeur.id
                                                                "
                                                                v-model="
                                                                    formAddProduit
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
                                                    formAddProduit.address
                                                "
                                                v-model:city="
                                                    formAddProduit.city
                                                "
                                                v-model:zip_code="
                                                    formAddProduit.zip_code
                                                "
                                                v-model:country="
                                                    formAddProduit.country
                                                "
                                                v-model:address_lat="
                                                    formAddProduit.address_lat
                                                "
                                                v-model:address_lng="
                                                    formAddProduit.address_lng
                                                "
                                            />

                                            <InstructeurForm
                                                v-if="addInstructeur"
                                                v-model:instructeur_email="
                                                    formAddProduit.instructeur_email
                                                "
                                                v-model:instructeur_contact="
                                                    formAddProduit.instructeur_contact
                                                "
                                                v-model:instructeur_phone="
                                                    formAddProduit.instructeur_phone
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
                                        :disabled="formAddProduit.processing"
                                        :class="{
                                            'opacity-25':
                                                formAddProduit.processing,
                                        }"
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="formAddProduit.processing"
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
