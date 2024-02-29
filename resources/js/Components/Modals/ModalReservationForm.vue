<script setup>
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import SelectForm from "@/Components/Forms/SelectForm.vue";
import CheckboxForm from "@/Components/Forms/CheckboxForm.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { XCircleIcon } from "@heroicons/vue/24/outline";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import dayjs from "dayjs";
import "dayjs/locale/fr";

const emit = defineEmits(["close"]);

const props = defineProps({
    produits: Object,
    produitId: Number,
    catTarifId: Number,
    show: Boolean,
});

const selectedProduit = computed(() => {
    return props.produits.find((produit) => produit.id === props.produitId);
});

const filteredCatTarif = computed(() => {
    if (!props.catTarifId || !props.produitId || !props.produits) {
        return [];
    }

    const produit = props.produits.find(
        (produit) => produit.id === props.produitId
    );

    if (!produit || !produit.cat_tarifs) {
        return [];
    }

    return produit.cat_tarifs.filter(
        (catTarif) => catTarif.id === props.catTarifId
    );
});

const formatDate = (dateTimeString) => {
    return dayjs(dateTimeString)
        .locale("fr")
        .format("dddd D MMMM YYYY, HH[h]mm");
};

const bookingForm = useForm({
    produitId: selectedProduit.value.id,
    catTarifId: props.catTarifId,
    attributs: {},
    sousattributs: {},
    plannings: [],
});

const updateSelectedCheckboxes = (fieldId, optionValue, checked) => {
    const selectedValue = bookingForm.attributs[fieldId];

    if (checked) {
        if (!Array.isArray(selectedValue)) {
            bookingForm.attributs[fieldId] = ref([optionValue]);
        } else {
            selectedValue.push(optionValue);
        }
    } else {
        if (Array.isArray(selectedValue)) {
            const index = selectedValue.indexOf(optionValue);
            if (index !== -1) {
                selectedValue.splice(index, 1);
            }
        }
    }
};

const isCheckboxSelected = computed(() => {
    return (fieldId, optionValue) => {
        return (
            bookingForm.attributs[fieldId] &&
            bookingForm.attributs[fieldId].includes(optionValue)
        );
    };
});

const updateSousAttrSelectedCheckboxes = (
    sousFieldId,
    optionValue,
    checked
) => {
    const selectedSousField = bookingForm.sousattributs[sousFieldId];

    if (checked) {
        if (!Array.isArray(selectedSousField)) {
            // Use ref() to ensure reactivity when modifying arrays
            bookingForm.sousattributs[sousFieldId] = ref([optionValue]);
        } else {
            selectedSousField.push(optionValue);
        }
    } else {
        if (Array.isArray(selectedSousField)) {
            const index = selectedSousField.indexOf(optionValue);
            if (index !== -1) {
                selectedSousField.splice(index, 1);
            }
        }
    }
};

const isCheckboxSousAttrSelected = computed(() => {
    return (sousFieldId, optionValue) => {
        return (
            bookingForm.sousattributs[sousFieldId] &&
            bookingForm.sousattributs[sousFieldId].includes(optionValue)
        );
    };
});

const onSubmit = () => {
    bookingForm.post(route("panier.store"), {
        preserveScroll: true,
        remember: false,
        onSuccess: () => {
            bookingForm.reset();
            emit("close");
        },
    });
};
</script>
<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="open = false" class="relative z-[9999]">
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
                    class="fixed inset-0 transition-opacity bg-black bg-opacity-50"
                />
            </TransitionChild>
            <div
                class="fixed inset-0 flex items-center justify-center w-full p-4 text-center"
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
                        class="w-full max-w-6xl max-h-full p-6 space-y-5 overflow-y-auto text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
                    >
                        <DialogTitle
                            as="div"
                            class="flex items-center justify-between w-full"
                        >
                            <h3
                                class="text-lg font-medium leading-6 text-gray-800"
                            >
                                Formulaire de réservation
                            </h3>
                            <button type="button">
                                <XCircleIcon
                                    @click="emit('close')"
                                    class="w-6 h-6 text-gray-600 hover:text-red-600"
                                />
                            </button>
                        </DialogTitle>
                        <div>
                            <form
                                @submit.prevent="onSubmit()"
                                autocomplete="off"
                            >
                                <div v-if="filteredCatTarif.length > 0">
                                    <div
                                        v-for="catTarif in filteredCatTarif"
                                        :key="catTarif.id"
                                        class="flex flex-col space-y-3"
                                    >
                                        <div
                                            v-if="
                                                catTarif.cat_tarif_type
                                                    .tarif_booking_fields &&
                                                catTarif.cat_tarif_type
                                                    .tarif_booking_fields
                                                    .length > 0
                                            "
                                            class="grid w-full grid-cols-1 gap-4 mx-auto md:grid-cols-3"
                                        >
                                            <div
                                                v-for="field in catTarif
                                                    .cat_tarif_type
                                                    .tarif_booking_fields"
                                                :key="field.id"
                                                class="col-span-1"
                                            >
                                                <!-- select  -->
                                                <SelectForm
                                                    :classes="'block'"
                                                    class="max-w-sm"
                                                    v-if="
                                                        field.type_champ_form ===
                                                        'select'
                                                    "
                                                    :name="field.nom"
                                                    v-model="
                                                        bookingForm.attributs[
                                                            field.id
                                                        ]
                                                    "
                                                    :options="field.valeurs"
                                                />
                                                <!-- checkbox -->
                                                <CheckboxForm
                                                    class="max-w-sm"
                                                    v-if="
                                                        field.type_champ_form ===
                                                        'checkbox'
                                                    "
                                                    :critere="field"
                                                    :name="field.nom"
                                                    v-model="
                                                        bookingForm.attributs[
                                                            field.id
                                                        ]
                                                    "
                                                    :options="field.valeurs"
                                                    :is-checkbox-selected="
                                                        isCheckboxSelected
                                                    "
                                                    @update-selected-checkboxes="
                                                        updateSelectedCheckboxes
                                                    "
                                                />
                                                <!-- input text -->
                                                <div
                                                    class="max-w-sm"
                                                    v-if="
                                                        field.type_champ_form ===
                                                        'text'
                                                    "
                                                >
                                                    <label
                                                        :for="field.nom"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        {{ field.nom }}
                                                    </label>
                                                    <div
                                                        class="flex mt-1 rounded-md"
                                                    >
                                                        <TextInput
                                                            type="text"
                                                            v-model="
                                                                bookingForm
                                                                    .attributs[
                                                                    field.id
                                                                ]
                                                            "
                                                            :name="field.nom"
                                                            :id="field.nom"
                                                            class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                </div>

                                                <!-- number text -->
                                                <div
                                                    class="max-w-sm"
                                                    v-if="
                                                        field.type_champ_form ===
                                                        'number'
                                                    "
                                                >
                                                    <label
                                                        :for="field.nom"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        {{ field.nom }}
                                                    </label>
                                                    <div
                                                        class="flex mt-1 rounded-md"
                                                    >
                                                        <TextInput
                                                            type="number"
                                                            v-model="
                                                                bookingForm
                                                                    .attributs[
                                                                    field.id
                                                                ]
                                                            "
                                                            :name="field.nom"
                                                            :id="field.nom"
                                                            class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                </div>
                                                <!-- sous fields -->

                                                <template
                                                    v-for="sousField in field.sous_fields"
                                                    :key="sousField.id"
                                                >
                                                    <SelectForm
                                                        :classes="'block '"
                                                        class="w-full max-w-sm"
                                                        v-if="
                                                            sousField.type_champ_form ===
                                                            'select'
                                                        "
                                                        :name="sousField.nom"
                                                        v-model="
                                                            bookingForm
                                                                .sousattributs[
                                                                sousField.id
                                                            ]
                                                        "
                                                        :options="
                                                            sousField.valeurs
                                                        "
                                                    />

                                                    <!-- checkbox -->
                                                    <CheckboxForm
                                                        class="max-w-sm"
                                                        v-if="
                                                            sousField.type_champ_form ===
                                                            'checkbox'
                                                        "
                                                        :critere="sousField"
                                                        :name="sousField.nom"
                                                        v-model="
                                                            bookingForm
                                                                .sousattributs[
                                                                sousField.id
                                                            ]
                                                        "
                                                        :options="
                                                            sousField.valeurs
                                                        "
                                                        :is-checkbox-selected="
                                                            isCheckboxSousAttrSelected
                                                        "
                                                        @update-selected-checkboxes="
                                                            updateSousAttrSelectedCheckboxes
                                                        "
                                                    />
                                                    <!-- input text -->
                                                    <div
                                                        class="w-full max-w-sm"
                                                        v-if="
                                                            sousField.type_champ_form ===
                                                            'text'
                                                        "
                                                    >
                                                        <label
                                                            :for="sousField.nom"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            {{ sousField.nom }}
                                                        </label>
                                                        <div
                                                            class="flex mt-1 rounded-md"
                                                        >
                                                            <TextInput
                                                                type="text"
                                                                v-model="
                                                                    bookingForm
                                                                        .sousattributs[
                                                                        sousField
                                                                            .id
                                                                    ]
                                                                "
                                                                :name="
                                                                    sousField.nom
                                                                "
                                                                :id="
                                                                    sousField.nom
                                                                "
                                                                class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                                placeholder=""
                                                                autocomplete="none"
                                                            />
                                                        </div>
                                                    </div>

                                                    <!-- number text -->
                                                    <div
                                                        class="w-full min-w-max"
                                                        v-if="
                                                            sousField.type_champ_form ===
                                                            'number'
                                                        "
                                                    >
                                                        <label
                                                            :for="sousField.nom"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            {{ sousField.nom }}
                                                        </label>
                                                        <div
                                                            class="flex mt-1 rounded-md"
                                                        >
                                                            <TextInput
                                                                type="number"
                                                                v-model="
                                                                    bookingForm
                                                                        .sousattributs[
                                                                        sousField
                                                                            .id
                                                                    ]
                                                                "
                                                                :name="
                                                                    sousField.nom
                                                                "
                                                                :id="
                                                                    sousField.nom
                                                                "
                                                                class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                                placeholder=""
                                                                autocomplete="none"
                                                            />
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="my-4"
                                    v-if="
                                        selectedProduit &&
                                        selectedProduit.plannings &&
                                        selectedProduit.plannings.length > 0
                                    "
                                >
                                    <div
                                        class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3"
                                    >
                                        <label
                                            v-for="planning in selectedProduit.plannings"
                                            :key="planning.id"
                                            :for="planning.id"
                                            class="flex w-full p-3 text-sm border border-gray-400 rounded-lg shadow-sm bg-blue-50 focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <span class="text-sm text-gray-700"
                                                >{{
                                                    formatDate(planning.start)
                                                }}
                                                au
                                                {{
                                                    formatDate(planning.end)
                                                }}</span
                                            >
                                            <input
                                                v-model="bookingForm.plannings"
                                                :id="planning.id"
                                                :name="planning.id"
                                                :value="planning.id"
                                                type="checkbox"
                                                class="ms-auto mt-0.5 shrink-0 rounded border-gray-200 text-blue-600 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50"
                                            />
                                        </label>
                                    </div>
                                </div>
                                <div
                                    class="flex items-center justify-between w-full mt-4"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                        @click.prevent="emit('close')"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        :disabled="bookingForm.processing"
                                        :class="{
                                            'opacity-25':
                                                bookingForm.processing,
                                        }"
                                        type="submit"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG
                                            v-if="bookingForm.processing"
                                        />
                                        Envoyer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
