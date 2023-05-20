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

const emit = defineEmits("close");

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

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

const editProduitAddress = ref(false);

const formEditProduit = useForm({
    structure_id: ref(props.structure.id),
    discipline_id: ref(),
    categorie_id: ref(),
    criteres: ref([]),
    adresse: ref(),
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
    date: ref(null),
    time: ref(null),
});

const onSubmitEditProduitForm = () => {
    router.post(
        `/structures/${props.structure.slug}/activites/${props.structureActivite.id}/produits/produits/${produit.id}`,
        {
            _method: "put",
            structure_id: formEditProduit.structure_id,
            discipline_id: formEditProduit.discipline_id,
            categorie_id: formEditProduit.categorie_id,
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
        },
        {
            preserveScroll: true,
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
                                    @submit.prevent="onSubmitEditProduitForm()"
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
                                            Modifier un produit à
                                            <span class="text-blue-700">
                                                {{
                                                    structureActivite.titre
                                                }}</span
                                            >
                                        </h3>
                                        <button type="button">
                                            <XCircleIcon
                                                @click="emit('close')"
                                                class="h-6 w-6 text-gray-600 hover:text-gray-800"
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
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <input
                                                                    v-model="
                                                                        formEditProduit
                                                                            .criteres[
                                                                            critere
                                                                                .id
                                                                        ]
                                                                    "
                                                                    :id="
                                                                        critere.nom
                                                                    "
                                                                    type="checkbox"
                                                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                                                                />
                                                                <label
                                                                    :for="
                                                                        critere.nom
                                                                    "
                                                                    class="ml-2 text-sm font-medium text-gray-700"
                                                                    >{{
                                                                        critere.nom
                                                                    }}</label
                                                                >
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
                                                        v-if="
                                                            !editProduitAddress
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
                                                            Horaires (ouverture
                                                            / fermeture)
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
                                            :disabled="
                                                formEditProduit.processing
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
