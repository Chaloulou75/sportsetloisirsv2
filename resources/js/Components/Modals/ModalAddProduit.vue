<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, onMounted, defineAsyncComponent } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
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
    structureActivite: Object,
    show: Boolean,
    filteredCriteres: Object,
    latestAdresseId: Number,
});

onMounted(() => {
    const startDate = new Date();
    const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
    formAddProduit.date = [startDate, endDate];

    const startTime = {
        hours: 10,
        minutes: 0,
    };
    const endTime = {
        hours: 20,
        minutes: 0,
    };
    formAddProduit.time = [startTime, endTime];

    props.filteredCriteres.forEach((critere) => {
        if (
            critere.type_champ_form === "select" &&
            critere.valeurs.length > 0
        ) {
            formAddProduit.criteres[critere.id] = critere.valeurs[0].valeur;
        }
    });
});

const addProduitAddress = ref(false);

const updateSelectedCheckboxes = (critereId, optionValue, checked) => {
    if (checked) {
        if (!formAddProduit.criteres[critereId]) {
            // If the critereId doesn't exist in the form object, create a new array with the optionValue
            formAddProduit.criteres[critereId] = [optionValue];
        } else {
            // If the critereId exists, push the optionValue to the existing array
            formAddProduit.criteres[critereId].push(optionValue);
        }
    } else {
        // Remove the optionValue from the array
        const index = formAddProduit.criteres[critereId].indexOf(optionValue);
        if (index !== -1) {
            formAddProduit.criteres[critereId].splice(index, 1);
        }
    }
};

// Check if a checkbox is selected
const isCheckboxSelected = (critereId, optionValue) => {
    return (
        formAddProduit.criteres[critereId] &&
        formAddProduit.criteres[critereId].includes(optionValue)
    );
};

const formAddProduit = useForm({
    structure_id: ref(props.structure.id),
    discipline_id: ref(null),
    categorie_id: ref(null),
    criteres: ref([]),
    adresse: ref(props.latestAdresseId),
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
    date: ref(null),
    time: ref(null),
});

const onSubmitAddProduitForm = () => {
    router.post(
        `/structures/${props.structure.slug}/activites/${props.structureActivite.id}/produits`,
        {
            structure_id: formAddProduit.structure_id,
            discipline_id: props.structureActivite.discipline_id,
            categorie_id: props.structureActivite.categorie_id,
            criteres: formAddProduit.criteres,
            adresse: formAddProduit.adresse,
            address: formAddProduit.address,
            city: formAddProduit.city,
            zip_code: formAddProduit.zip_code,
            country: formAddProduit.country,
            address_lat: formAddProduit.address_lat,
            address_lng: formAddProduit.address_lng,
            date: formAddProduit.date,
            time: formAddProduit.time,
        },
        {
            preserveScroll: true,
            remember: false,
            onSuccess: () => {
                formAddProduit.reset();
                emit("close");
            },
            structure: props.structure.slug,
            activite: props.structureActivite.id,
        }
    );
};
</script>
<template>
    <div>
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

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex h-5/6 min-h-full items-center justify-center p-4 text-center"
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
                                class="min-h-full w-full max-w-6xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
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
                                            Ajouter un produit à
                                            <span class="text-blue-700">
                                                {{
                                                    structureActivite.titre
                                                }}</span
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
                                            <div
                                                class="flex flex-col space-y-3"
                                            >
                                                <!-- Criteres -->
                                                <div
                                                    v-if="
                                                        filteredCriteres.length >
                                                        0
                                                    "
                                                    class="flex w-full flex-col items-center justify-between space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0"
                                                >
                                                    <div
                                                        v-for="critere in filteredCriteres"
                                                        :key="critere.id"
                                                        class="w-full"
                                                    >
                                                        <!-- select -->
                                                        <div
                                                            v-if="
                                                                critere.type_champ_form ===
                                                                'select'
                                                            "
                                                        >
                                                            <div>
                                                                <label
                                                                    :for="
                                                                        critere.nom
                                                                    "
                                                                    class="block text-sm font-medium text-gray-700"
                                                                >
                                                                    {{
                                                                        critere.nom
                                                                    }}
                                                                </label>
                                                                <div
                                                                    class="mt-1 flex rounded-md"
                                                                >
                                                                    <select
                                                                        :name="
                                                                            critere.nom
                                                                        "
                                                                        :id="
                                                                            critere.nom
                                                                        "
                                                                        v-model="
                                                                            formAddProduit
                                                                                .criteres[
                                                                                critere
                                                                                    .id
                                                                            ]
                                                                        "
                                                                        class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                                    >
                                                                        <option
                                                                            disabled
                                                                            value=""
                                                                        >
                                                                            Selectionner
                                                                            un
                                                                            {{
                                                                                critere.nom
                                                                            }}
                                                                        </option>
                                                                        <option
                                                                            v-for="(
                                                                                option,
                                                                                index
                                                                            ) in critere.valeurs"
                                                                            :key="
                                                                                option.id
                                                                            "
                                                                            :value="
                                                                                option.valeur
                                                                            "
                                                                        >
                                                                            {{
                                                                                option.valeur
                                                                            }}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- checkbox -->
                                                        <div
                                                            v-if="
                                                                critere.type_champ_form ===
                                                                'checkbox'
                                                            "
                                                        >
                                                            <div class="block">
                                                                <span
                                                                    class="text-sm font-medium text-gray-700"
                                                                    >{{
                                                                        critere.nom
                                                                    }}</span
                                                                >
                                                                <div
                                                                    class="mt-2"
                                                                >
                                                                    <div
                                                                        v-for="(
                                                                            option,
                                                                            index
                                                                        ) in critere.valeurs"
                                                                        :key="
                                                                            option.id
                                                                        "
                                                                    >
                                                                        <label
                                                                            class="inline-flex items-center"
                                                                            :for="
                                                                                option.valeur
                                                                            "
                                                                        >
                                                                            <input
                                                                                :checked="
                                                                                    isCheckboxSelected(
                                                                                        critere.id,
                                                                                        option.valeur
                                                                                    )
                                                                                "
                                                                                @change="
                                                                                    updateSelectedCheckboxes(
                                                                                        critere.id,
                                                                                        option.valeur,
                                                                                        $event
                                                                                            .target
                                                                                            .checked
                                                                                    )
                                                                                "
                                                                                :id="
                                                                                    option.valeur
                                                                                "
                                                                                :value="
                                                                                    option.valeur
                                                                                "
                                                                                :name="
                                                                                    option.valeur
                                                                                "
                                                                                type="checkbox"
                                                                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                                                                            />
                                                                            <span
                                                                                class="ml-2 text-sm font-medium text-gray-700"
                                                                                >{{
                                                                                    option.valeur
                                                                                }}</span
                                                                            >
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- radio -->
                                                        <div
                                                            v-if="
                                                                critere.type_champ_form ===
                                                                'radio'
                                                            "
                                                        >
                                                            <label
                                                                :for="
                                                                    critere.nom
                                                                "
                                                                class="block text-sm font-medium text-gray-700"
                                                            >
                                                                {{
                                                                    critere.nom
                                                                }}
                                                            </label>

                                                            <div
                                                                class="mt-1 flex rounded-md"
                                                            >
                                                                <div>
                                                                    <label
                                                                        class="inline-flex items-center"
                                                                        v-for="(
                                                                            option,
                                                                            index
                                                                        ) in critere.valeurs"
                                                                        :key="
                                                                            option.id
                                                                        "
                                                                    >
                                                                        <input
                                                                            v-model="
                                                                                formAddProduit
                                                                                    .criteres[
                                                                                    critere
                                                                                        .id
                                                                                ]
                                                                            "
                                                                            type="radio"
                                                                            class="form-radio"
                                                                            :name="
                                                                                option.valeur
                                                                            "
                                                                            :value="
                                                                                option.valeur
                                                                            "
                                                                            checked
                                                                        />
                                                                        <span
                                                                            class="ml-2"
                                                                            >{{
                                                                                option.valeur
                                                                            }}</span
                                                                        >
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- input text -->
                                                        <div
                                                            v-if="
                                                                critere.type_champ_form ===
                                                                'text'
                                                            "
                                                        >
                                                            <label
                                                                :for="
                                                                    critere.nom
                                                                "
                                                                class="block text-sm font-medium text-gray-700"
                                                            >
                                                                {{
                                                                    critere.nom
                                                                }}
                                                            </label>
                                                            <div
                                                                class="mt-1 flex rounded-md"
                                                            >
                                                                <input
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
                                                    </div>
                                                </div>

                                                <!-- Adresse -->
                                                <div
                                                    class="flex w-full flex-col items-end justify-between space-x-0 space-y-2 md:flex-row md:space-x-4 md:space-y-0"
                                                >
                                                    <div
                                                        v-if="
                                                            !addProduitAddress
                                                        "
                                                        class="flex-1"
                                                    >
                                                        <label
                                                            for="adresse"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Adresse
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md"
                                                        >
                                                            <select
                                                                name="adresse"
                                                                id="adresse"
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
                                                                addProduitAddress
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

                                                <!-- newAddress -->
                                                <AddressForm
                                                    v-if="addProduitAddress"
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

                                                <!-- Jours et Heures -->
                                                <div
                                                    class="flex w-full flex-col items-center justify-between space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0"
                                                >
                                                    <div class="w-full">
                                                        <label
                                                            for="date"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Dates d'ouvertures
                                                        </label>
                                                        <VueDatePicker
                                                            v-model="
                                                                formAddProduit.date
                                                            "
                                                            range
                                                            multi-calendars
                                                            locale="fr"
                                                            :format="'dd/MM/yyyy'"
                                                            :enableTimePicker="
                                                                false
                                                            "
                                                            cancelText="annuler"
                                                            selectText="confirmer"
                                                            placeholder="Selectionner vos dates"
                                                        ></VueDatePicker>
                                                    </div>

                                                    <div class="w-full">
                                                        <label
                                                            for="time"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Horaires (ouverture
                                                            / fermeture)
                                                        </label>
                                                        <VueDatePicker
                                                            v-model="
                                                                formAddProduit.time
                                                            "
                                                            time-picker
                                                            range
                                                            locale="fr"
                                                            cancelText="annuler"
                                                            selectText="confirmer"
                                                            placeholder="Selectionnez vos horaires"
                                                        />
                                                    </div>
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
                                            @click="emit('close')"
                                        >
                                            Annuler
                                        </button>
                                        <button
                                            :disabled="
                                                formAddProduit.processing
                                            "
                                            type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2"
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
    </div>
</template>
