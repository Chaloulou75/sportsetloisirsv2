<script setup>
import { ref, computed, onMounted } from "vue";
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

const emit = defineEmits(["close"]);

const props = defineProps({
    produits: Object,
    produitId: Number,
    catTarifId: Number,
    show: Boolean,
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

    console.log(produit);

    // Filter catTarifs of the found produit based on catTarifId
    return produit.cat_tarifs.filter(
        (catTarif) => catTarif.id === props.catTarifId
    );
});

const formReservation = useForm({
    attributs: [],
    sousattributs: [],
});

const updateSelectedCheckboxes = (fieldId, optionValue, checked) => {
    const selectedValue = formReservation.attributs[fieldId];

    if (checked) {
        if (!Array.isArray(selectedValue)) {
            formReservation.attributs[fieldId] = ref([optionValue]);
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
            formReservation.attributs[fieldId] &&
            formReservation.attributs[fieldId].includes(optionValue)
        );
    };
});

const onSubmit = () => {};
</script>
<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="open = false" class="relative z-[1199]">
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
            <div
                class="fixed inset-0 flex w-screen items-center justify-center p-4 text-center"
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
                        class="w-full max-w-5xl transform space-y-5 overflow-visible rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                    >
                        <DialogTitle
                            as="div"
                            class="flex w-full items-center justify-between"
                        >
                            <h3
                                class="text-lg font-medium leading-6 text-gray-800"
                            >
                                Formulaire de réservation
                            </h3>
                            <button type="button">
                                <XCircleIcon
                                    @click="emit('close')"
                                    class="h-6 w-6 text-gray-600 hover:text-red-600"
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
                                            class="mx-auto grid w-full grid-cols-1 gap-4 md:grid-cols-3"
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
                                                        formReservation
                                                            .attributs[field.id]
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
                                                    :name="field.nom"
                                                    v-model="
                                                        formReservation
                                                            .attributs[field.id]
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
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <TextInput
                                                            type="text"
                                                            v-model="
                                                                formReservation
                                                                    .attributs[
                                                                    field.id
                                                                ]
                                                            "
                                                            :name="field.nom"
                                                            :id="field.nom"
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
                                                        class="mt-1 flex rounded-md"
                                                    >
                                                        <TextInput
                                                            type="number"
                                                            v-model="
                                                                formReservation
                                                                    .attributs[
                                                                    field.id
                                                                ]
                                                            "
                                                            :name="field.nom"
                                                            :id="field.nom"
                                                            class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                            placeholder=""
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                </div>
                                                <!-- sous fields -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="mt-4 flex w-full items-center justify-between"
                                >
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                        @click.prevent="emit('close')"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
                                    >
                                        <LoadingSVG />
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
