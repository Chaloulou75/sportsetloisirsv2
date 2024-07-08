<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, router, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted, defineAsyncComponent } from "vue";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";
import Checkbox from "@/Components/Forms/Checkbox.vue";
import Dropdown from "primevue/dropdown";
import TextInput from "@/Components/Forms/TextInput.vue";
import RadioButton from "primevue/radiobutton";
import {
    ArrowPathIcon,
    PlusIcon,
    MinusIcon,
    TrashIcon,
    ChevronLeftIcon,
} from "@heroicons/vue/24/outline";
import dayjs from "dayjs";
import "dayjs/locale/fr";
dayjs.locale("fr");

const MicroNavBackPro = defineAsyncComponent(() =>
    import("@/Components/Structures/MicroNavBackPro.vue")
);

const PermissionStructureUserForm = defineAsyncComponent(() =>
    import(
        "@/Components/Structures/Gestion/Permissions/PermissionStructureUserForm.vue"
    )
);

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const BaseInfoForm = defineAsyncComponent(() =>
    import("@/Components/Inscription/BaseInfoForm.vue")
);

const props = defineProps({
    structurestypes: Object,
    structure: Object,
    confirmedReservationsCount: Number,
    allReservationsCount: Number,
    pendingReservationsCount: Number,
    errors: Object,
    can: Object,
});

const name = ref(null);
const filteredAttributs = ref([]);

const form = useForm({
    _method: "put",
    name: props.structure.name,
    structuretype_id: props.structure.structuretype_id,
    attributs: [],
    address: props.structure.address,
    city: props.structure.city_name,
    zip_code: props.structure.zip_code,
    country: props.structure.country,
    address_lat: props.structure.address_lat,
    address_lng: props.structure.address_lng,
    email: props.structure.email,
    date_creation: props.structure.date_creation,
    website: props.structure.website,
    phone1: props.structure.phone1,
    phone2: props.structure.phone2,
    facebook: props.structure.facebook,
    instagram: props.structure.instagram,
    youtube: props.structure.youtube,
    tiktok: props.structure.tiktok,
    presentation_courte: props.structure.presentation_courte,
    presentation_longue: props.structure.presentation_longue,
    abo_news: !!props.structure.abo_news,
    abo_promo: !!props.structure.abo_promo,
    logo: props.structure.logo,
});

if (props.structure.structuretype_infos) {
    props.structure.structuretype_infos.forEach((info) => {
        if (info.valeur_id) {
            const attributValue = props.structurestypes
                .flatMap((st) => st.attributs)
                .flatMap((attribut) => attribut.valeurs)
                .find((valeur) => valeur.id === info.valeur_id);
            form.attributs[info.attribut_id] = attributValue
                ? attributValue
                : null;
        } else {
            form.attributs[info.attribut_id] = info.valeur;
        }
    });
}

watch(
    () => form.structuretype_id,
    (newVal) => {
        if (newVal) {
            const selectedStructureType = props.structurestypes.find(
                (structuretype) => structuretype.id === newVal
            );
            filteredAttributs.value = selectedStructureType
                ? selectedStructureType.attributs
                : [];
        } else {
            filteredAttributs.value = [];
        }
    },
    { immediate: true }
);

const displayAdresses = ref(false);
const displayEditStructure = ref(true);
const displayPartenaire = ref(false);

const handleButtonEvent = (message) => {
    if (message === "Mes adresses") {
        displayAdresses.value = true;
        displayEditStructure.value = false;
        displayPartenaire.value = false;
    } else if (message === "Edit Structure") {
        displayEditStructure.value = true;
        displayAdresses.value = false;
        displayPartenaire.value = false;
    } else if (message === "Partenaires") {
        displayEditStructure.value = false;
        displayAdresses.value = false;
        displayPartenaire.value = true;
    }
};

const addPermission = ref(false);
const showUpdatePermissionForm = ref(false);
const displayPartenaireForm = () => {
    showUpdatePermissionForm.value = false;
    addPermission.value = !addPermission.value;
};

const addAddress = ref(false);
const displayAdresseForm = () => {
    showUpdateAddressForm.value = false;
    addAddress.value = !addAddress.value;
};

const addressForm = useForm({
    address: null,
    city: null,
    zip_code: null,
    country: null,
    address_lat: null,
    address_lng: null,
});

const onSubmitAdress = () => {
    addressForm.post(route("structures.adresses.store", props.structure.slug), {
        preserveScroll: true,
        onSuccess: () => {
            addressForm.reset();
            displayAdresseForm();
        },
    });
};

const showUpdateAddressForm = ref(false);
const selectedAdresse = ref(null);

const updateAddressForm = useForm({
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
});

watch(
    () => selectedAdresse.value,
    (newAdresse) => {
        if (newAdresse) {
            updateAddressForm.address = newAdresse.address;
            updateAddressForm.city = newAdresse.city;
            updateAddressForm.zip_code = newAdresse.zip_code;
            updateAddressForm.country = newAdresse.country;
            updateAddressForm.address_lat = newAdresse.address_lat;
            updateAddressForm.address_lng = newAdresse.address_lng;
        }
    }
);

const displayUpdateAdresseForm = (adresse) => {
    selectedAdresse.value = adresse;
    addAddress.value = false;
    showUpdateAddressForm.value = !showUpdateAddressForm.value;
};

const onUpdateAdress = () => {
    const adresse = selectedAdresse.value;
    updateAddressForm.put(
        route("structures.adresses.update", {
            structure: props.structure,
            adress: adresse,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                updateAddressForm.reset();
                displayUpdateAdresseForm();
            },
        }
    );
};

const deleteAdresse = (adresse) => {
    router.delete(
        route("structures.adresses.destroy", {
            structure: props.structure.slug,
            adress: adresse.id,
        }),
        {
            preserveScroll: true,
        }
    );
};

const deletePartenaire = (user) => {
    router.delete(
        route("structures.users.destroy", {
            structure: props.structure.slug,
            user: user.id,
        }),
        {
            preserveScroll: true,
        }
    );
};

const submit = () => {
    form.post(route("structures.update", props.structure), {
        preserveScroll: true,
    });
};

onMounted(() => {
    name.value.focus();
});
</script>

<template>
    <Head title="Editer votre structure" />

    <ProLayout
        :structure="structure"
        :can="can"
        :all-reservations-count="allReservationsCount"
        :pending-reservations-count="pendingReservationsCount"
        :confirmed-reservations-count="confirmedReservationsCount"
    >
        <template #header>
            <div class="flex h-full items-center justify-start">
                <Link
                    class="h-full bg-blue-600 py-2.5 md:px-4 md:py-4"
                    :href="route('structures.gestion.index', structure)"
                >
                    <ChevronLeftIcon class="h-10 w-10 text-white" />
                </Link>
                <h1
                    class="px-2 py-2.5 text-center text-lg font-semibold text-indigo-700 md:px-6 md:py-4 md:text-left md:text-2xl md:font-bold"
                >
                    Ma structure
                </h1>
            </div>
        </template>

        <template #default>
            <MicroNavBackPro
                :can="can"
                :structure="structure"
                @eventFromChild="handleButtonEvent"
            />
            <div
                class="relative flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
            >
                <div class="flex-1">
                    <template v-if="displayEditStructure">
                        <div class="mx-auto my-4 max-w-full md:px-4">
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <div class="md:col-span-3">
                                    <!--  -->
                                    <div class="px-4 sm:px-0">
                                        <h3
                                            class="text-lg font-medium leading-6 text-gray-700"
                                        >
                                            Edition et Profil social de votre
                                            structure
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-800">
                                            Les champs suivis d'un astérisque
                                            (*) sont requis. Ces informations
                                            apparaitront publiquement sur ce
                                            site.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-5 md:col-span-3 md:mt-0">
                                    <form
                                        @submit.prevent="submit"
                                        enctype="multipart/form-data"
                                        autocomplete="off"
                                    >
                                        <div
                                            class="shadow sm:overflow-hidden sm:rounded-md"
                                        >
                                            <div
                                                class="space-y-6 bg-white px-4 py-5 sm:p-6"
                                            >
                                                <div
                                                    class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6"
                                                >
                                                    <!-- Name -->
                                                    <div
                                                        class="col-span-1 md:col-span-2"
                                                    >
                                                        <label
                                                            for="name"
                                                            class="block text-sm font-medium normal-case text-gray-700"
                                                        >
                                                            Nom de la structure
                                                            *
                                                            <span
                                                                class="text-xs italic"
                                                                >(Attention, en
                                                                changeant le
                                                                nom, vous
                                                                changez aussi le
                                                                lien d'accès à
                                                                la page de votre
                                                                structure)</span
                                                            >
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md"
                                                        >
                                                            <input
                                                                ref="name"
                                                                v-model="
                                                                    form.name
                                                                "
                                                                type="text"
                                                                name="name"
                                                                id="name"
                                                                class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                placeholder=""
                                                                autocomplete="none"
                                                            />
                                                        </div>
                                                        <div
                                                            v-if="errors.name"
                                                            class="mt-2 text-xs text-red-500"
                                                        >
                                                            {{ errors.name }}
                                                        </div>
                                                    </div>
                                                    <!-- structuretype_id -->
                                                    <div class="flex flex-col">
                                                        <label
                                                            for="structuretype_id"
                                                            class="block text-sm font-medium normal-case text-gray-700"
                                                        >
                                                            Type de structure *
                                                        </label>
                                                        <div class="mt-1">
                                                            <Dropdown
                                                                v-model="
                                                                    form.structuretype_id
                                                                "
                                                                :options="
                                                                    structurestypes
                                                                "
                                                                optionLabel="name"
                                                                optionValue="id"
                                                                placeholder="Type de structure"
                                                                class="w-full text-sm md:w-[14rem]"
                                                                :ptOptions="{
                                                                    mergeProps: true,
                                                                }"
                                                                :pt="{
                                                                    item: 'text-sm',
                                                                }"
                                                                showClear
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- structure type attributs -->
                                                <div
                                                    v-if="
                                                        form.structuretype_id &&
                                                        filteredAttributs &&
                                                        filteredAttributs.length >
                                                            0
                                                    "
                                                    class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6"
                                                >
                                                    <div
                                                        v-for="attribut in filteredAttributs"
                                                        :key="attribut.id"
                                                    >
                                                        <!-- input text -->
                                                        <div
                                                            v-if="
                                                                attribut.type_champ_form ===
                                                                'text'
                                                            "
                                                            class="max-w-sm"
                                                        >
                                                            <label
                                                                :for="
                                                                    attribut.nom
                                                                "
                                                                class="block text-sm font-medium normal-case text-gray-700"
                                                            >
                                                                {{
                                                                    attribut.nom
                                                                }}
                                                            </label>
                                                            <div
                                                                class="mt-1 flex rounded-md"
                                                            >
                                                                <TextInput
                                                                    type="text"
                                                                    v-model="
                                                                        form
                                                                            .attributs[
                                                                            attribut
                                                                                .id
                                                                        ]
                                                                    "
                                                                    :name="
                                                                        attribut.nom
                                                                    "
                                                                    :id="
                                                                        attribut.nom
                                                                    "
                                                                    class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                                    placeholder=""
                                                                    autocomplete="none"
                                                                />
                                                            </div>
                                                        </div>
                                                        <!-- select -->
                                                        <div
                                                            v-if="
                                                                attribut.type_champ_form ===
                                                                'select'
                                                            "
                                                        >
                                                            <label
                                                                :for="
                                                                    attribut.nom
                                                                "
                                                                class="block text-sm font-medium normal-case text-gray-700"
                                                            >
                                                                {{
                                                                    attribut.nom
                                                                }}
                                                            </label>

                                                            <div
                                                                class="mt-1 flex rounded-md"
                                                            >
                                                                <Dropdown
                                                                    v-model="
                                                                        form
                                                                            .attributs[
                                                                            attribut
                                                                                .id
                                                                        ]
                                                                    "
                                                                    :options="
                                                                        attribut.valeurs
                                                                    "
                                                                    optionLabel="nom"
                                                                    placeholder=""
                                                                    class="w-full text-sm md:w-[14rem]"
                                                                    :ptOptions="{
                                                                        mergeProps: true,
                                                                    }"
                                                                    :pt="{
                                                                        item: 'text-sm',
                                                                    }"
                                                                    showClear
                                                                />
                                                            </div>
                                                        </div>
                                                        <!-- checkbox -->
                                                        <div
                                                            v-if="
                                                                attribut.type_champ_form ===
                                                                'checkbox'
                                                            "
                                                        >
                                                            <Checkbox
                                                                v-model:checked="
                                                                    form
                                                                        .attributs[
                                                                        attribut
                                                                            .id
                                                                    ]
                                                                "
                                                                :name="
                                                                    attribut.nom
                                                                "
                                                            />
                                                        </div>
                                                        <!-- radio -->
                                                        <div
                                                            v-if="
                                                                attribut.type_champ_form ===
                                                                'radio'
                                                            "
                                                        >
                                                            <label
                                                                :for="
                                                                    attribut.nom
                                                                "
                                                                class="mb-2 block text-sm font-medium normal-case text-gray-700"
                                                            >
                                                                {{
                                                                    attribut.nom
                                                                }}
                                                            </label>
                                                            <div
                                                                class="flex items-center gap-3"
                                                            >
                                                                <div
                                                                    v-for="valeur in attribut.valeurs"
                                                                    :key="
                                                                        valeur.id
                                                                    "
                                                                    class="flex items-center"
                                                                >
                                                                    <RadioButton
                                                                        v-model="
                                                                            form
                                                                                .attributs[
                                                                                attribut
                                                                                    .id
                                                                            ]
                                                                        "
                                                                        :inputId="
                                                                            valeur.nom
                                                                        "
                                                                        name="dynamic"
                                                                        :value="
                                                                            valeur
                                                                        "
                                                                    />
                                                                    <label
                                                                        :for="
                                                                            valeur.nom
                                                                        "
                                                                        class="ml-2"
                                                                        >{{
                                                                            valeur.nom
                                                                        }}</label
                                                                    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Adresse -->
                                                <AddressForm
                                                    label="Siège social"
                                                    :errors="errors"
                                                    v-model:address="
                                                        form.address
                                                    "
                                                    v-model:city="form.city"
                                                    v-model:zip_code="
                                                        form.zip_code
                                                    "
                                                    v-model:country="
                                                        form.country
                                                    "
                                                    v-model:address_lat="
                                                        form.address_lat
                                                    "
                                                    v-model:address_lng="
                                                        form.address_lng
                                                    "
                                                />

                                                <BaseInfoForm
                                                    :errors="errors"
                                                    v-model:website="
                                                        form.website
                                                    "
                                                    v-model:email="form.email"
                                                    v-model:date_creation="
                                                        form.date_creation
                                                    "
                                                    v-model:phone1="form.phone1"
                                                    v-model:phone2="form.phone2"
                                                    v-model:facebook="
                                                        form.facebook
                                                    "
                                                    v-model:instagram="
                                                        form.instagram
                                                    "
                                                    v-model:youtube="
                                                        form.youtube
                                                    "
                                                    v-model:tiktok="form.tiktok"
                                                />

                                                <!-- presentation_courte -->
                                                <div>
                                                    <label
                                                        for="presentation_courte"
                                                        class="block text-sm font-medium normal-case text-gray-700"
                                                    >
                                                        Description courte *
                                                    </label>
                                                    <div class="mt-1">
                                                        <textarea
                                                            v-model="
                                                                form.presentation_courte
                                                            "
                                                            id="presentation_courte"
                                                            name="presentation_courte"
                                                            rows="3"
                                                            class="mt-1 block h-48 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                            :class="{
                                                                errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                            }"
                                                            placeholder="Un peu d'historique, vos activités... Mettez votre club en valeur"
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                    <p
                                                        class="mt-2 text-sm text-gray-500"
                                                    >
                                                        Description de votre
                                                        structure en quelques
                                                        lignes.
                                                    </p>
                                                    <div
                                                        v-if="
                                                            errors.presentation_courte
                                                        "
                                                        class="mt-2 text-xs text-red-500"
                                                    >
                                                        {{
                                                            errors.presentation_courte
                                                        }}
                                                    </div>
                                                </div>

                                                <!-- presentation_longue -->
                                                <div>
                                                    <label
                                                        for="presentation_longue"
                                                        class="block text-sm font-medium normal-case text-gray-700"
                                                    >
                                                        Présentation longue
                                                    </label>
                                                    <div class="mt-1">
                                                        <textarea
                                                            v-model="
                                                                form.presentation_longue
                                                            "
                                                            id="presentation_longue"
                                                            name="presentation_longue"
                                                            rows="3"
                                                            class="mt-1 block h-48 min-h-full w-full rounded-md border border-gray-300 placeholder-gray-400 placeholder-opacity-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                            :class="{
                                                                errors: 'border-red-500 focus:ring focus:ring-red-200',
                                                            }"
                                                            placeholder="Un peu d'historique, vos activités... Mettez votre structure en valeur"
                                                            autocomplete="none"
                                                        />
                                                    </div>
                                                    <p
                                                        class="mt-2 text-sm text-gray-500"
                                                    >
                                                        Description de votre
                                                        structure.
                                                    </p>
                                                    <div
                                                        v-if="
                                                            errors.presentation_longue
                                                        "
                                                        class="mt-2 text-xs text-red-500"
                                                    >
                                                        {{
                                                            errors.presentation_longue
                                                        }}
                                                    </div>
                                                </div>

                                                <!-- Logo -->
                                                <div>
                                                    <label
                                                        for="logo"
                                                        class="block text-sm font-medium normal-case text-gray-700"
                                                        >Photo ou logo:</label
                                                    >
                                                    <input
                                                        class="mt-1 text-sm text-gray-700"
                                                        type="file"
                                                        id="logo"
                                                        @input="
                                                            form.logo =
                                                                $event.target.files[0]
                                                        "
                                                    />
                                                    <span
                                                        class="mt-2 text-xs text-red-500"
                                                        v-if="errors.logo"
                                                        v-text="errors.logo[0]"
                                                    ></span>
                                                </div>

                                                <!-- abonnements -->
                                                <Checkbox
                                                    v-model:checked="
                                                        form.abo_news
                                                    "
                                                    name="Abonnement à la newsletter"
                                                />
                                                <Checkbox
                                                    v-model:checked="
                                                        form.abo_promo
                                                    "
                                                    name="Abonnement aux promotions"
                                                />
                                            </div>

                                            <!--buttons  -->
                                            <div
                                                class="bg-gray-50 px-4 py-3 text-right sm:px-6"
                                            >
                                                <button
                                                    :disabled="form.processing"
                                                    :class="{
                                                        'opacity-25':
                                                            form.processing,
                                                    }"
                                                    type="submit"
                                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                                >
                                                    <LoadingSVG
                                                        v-if="form.processing"
                                                    />
                                                    Editer votre structure
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-if="displayAdresses">
                        <div
                            class="mx-auto my-4 flex w-full max-w-5xl flex-col space-y-6 rounded-md border border-gray-200 bg-gray-50 px-4 py-2 text-gray-800 shadow-md"
                        >
                            <div class="flex items-center justify-start">
                                <h3 class="text-xl font-semibold">
                                    Vos adresses:
                                </h3>
                            </div>

                            <ul class="list-inside list-disc space-y-2">
                                <li
                                    class="flex items-center justify-between"
                                    v-for="adresse in structure.adresses"
                                    :key="adresse.id"
                                >
                                    <span
                                        >{{ adresse.address }},
                                        {{ adresse.zip_code }}
                                        {{ adresse.city }}</span
                                    >
                                    <div class="flex items-center gap-x-6">
                                        <button
                                            type="button"
                                            @click="
                                                displayUpdateAdresseForm(
                                                    adresse
                                                )
                                            "
                                        >
                                            <ArrowPathIcon
                                                class="h-6 w-6 text-blue-500 transition-all duration-200 hover:-rotate-90 hover:text-indigo-500"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            @click="deleteAdresse(adresse)"
                                        >
                                            <TrashIcon
                                                class="h-6 w-6 text-red-500 hover:text-red-700"
                                            />
                                        </button>
                                    </div>
                                </li>
                            </ul>
                            <div class="flex items-center justify-end">
                                <button
                                    class="group flex items-center justify-center rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm hover:bg-blue-500"
                                    type="button"
                                    @click="displayAdresseForm"
                                >
                                    <span class="group-hover:text-white"
                                        >Ajouter une adresse</span
                                    >
                                    <PlusIcon
                                        v-if="!addAddress"
                                        class="ml-4 h-5 w-5 text-blue-500 group-hover:text-white"
                                    />
                                    <MinusIcon
                                        v-else
                                        class="ml-4 h-5 w-5 text-blue-500 group-hover:text-white"
                                    />
                                </button>
                            </div>
                            <form
                                class="flex flex-col justify-end"
                                v-if="showUpdateAddressForm"
                                @submit.prevent="onUpdateAdress"
                                autocomplete="off"
                            >
                                <AddressForm
                                    :errors="errors"
                                    v-model:address="updateAddressForm.address"
                                    v-model:city="updateAddressForm.city"
                                    v-model:zip_code="
                                        updateAddressForm.zip_code
                                    "
                                    v-model:country="updateAddressForm.country"
                                    v-model:address_lat="
                                        updateAddressForm.address_lat
                                    "
                                    v-model:address_lng="
                                        updateAddressForm.address_lng
                                    "
                                />
                                <button
                                    type="submit"
                                    :disabled="updateAddressForm.processing"
                                    :class="{
                                        'opacity-25':
                                            updateAddressForm.processing,
                                    }"
                                    class="my-4 flex items-center self-end rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm hover:bg-blue-500 hover:text-white"
                                >
                                    <LoadingSVG
                                        v-if="updateAddressForm.processing"
                                    />
                                    Mettre à jour
                                </button>
                            </form>
                            <form
                                class="flex flex-col justify-end"
                                v-if="addAddress"
                                @submit.prevent="onSubmitAdress"
                                autocomplete="off"
                            >
                                <AddressForm
                                    :errors="errors"
                                    v-model:address="addressForm.address"
                                    v-model:city="addressForm.city"
                                    v-model:zip_code="addressForm.zip_code"
                                    v-model:country="addressForm.country"
                                    v-model:address_lat="
                                        addressForm.address_lat
                                    "
                                    v-model:address_lng="
                                        addressForm.address_lng
                                    "
                                />
                                <button
                                    :disabled="addressForm.processing"
                                    :class="{
                                        'opacity-25': addressForm.processing,
                                    }"
                                    type="submit"
                                    class="my-4 flex items-center self-end rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm hover:bg-blue-500 hover:text-white"
                                >
                                    <LoadingSVG v-if="addressForm.processing" />
                                    Enregistrer
                                </button>
                            </form>
                        </div>
                    </template>

                    <template v-if="displayPartenaire">
                        <div
                            class="mx-auto my-4 flex w-full max-w-5xl flex-col space-y-6 rounded-md border border-gray-200 bg-gray-50 px-4 py-2 text-gray-800 shadow-md"
                        >
                            <div class="flex items-center justify-start">
                                <h3 class="text-xl font-semibold">
                                    Vos partenaires et instructeurs:
                                </h3>
                            </div>
                            <ul class="list-inside list-disc space-y-2">
                                <li
                                    class="flex items-center justify-between"
                                    v-for="user in structure.users"
                                    :key="user.id"
                                >
                                    <p>
                                        <span
                                            class="font-semibold text-indigo-500"
                                            >{{ user.name }}</span
                                        >, {{ user.email }}. Niveau:<span
                                            class="font-semibold"
                                            v-if="user.pivot.niveau === 1"
                                        >
                                            Super administrateur</span
                                        >
                                        <span
                                            class="font-semibold"
                                            v-else-if="user.pivot.niveau === 2"
                                        >
                                            Administrateur</span
                                        >
                                        <span
                                            class="font-semibold"
                                            v-else-if="user.pivot.niveau === 3"
                                        >
                                            Sans permission</span
                                        >, telephone:
                                        {{ user.pivot.phone }}
                                    </p>

                                    <div class="flex items-center gap-x-6">
                                        <!-- <button
                                            type="button"
                                            @click="
                                                displayPartenaireForm(
                                                    partenaire
                                                )
                                            "
                                        >
                                            <ArrowPathIcon
                                                class="w-6 h-6 text-blue-500 transition-all duration-200 hover:-rotate-90 hover:text-indigo-500"
                                            />
                                        </button> -->
                                        <button
                                            type="button"
                                            @click="deletePartenaire(user)"
                                        >
                                            <TrashIcon
                                                class="h-6 w-6 text-red-500 hover:text-red-700"
                                            />
                                        </button>
                                    </div>
                                </li>
                            </ul>
                            <div class="flex items-center justify-end">
                                <button
                                    class="group flex items-center justify-center rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm hover:bg-blue-500"
                                    type="button"
                                    @click="displayPartenaireForm"
                                >
                                    <span class="group-hover:text-white"
                                        >Ajouter une permission</span
                                    >
                                    <PlusIcon
                                        class="ml-4 h-5 w-5 text-blue-500 group-hover:text-white"
                                    />
                                </button>
                            </div>
                            <PermissionStructureUserForm
                                v-if="addPermission"
                                :structure="structure"
                            />
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </ProLayout>
</template>

<style>
.vc-select select {
    background-image: unset;
}
</style>
