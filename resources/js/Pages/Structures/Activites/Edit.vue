<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ref, watch, computed, onMounted, defineAsyncComponent } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import ModalAddTarif from "@/Components/Modals/ModalAddTarif.vue";
import { PlusIcon, XCircleIcon } from "@heroicons/vue/24/outline";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
    TabGroup,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
} from "@headlessui/vue";

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const ActivityDisplay = defineAsyncComponent(() =>
    import("@/Components/Inscription/Activity/ActivityDisplay.vue")
);

const props = defineProps({
    errors: Object,
    structure: Object,
    activite: Object,
    structureActivites: Object,
    criteres: Object,
    categoriesListByDiscipline: Object,
    tarifTypes: Object,
    activiteForTarifs: Object,
    can: Object,
});

const selectedCategoryId = ref(props.activite.categorie_id);

const addAddress = ref(false);

const isOpen = ref(false);

const openModal = () => {
    isOpen.value = true;
};

const closeModal = () => {
    isOpen.value = false;
};

const showAddTarifModal = ref(false);

const openAddTarifModal = (structure) => {
    showAddTarifModal.value = true;
};

onMounted(() => {
    selectedCategoryId.value = props.activite.categorie_id;

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

const defaultTabIndex = computed(() => {
    return props.categoriesListByDiscipline.findIndex(
        (categorie) => categorie.id === selectedCategoryId.value
    );
});

const filteredActivites = computed(() => {
    if (!selectedCategoryId.value) {
        return [];
    }
    return props.structureActivites.filter(
        (activity) => activity.categorie_id === selectedCategoryId.value
    );
});

const filteredCriteres = computed(() => {
    return props.criteres.filter(
        (critere) => critere.categorie_id === selectedCategoryId.value
    );
});

const latestAdresseId = computed(() => {
    if (props.structure.adresses.length > 0) {
        const latestAdresse = props.structure.adresses[0];
        return latestAdresse.id;
    }
    return null; // Return a default value if there are no adresses
});

const form = useForm({
    structure_id: ref(props.activite.structure_id),
    discipline_id: ref(props.activite.discipline_id),
    categorie_id: ref(null),
    titre: ref(null),
    description: ref(null),
    image: ref(null),
    actif: ref(1),
    criteres: ref([]),
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
            categorie_id: selectedCategoryId.value,
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
                closeModal();
            },
            structure: props.structure.slug,
            activite: props.activite.id,
        }
    );
}
</script>

<template>
    <Head title="Modifier une activité" />

    <AppLayout>
        <template #header>
            <div class="flex flex-col items-start justify-between md:flex-row md:items-center">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Ajouter ou modifier votre activité
                        <span class="text-blue-700"></span>
                    </h2>
                </div>
                <div class="mt-4 w-full md:mt-0 md:w-1/4">
                    <div class="flex flex-col justify-between space-y-4 md:ml-4 md:space-y-6">
                        <Link :href="route('structures.activites.index', structure)
                            " v-if="can.update"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg">
                        Mes activités</Link>
                        <Link :href="route('structures.show', structure.slug)"
                            class="flex flex-col items-center justify-center overflow-hidden rounded bg-white px-4 py-2 text-center text-xs text-gray-600 shadow-lg transition duration-150 hover:bg-darkblue hover:text-white hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-lg">
                        Voir la structure</Link>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <TabGroup :defaultIndex="defaultTabIndex">
                    <section class="space-y-4 text-gray-700">
                        <div>
                            <h2 class="text-center text-3xl font-bold uppercase md:text-left">
                                {{ activite.discipline.name }}
                            </h2>
                            <!-- categories -->
                            <div class="my-4 w-full">
                                <div class="mt-1">
                                    <TabList
                                        class="flex w-full flex-col items-stretch justify-between divide-y divide-green-600 rounded-sm border border-gray-300 bg-white/20 px-3 py-2 shadow-md focus:border-indigo-500 focus:outline-none sm:text-base md:flex-row md:items-center md:divide-y-0">
                                        <Tab v-for="categorie in categoriesListByDiscipline" :key="categorie.id"
                                            as="template" v-slot="{ selected }" class="py-2" v-model="selectedCategoryId">
                                            <button @click="
                                                selectedCategoryId =
                                                categorie.id
                                                " :class="[
        'w-full px-2 py-3 text-sm font-medium leading-5 text-gray-700 ring-white ring-opacity-10 ring-offset-2 ring-offset-green-200 focus:outline-none focus:ring-2',
        selected
            ? 'bg-green-600 text-white'
            : 'text-gray-700 hover:bg-white/50 hover:text-gray-800',
    ]">
                                                {{ categorie.nom_categorie }}
                                            </button>
                                        </Tab>
                                    </TabList>
                                </div>
                            </div>
                        </div>
                    </section>

                    <TabPanels class="mx-auto max-w-7xl py-6 text-gray-700">
                        <TabPanel v-for="(
                                categorie, idx
                            ) in categoriesListByDiscipline" :key="categorie.id" class="flex flex-col space-y-4">
                            <!-- buttons -->
                            <div
                                class="flex flex-col items-start justify-start space-x-0 space-y-2 px-2 md:flex-row md:space-x-4 md:space-y-0 md:px-0">
                                <button @click="openModal" type="button"
                                    class="flex w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto">
                                    Ajouter
                                    {{ categorie.nom_categorie }}
                                    <PlusIcon class="ml-2 h-5 w-5" />
                                </button>
                                <button type="button"
                                    class="flex w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto">
                                    Voir le planning
                                </button>
                                <button type="button" @click="openAddTarifModal(structure)"
                                    class="flex w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto">
                                    Ajouter un tarif
                                </button>
                            </div>
                            <div
                                class="flex w-full flex-col items-center justify-between space-y-2 px-2 py-6 md:flex-row md:space-y-0 md:px-0">
                                <div class="text-center text-lg font-semibold text-gray-700 md:text-left">
                                    {{ categorie.nom_categorie }}
                                </div>
                                <button type="button" @click="openModal"
                                    class="hidden md:flex w-full items-center justify-between rounded-sm bg-green-600 px-4 py-3 text-lg text-white shadow-lg transition duration-150 hover:bg-white hover:text-gray-600 hover:ring-2 hover:ring-green-400 hover:ring-offset-2 focus:ring-2 focus:ring-green-400 focus:ring-offset-2 sm:rounded-sm md:w-auto">
                                    Ajouter
                                    <PlusIcon class="ml-2 h-5 w-5" />
                                </button>
                                <TransitionRoot appear :show="isOpen" as="template">
                                    <Dialog as="div" @close="closeModal" class="relative z-10">
                                        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0"
                                            enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100"
                                            leave-to="opacity-0">
                                            <div class="fixed inset-0 bg-black bg-opacity-50" />
                                        </TransitionChild>

                                        <div class="fixed inset-0 overflow-y-auto">
                                            <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                <TransitionChild as="template" enter="duration-300 ease-out"
                                                    enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100"
                                                    leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                                                    leave-to="opacity-0 scale-95">
                                                    <DialogPanel
                                                        class="w-full max-w-6xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                                        <form @submit.prevent="onSubmit
                                                            " enctype="multipart/form-data" autocomplete="off">
                                                            <DialogTitle as="div"
                                                                class="flex w-full items-center justify-between">
                                                                <h3 class="text-lg font-medium leading-6 text-gray-800">
                                                                    Ajouter une
                                                                    activité
                                                                </h3>
                                                                <button type="button">
                                                                    <XCircleIcon @click="closeModal
                                                                        "
                                                                        class="h-6 w-6 text-gray-600 hover:text-gray-800" />
                                                                </button>
                                                            </DialogTitle>
                                                            <div class="mt-2 w-full">
                                                                <div class="flex flex-col space-y-3">
                                                                    <!-- image -->
                                                                    <div>
                                                                        <label for="image"
                                                                            class="block text-sm font-medium text-gray-700">Ajouter
                                                                            ou
                                                                            modifier
                                                                            la
                                                                            photo
                                                                            ou
                                                                            l'image:</label>
                                                                        <input
                                                                            class="mt-1 text-sm text-gray-700 focus:outline-none"
                                                                            type="file" id="image" @input="
                                                                                form.image =
                                                                                $event.target.files[0]
                                                                                " />
                                                                        <span v-if="errors.image
                                                                            " class="mt-2 text-xs text-red-500">{{
        errors
            .image[0]
    }}</span>
                                                                    </div>
                                                                    <!-- titre -->
                                                                    <div>
                                                                        <label for="titre"
                                                                            class="block text-sm font-medium text-gray-700">
                                                                            Titre
                                                                            de
                                                                            l'activité
                                                                        </label>
                                                                        <div class="mt-1 flex rounded-md">
                                                                            <input v-model="form.titre
                                                                                    " type="text" name="titre"
                                                                                id="titre"
                                                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                                :placeholder="`${categorie.nom_categorie} de ${activite.discipline.name}`"
                                                                                autocomplete="none" />
                                                                        </div>
                                                                        <div v-if="errors.titre
                                                                            " class="mt-2 text-xs text-red-500">
                                                                            {{
                                                                                errors.titre
                                                                            }}
                                                                        </div>
                                                                    </div>
                                                                    <!-- description -->
                                                                    <div>
                                                                        <label for="description"
                                                                            class="block text-sm font-medium text-gray-700">
                                                                            Description
                                                                        </label>
                                                                        <div class="mt-1">
                                                                            <textarea v-model="form.description
                                                                                    " id="description"
                                                                                name="description" rows="2"
                                                                                class="mt-1 block h-32 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                                                :class="{
                                                                                    errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                                                }"
                                                                                placeholder="Presentez votre activité"
                                                                                autocomplete="none" />
                                                                        </div>
                                                                        <div v-if="errors.description
                                                                            " class="mt-2 text-xs text-red-500">
                                                                            {{
                                                                                errors.description
                                                                            }}
                                                                        </div>
                                                                    </div>

                                                                    <!-- Criteres -->
                                                                    <div v-if="filteredCriteres.length >
                                                                            0
                                                                            "
                                                                        class="flex w-full flex-col items-center justify-between space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0">
                                                                        <div v-for="critere in filteredCriteres" :key="critere.id
                                                                            " class="w-full">
                                                                            <!-- select -->
                                                                            <div v-if="critere.type_champ_form ===
                                                                                'select'
                                                                                ">
                                                                                <div>
                                                                                    <label :for="critere.nom
                                                                                        "
                                                                                        class="block text-sm font-medium text-gray-700">
                                                                                        {{
                                                                                            critere.nom
                                                                                        }}
                                                                                    </label>
                                                                                    <div class="mt-1 flex rounded-md">
                                                                                        <select :name="critere.nom
                                                                                                " :id="critere.nom
            " v-model="form
        .criteres[
        critere
            .id
    ]
        " class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm">
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
                                                                                                ) in critere.valeurs"
                                                                                                :key="option.id
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
                                                                                    <input v-model="form
                                                                                        .criteres[
                                                                                        critere
                                                                                            .id
                                                                                    ]
                                                                                        " :id="critere.nom
        " type="checkbox" class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600" />
                                                                                    <label :for="critere.nom
                                                                                        "
                                                                                        class="ml-2 text-sm font-medium text-gray-700">{{
                                                                                            critere.nom
                                                                                        }}</label>
                                                                                </div>
                                                                            </div>
                                                                            <!-- radio -->
                                                                            <div v-if="critere.type_champ_form ===
                                                                                    'radio'
                                                                                    ">
                                                                                <label :for="critere.nom
                                                                                    "
                                                                                    class="block text-sm font-medium text-gray-700">
                                                                                    {{
                                                                                        critere.nom
                                                                                    }}
                                                                                </label>

                                                                                <div class="mt-1 flex rounded-md">
                                                                                    <div>
                                                                                        <label
                                                                                            class="inline-flex items-center"
                                                                                            v-for="(
                                                                                                option,
                                                                                                        index
                                                                                            ) in critere.valeurs" :key="option.id
                                                                                                ">
                                                                                            <input v-model="form
                                                                                                .criteres[
                                                                                                critere
                                                                                                    .id
                                                                                            ]
                                                                                                " type="radio"
                                                                                                class="form-radio" :name="option.valeur
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
                                                                                    "
                                                                                    class="block text-sm font-medium text-gray-700">
                                                                                    {{
                                                                                        critere.nom
                                                                                    }}
                                                                                </label>
                                                                                <div class="mt-1 flex rounded-md">
                                                                                    <input type="text" v-model="form
                                                                                                .criteres[
                                                                                            critere
                                                                                                .id
                                                                                            ]
                                                                                            " :name="critere.nom
            " :id="critere.nom
        " class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                                        placeholder=""
                                                                                        autocomplete="none" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Adresse -->
                                                                    <div
                                                                        class="flex w-full items-end justify-between space-x-4">
                                                                        <div v-if="!addAddress
                                                                            " class="flex-1">
                                                                            <label for="
                                                                                    adresse
                                                                                "
                                                                                class="block text-sm font-medium text-gray-700">
                                                                                Adresse
                                                                            </label>
                                                                            <div class="mt-1 flex rounded-md">
                                                                                <select name="
                                                                                        adresse
                                                                                    " id="
                                                                                        adresse
                                                                                    " v-model="form.adresse
                                                                                        "
                                                                                    class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm">
                                                                                    <option
                                                                                        v-for="adresse in structure.adresses"
                                                                                        :key="adresse.id
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
                                                                            <input v-model="addAddress
                                                                                " id="addAddress" type="checkbox"
                                                                                class="form-checkbox h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500" />
                                                                            <label for="addAddress"
                                                                                class="ml-2 text-sm font-medium text-gray-700">Ajouter
                                                                                une
                                                                                adresse</label>
                                                                        </div>
                                                                    </div>

                                                                    <!-- newAddress -->
                                                                    <AddressForm v-if="addAddress
                                                                        " :errors="errors
        " v-model:address="form.address
        " v-model:city="form.city
        " v-model:zip_code="form.zip_code
        " v-model:country="form.country
        " v-model:address_lat="form.address_lat
        " v-model:address_lng="form.address_lng
        " />

                                                                    <!-- Jours et Heures -->
                                                                    <div
                                                                        class="flex w-full flex-col items-center justify-between space-x-0 space-y-2 md:flex-row md:space-x-6 md:space-y-0">
                                                                        <div class="z-10 w-full">
                                                                            <label for="date"
                                                                                class="block text-sm font-medium text-gray-700">
                                                                                Dates
                                                                                d'ouvertures
                                                                            </label>
                                                                            <VueDatePicker v-model="form.date
                                                                                " range multi-calendars locale="fr"
                                                                                :format="'dd/MM/yyyy'" :enableTimePicker="false
                                                                                    " cancelText="annuler"
                                                                                selectText="confirmer"
                                                                                placeholder="Selectionner vos dates">
                                                                            </VueDatePicker>
                                                                        </div>

                                                                        <div class="w-full">
                                                                            <label for="time"
                                                                                class="block text-sm font-medium text-gray-700">
                                                                                Horaires
                                                                                (ouverture
                                                                                /
                                                                                fermeture)
                                                                            </label>
                                                                            <VueDatePicker v-model="form.time
                                                                                " time-picker range locale="fr"
                                                                                cancelText="annuler" selectText="confirmer"
                                                                                placeholder="Selectionnez vos horaires" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-4 flex w-full items-center justify-between">
                                                                <button type="button"
                                                                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-normal text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                                                                    @click="closeModal
                                                                        ">
                                                                    Annuler
                                                                </button>
                                                                <button :disabled="form.processing
                                                                    " type="submit"
                                                                    class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-normal text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2">
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
                            <ActivityDisplay :errors="errors" :structure="structure" :structureActivites="filteredActivites"
                                :filteredCriteres="filteredCriteres" :latestAdresseId="latestAdresseId"
                                :tarif-types="tarifTypes" :activiteForTarifs="activiteForTarifs" />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>

                <div class="hidden sm:block" aria-hidden="true">
                    <div class="py-5">
                        <div class="border-t border-gray-200" />
                    </div>
                </div>
            </div>
        </div>
        <ModalAddTarif :errors="errors" :structure="structure" :tarif-types="tarifTypes"
            :activiteForTarifs="activiteForTarifs" :show="showAddTarifModal" @close="showAddTarifModal = false" />
    </AppLayout>
</template>

<style>
.vc-select select {
    background-image: unset;
}
</style>
