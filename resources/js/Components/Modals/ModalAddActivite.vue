<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch, onMounted, computed, defineAsyncComponent } from "vue";
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
    discipline: Object,
    criteres: Object,
    categorie: Object,
    show: Boolean,
});

const addAddress = ref(false);
const latestAdresseId = computed(() => {
    if (props.structure.adresses.length > 0) {
        const latestAdresse = props.structure.adresses[0];
        return latestAdresse.id;
    }
    return null; // Return a default value if there are no adresses
});

const filteredCriteres = computed(() => {
    return props.criteres.filter(
        (critere) => critere.categorie_id === props.categorie.id
    );
});

const form = useForm({
    structure_id: ref(props.structure.id),
    discipline_id: ref(props.discipline.id),
    categorie_id: ref(props.categorie.id),
    titre: ref(null),
    description: ref(null),
    image: ref(null),
    actif: ref(1),
    criteres: ref({}),
    adresse: ref(latestAdresseId.value),
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
    date: ref(null),
    time: ref(null),
});

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

watch(
    filteredCriteres,
    (newFilteredCriteres) => {
        newFilteredCriteres.forEach((critere) => {
            if (
                critere.type_champ_form === "select" &&
                critere.valeurs.length > 0
            ) {
                form.criteres[critere.id] = critere.valeurs[0].valeur;
            }
        });
    },
    { immediate: true }
);

function onSubmit() {
    router.post(
        `/structures/${props.structure.slug}/activites/${props.activite.id}/newactivitystore`,
        {
            structure_id: form.structure_id,
            discipline_id: form.discipline_id,
            categorie_id: props.categorie.id,
            titre: form.titre,
            description: form.description,
            image: form.image,
            actif: form.actif,
            criteres: form.criteres,
            adresse: form.adresse,
            address: form.address,
            city: form.city,
            zip_code: form.zip_code,
            country: form.country,
            address_lat: form.address_lat,
            address_lng: form.address_lng,
            date: form.date,
            time: form.time,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                emit("close");
            },
            structure: props.structure.slug,
            activite: props.activite.id,
        }
    );
}

onMounted(() => {
    const startDate = new Date();
    const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
    form.date = [startDate, endDate];

    const startTime = {
        hours: 10,
        minutes: 0,
    };
    const endTime = {
        hours: 20,
        minutes: 0,
    };
    form.time = [startTime, endTime];
});
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
                                            Ajouter une activité
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
                                            <!-- image -->
                                            <div>
                                                <label
                                                    for="image"
                                                    class="block text-sm font-medium text-gray-700"
                                                    >Ajouter ou modifier la
                                                    photo ou l'image:</label
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
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        v-model="form.titre"
                                                        type="text"
                                                        name="titre"
                                                        id="titre"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                        :placeholder="`${categorie.nom_categorie_pro} de ${activite.discipline.name}`"
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
                                                        v-model="
                                                            form.description
                                                        "
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
                                                                        form
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
                                                                            form
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
                                                                    form
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
                                                        >Ajouter une
                                                        adresse</label
                                                    >
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

                                            <!-- Jours et Heures -->
                                            <div
                                                class="flex w-full flex-col items-center justify-between space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0"
                                            >
                                                <div class="z-10 w-full">
                                                    <label
                                                        for="date"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Dates d'ouvertures
                                                    </label>
                                                    <VueDatePicker
                                                        v-model="form.date"
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
                                                    >
                                                    </VueDatePicker>
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
                                                        v-model="form.time"
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
    </div>
</template>
