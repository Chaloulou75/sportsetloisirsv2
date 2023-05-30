<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch, computed, onMounted, defineAsyncComponent } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
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
    filteredCriteres: Object,
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
                formEditProduit.criteres[critere.critere_id] = critere.valeur;
            });
        }
    }
);

const formEditProduit = useForm({
    criteres: ref([]),
    adresse: ref(null),
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
    date: ref(null),
    time: ref(null),
    actif: ref(null),
});

const onSubmitEditProduitForm = () => {
    router.put(
        `/structures/${props.structure.slug}/activites/${props.structureActivite.id}/produits/${props.produit.id}`,
        {
            _method: "put",
            criteres: formEditProduit.criteres,
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
            structure: props.structure.slug,
            activite: props.structureActivite.id,
            produit: props.produit.id,
        }
    );
};
</script>
<template>
    <div>
        <TransitionRoot appear :show="show" as="template">
            <Dialog as="div" @close="open = false" class="relative z-10">
                <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                    leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-800 bg-opacity-50" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex items-center justify-center min-h-full p-4 text-center h-5/6">
                        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95">
                            <DialogPanel
                                class="w-full max-w-6xl min-h-full p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                                <form @submit.prevent="onSubmitEditProduitForm()" autocomplete="off">
                                    <DialogTitle as="div" class="flex items-center justify-between w-full">
                                        <h3 class="text-lg font-medium leading-6 text-gray-800">
                                            Modifier un produit à
                                            <span class="text-blue-700">
                                                {{
                                                    structureActivite.titre
                                                }}</span>
                                        </h3>
                                        <button type="button">
                                            <XCircleIcon @click="emit('close')"
                                                class="w-6 h-6 text-gray-600 hover:text-gray-800" />
                                        </button>
                                    </DialogTitle>
                                    <div class="w-full mt-2">
                                        <div class="flex flex-col space-y-3">
                                            <div class="flex flex-col space-y-3">
                                                <div class="flex items-center space-x-2">
                                                    <Switch v-model="formEditProduit.actif
                                                        " :class="formEditProduit.actif
            ? 'bg-green-600'
            : 'bg-gray-200'
        "
                                                        class="relative inline-flex items-center h-6 rounded-full w-11">
                                                        <span class="sr-only">Actif</span>
                                                        <span :class="formEditProduit.actif
                                                                ? 'translate-x-6'
                                                                : 'translate-x-1'
                                                            "
                                                            class="inline-block w-4 h-4 transition transform bg-white rounded-full" />
                                                    </Switch>
                                                    <p class="text-lg font-semibold text-green-600" v-if="formEditProduit.actif
                                                        ">
                                                        Actif
                                                    </p>
                                                    <p class="text-lg font-semibold text-gray-600" v-else>
                                                        Inactif
                                                    </p>
                                                </div>
                                                <!-- Criteres -->
                                                <div v-if="filteredCriteres.length >
                                                    0
                                                    "
                                                    class="flex flex-col items-center justify-between w-full space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0">
                                                    <div v-for="critere in filteredCriteres" :key="critere.id"
                                                        class="w-full">
                                                        <!-- select -->
                                                        <div v-if="critere.type_champ_form ===
                                                            'select'
                                                            ">
                                                            <div>
                                                                <label :for="critere.nom
                                                                    " class="block text-sm font-medium text-gray-700">
                                                                    {{
                                                                        critere.nom
                                                                    }}
                                                                </label>
                                                                <div class="flex mt-1 rounded-md">
                                                                    <select :name="critere.nom
                                                                            " :id="critere.nom
            " v-model="formEditProduit
            .criteres[
        critere
            .id
        ]
        "
                                                                        class="block w-full text-sm text-gray-800 border-gray-300 rounded-lg shadow-sm">
                                                                        <option disabled value="">
                                                                            Selectionner
                                                                            un
                                                                            {{
                                                                                critere.nom
                                                                            }}
                                                                        </option>
                                                                        <option v-for="(
                                                                                option,
                                                                                        index
                                                                            ) in critere.valeurs" :key="option.id
                                                                                " :value="option.valeur
        ">
                                                                            {{
                                                                                option.valeur
                                                                            }}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- checkbox -->
                                                        <div v-if="critere.type_champ_form ===
                                                            'checkbox'
                                                            ">
                                                            <div class="flex items-center">
                                                                <input v-model="formEditProduit
                                                                        .criteres[
                                                                    critere
                                                                        .id
                                                                    ]
                                                                    " :id="critere.nom
        " type="checkbox"
                                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600" />
                                                                <label :for="critere.nom
                                                                    " class="ml-2 text-sm font-medium text-gray-700">{{
        critere.nom
    }}</label>
                                                            </div>
                                                        </div>
                                                        <!-- radio -->
                                                        <div v-if="critere.type_champ_form ===
                                                                'radio'
                                                                ">
                                                            <label :for="critere.nom
                                                                " class="block text-sm font-medium text-gray-700">
                                                                {{
                                                                    critere.nom
                                                                }}
                                                            </label>

                                                            <div class="flex mt-1 rounded-md">
                                                                <div>
                                                                    <label class="inline-flex items-center" v-for="(
                                                                            option,
                                                                                    index
                                                                        ) in critere.valeurs" :key="option.id
                                                                            ">
                                                                        <input v-model="formEditProduit
                                                                                .criteres[
                                                                            critere
                                                                                .id
                                                                            ]
                                                                            " type="radio" class="form-radio" :name="option.valeur
        " :value="option.valeur
        " checked />
                                                                        <span class="ml-2">{{
                                                                            option.valeur
                                                                        }}</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- input text -->
                                                        <div v-if="critere.type_champ_form ===
                                                            'text'
                                                            ">
                                                            <label :for="critere.nom
                                                                " class="block text-sm font-medium text-gray-700">
                                                                {{
                                                                    critere.nom
                                                                }}
                                                            </label>
                                                            <div class="flex mt-1 rounded-md">
                                                                <input type="text" v-model="formEditProduit
                                                                            .criteres[
                                                                        critere
                                                                            .id
                                                                        ]
                                                                        " :name="critere.nom
            " :id="critere.nom
        "
                                                                    class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                                    placeholder="" autocomplete="none" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Adresse -->
                                                <div
                                                    class="flex flex-col items-end justify-between w-full space-x-0 space-y-2 md:flex-row md:space-x-4 md:space-y-0">
                                                    <div v-if="!editProduitAddress
                                                        " class="flex-1">
                                                        <label for="adresse"
                                                            class="block text-sm font-medium text-gray-700">
                                                            Adresse
                                                        </label>
                                                        <div class="flex mt-1 rounded-md">
                                                            <select name="adresse" id="adresse" v-model="formEditProduit.adresse
                                                                "
                                                                class="block w-full text-sm text-gray-800 border-gray-300 rounded-lg shadow-sm">
                                                                <option v-for="adresse in structure.adresses" :key="adresse.id
                                                                    " :value="adresse.id
        ">
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
                                                        <input v-model="editProduitAddress
                                                            " id="addAddress" type="checkbox"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded form-checkbox focus:ring-blue-500" />
                                                        <label for="addAddress"
                                                            class="ml-2 text-sm font-medium text-gray-700">Ajouter une
                                                            adresse</label>
                                                    </div>
                                                </div>

                                                <!-- newAddress -->
                                                <AddressForm v-if="editProduitAddress" :errors="errors" v-model:address="formEditProduit.address
                                                    " v-model:city="formEditProduit.city
        " v-model:zip_code="formEditProduit.zip_code
        " v-model:country="formEditProduit.country
        " v-model:address_lat="formEditProduit.address_lat
        " v-model:address_lng="formEditProduit.address_lng
        " />

                                                <!-- Jours et Heures -->
                                                <div
                                                    class="flex flex-col items-center justify-between w-full space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0">
                                                    <div class="w-full">
                                                        <label for="date" class="block text-sm font-medium text-gray-700">
                                                            Dates d'ouvertures
                                                        </label>
                                                        <VueDatePicker v-model="formEditProduit.date
                                                            " range multi-calendars locale="fr" :format="'dd/MM/yyyy'"
                                                            :enableTimePicker="false
                                                                " cancelText="annuler" selectText="confirmer"
                                                            placeholder="Selectionner vos dates"></VueDatePicker>
                                                    </div>

                                                    <div class="w-full">
                                                        <label for="time" class="block text-sm font-medium text-gray-700">
                                                            Horaires (ouverture
                                                            / fermeture)
                                                        </label>
                                                        <VueDatePicker v-model="formEditProduit.time
                                                            " time-picker range locale="fr" cancelText="annuler"
                                                            selectText="confirmer"
                                                            placeholder="Selectionnez vos horaires" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between w-full mt-4">
                                        <button type="button"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                            @click="emit('close')">
                                            Annuler
                                        </button>
                                        <button :disabled="formEditProduit.processing
                                            " type="submit"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2">
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
