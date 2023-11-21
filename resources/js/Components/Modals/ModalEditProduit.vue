<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch, onMounted, defineAsyncComponent } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
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

onMounted(() => {
    const startDate = new Date();
    const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
    formEditProduit.date = [startDate, endDate];

    const startTime = {
        hours: 10,
        minutes: 0,
    };
    const endTime = {
        hours: 20,
        minutes: 0,
    };
    formEditProduit.time = [startTime, endTime];
});

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const editProduitAddress = ref(false);

watch(
    () => props.produit,
    (newValue) => {
        if (newValue) {
            formEditProduit.adresse = newValue.lieu_id;
            formEditProduit.actif = newValue.actif;
            formEditProduit.criteres = {};
            newValue.criteres.forEach((critere) => {
                const critereId = critere.critere_id;
                const critereValue = critere.valeur;

                if (Array.isArray(critereValue)) {
                    // For checkboxes with multiple values
                    if (!Array.isArray(formEditProduit.criteres[critereId])) {
                        // If the critereId doesn't exist in the form object, create a new array with the critereValue
                        formEditProduit.criteres[critereId] =
                            critereValue.slice();
                    } else {
                        // If the critereId exists, append the critereValue to the existing array
                        formEditProduit.criteres[critereId].push(
                            ...critereValue
                        );
                    }
                } else {
                    // For other types of fields
                    if (!formEditProduit.criteres[critereId]) {
                        // If the critereId doesn't exist in the form object, assign the critereValue as a single value
                        formEditProduit.criteres[critereId] = critereValue;
                    } else {
                        // If the critereId exists, convert the existing value to an array and push the new critereValue
                        const existingValue =
                            formEditProduit.criteres[critereId];
                        if (!Array.isArray(existingValue)) {
                            formEditProduit.criteres[critereId] = [
                                existingValue,
                            ];
                        }
                        formEditProduit.criteres[critereId].push(critereValue);
                    }
                }
            });
        }
    }
);

const updateSelectedCheckboxes = (critereId, optionValue, checked) => {
    if (checked) {
        if (!Array.isArray(formEditProduit.criteres[critereId])) {
            formEditProduit.criteres[critereId] = [
                formEditProduit.criteres[critereId],
            ];
        }
        formEditProduit.criteres[critereId].push(optionValue);
    } else {
        if (Array.isArray(formEditProduit.criteres[critereId])) {
            const index =
                formEditProduit.criteres[critereId].indexOf(optionValue);
            if (index !== -1) {
                formEditProduit.criteres[critereId].splice(index, 1);
            }
        }
    }
};

const isCheckboxSelected = (critereId, optionValue) => {
    return (
        formEditProduit.criteres[critereId] &&
        formEditProduit.criteres[critereId].includes(optionValue)
    );
};

const formEditProduit = useForm({
    actif: null,
    criteres: {},
    souscriteres: {},
    adresse: props.latestAdresseId,
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

const onSubmitEditProduitForm = () => {
    router.put(
        route("structures.activites.produits.update", {
            structure: props.structure.slug,
            activite: props.structureActivite.id,
            produit: props.produit.id,
        }),
        {
            _method: "put",
            criteres: formEditProduit.criteres,
            souscriteres: formEditProduit.souscriteres,
            adresse: formEditProduit.adresse,
            address: formEditProduit.address,
            city: formEditProduit.city,
            zip_code: formEditProduit.zip_code,
            country: formEditProduit.country,
            address_lat: formEditProduit.address_lat,
            address_lng: formEditProduit.address_lng,
            date: formEditProduit.date,
            time: formEditProduit.time,
            actif: formEditProduit.actif,
        },
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

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex h-screen min-h-full items-center justify-center p-4 text-center"
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
                            class="min-h-full w-full max-w-6xl transform rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
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
                                        Modifier un produit à
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
                                                                        formEditProduit
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
                                                            <div class="mt-2">
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
                                                            :for="critere.nom"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            {{ critere.nom }}
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
                                                                            formEditProduit
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
                                                            :for="critere.nom"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            {{ critere.nom }}
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md"
                                                        >
                                                            <input
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
                                                </div>
                                            </div>

                                            <!-- Adresse -->
                                            <div
                                                class="flex w-full flex-col items-end justify-between space-x-0 space-y-2 md:flex-row md:space-x-4 md:space-y-0"
                                            >
                                                <div
                                                    v-if="!editProduitAddress"
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
                                                <div class="flex items-center">
                                                    <input
                                                        v-model="
                                                            editProduitAddress
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
                                                v-if="editProduitAddress"
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
                                                        class="z-20"
                                                        v-model="
                                                            formEditProduit.date
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
                                                        Horaires (ouverture /
                                                        fermeture)
                                                    </label>
                                                    <VueDatePicker
                                                        v-model="
                                                            formEditProduit.time
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
