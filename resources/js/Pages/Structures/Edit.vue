<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted, computed, defineAsyncComponent } from "vue";
import { ArrowPathIcon, PlusIcon, TrashIcon } from "@heroicons/vue/24/outline";
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
    niveaux: Object,
    publictypes: Object,
    disciplines: Object,
    structure: Object,
    confirmedReservationsCount: Number,
    allReservationsCount: Number,
    pendingReservationsCount: Number,
    errors: Object,
    can: Object,
});

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
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
});

const onSubmitAdress = () => {
    router.post(
        route("structures.adresses.store", props.structure.slug),
        {
            address: addressForm.address,
            city: addressForm.city,
            zip_code: addressForm.zip_code,
            country: addressForm.country,
            address_lat: addressForm.address_lat,
            address_lng: addressForm.address_lng,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                addressForm.reset();
                displayAdresseForm();
            },
        }
    );
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
    router.put(
        route("structures.adresses.update", {
            structure: props.structure.slug,
            adress: adresse.id,
        }),
        {
            address: updateAddressForm.address,
            city: updateAddressForm.city,
            zip_code: updateAddressForm.zip_code,
            country: updateAddressForm.country,
            address_lat: updateAddressForm.address_lat,
            address_lng: updateAddressForm.address_lng,
        },
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

const deletePartenaire = (partenaire) => {
    router.delete(
        route("structures.partenaires.destroy", {
            structure: props.structure.slug,
            partenaire: partenaire.id,
        }),
        {
            preserveScroll: true,
        }
    );
};

const form = useForm({
    name: ref(props.structure.name),
    structuretype_id: ref(props.structure.structuretype_id),
    attributs: ref([props.structure.attributs]),
    address: ref(props.structure.address),
    city: ref(props.structure.city),
    zip_code: ref(props.structure.zip_code),
    country: ref(props.structure.country),
    address_lat: ref(props.structure.address_lat),
    address_lng: ref(props.structure.address_lng),
    email: ref(props.structure.email),
    date_creation: ref(props.structure.date_creation),
    website: ref(props.structure.website),
    phone1: ref(props.structure.phone1),
    phone2: ref(props.structure.phone2),
    facebook: ref(props.structure.facebook),
    instagram: ref(props.structure.instagram),
    youtube: ref(props.structure.youtube),
    tiktok: ref(props.structure.tiktok),
    presentation_courte: ref(props.structure.presentation_courte),
    presentation_longue: ref(props.structure.presentation_longue),
    abo_news: ref(props.structure.abo_news),
    abo_promo: ref(props.structure.abo_promo),
    logo: ref(null),
});

const name = ref(null);
const aboNews = ref(props.structure.abo_news);
const aboPromo = ref(props.structure.abo_promo);
const isAboNewsChecked = computed(() => {
    return aboNews.value === 1 ? true : false;
});
const isAboPromoChecked = computed(() => {
    return aboPromo.value === 1 ? true : false;
});

const addItem = (id) => {
    form.attributs.push({
        id,
    });
};

function resetFields() {
    form.reset("attributs");
}

onMounted(() => {
    name.value.focus();
});

const submit = () => {
    router.post(
        `/structures/${props.structure.id}`,
        {
            _method: "put",
            name: form.name,
            structuretype_id: form.structuretype_id,
            attributs: form.attributs,
            address: form.address,
            city: form.city,
            zip_code: form.zip_code,
            country: form.country,
            address_lat: form.address_lat,
            address_lng: form.address_lng,
            email: form.email,
            date_creation: form.date_creation,
            website: form.website,
            phone1: form.phone1,
            phone2: form.phone2,
            facebook: form.facebook,
            instagram: form.instagram,
            youtube: form.youtube,
            tiktok: form.tiktok,
            presentation_courte: form.presentation_courte,
            presentation_longue: form.presentation_longue,
            abo_news: form.abo_news,
            abo_promo: form.abo_promo,
            logo: form.logo,
        },
        props.structure
    );
};
</script>

<template>
    <Head title="Editer votre structure" />

    <ProLayout
        :structure="structure"
        :can="can"
        :allReservationsCount="allReservationsCount"
        :pendingReservationsCount="pendingReservationsCount"
        :confirmedReservationsCount="confirmedReservationsCount"
    >
        <template #header>
            <h1 class="text-2xl font-bold text-indigo-700">Ma structure</h1>
        </template>

        <template #default>
            <MicroNavBackPro @eventFromChild="handleButtonEvent" />
            <div
                class="relative flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
            >
                <div class="flex-1">
                    <template v-if="displayEditStructure">
                        <div class="mx-auto max-w-full lg:px-4">
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
                                                    class="grid grid-cols-3 gap-6"
                                                >
                                                    <!-- Name -->
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="name"
                                                            class="block text-sm font-medium text-gray-700"
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
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="structuretype_id"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Type de structure *
                                                        </label>
                                                        <div class="mt-1">
                                                            <select
                                                                name="structuretype_id"
                                                                id="structuretype_id"
                                                                v-model="
                                                                    form.structuretype_id
                                                                "
                                                                class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                            >
                                                                <option
                                                                    v-for="structure in structurestypes"
                                                                    :key="
                                                                        structure.id
                                                                    "
                                                                    :value="
                                                                        structure.id
                                                                    "
                                                                >
                                                                    {{
                                                                        structure.name
                                                                    }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div
                                                            v-if="
                                                                errors.structuretype_id
                                                            "
                                                            class="mt-2 text-xs text-red-500"
                                                        >
                                                            {{
                                                                errors.structuretype_id
                                                            }}
                                                        </div>
                                                    </div>
                                                    <!-- structure type attributs -->
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <div
                                                            v-for="(
                                                                structuretype,
                                                                index
                                                            ) in props.structurestypes"
                                                            :key="
                                                                structuretype.id
                                                            "
                                                            class="flex flex-col space-y-6"
                                                        >
                                                            <div
                                                                v-for="(
                                                                    attribut,
                                                                    idx
                                                                ) in structuretype.structuretypeattributs"
                                                                :key="
                                                                    attribut.id
                                                                "
                                                            >
                                                                <div
                                                                    v-if="
                                                                        attribut.structuretype_id ===
                                                                        form.structuretype_id
                                                                    "
                                                                >
                                                                    <!-- input text -->
                                                                    <div
                                                                        v-if="
                                                                            attribut.type_champ_form ===
                                                                            'text'
                                                                        "
                                                                    >
                                                                        <label
                                                                            :for="
                                                                                attribut.nom
                                                                            "
                                                                            class="block text-sm font-medium text-gray-700"
                                                                        >
                                                                            {{
                                                                                attribut.nom
                                                                            }}
                                                                        </label>
                                                                        <div
                                                                            class="mt-1 flex rounded-md"
                                                                        >
                                                                            <input
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

                                                                        <!-- <div
                                                                v-if="
                                                                    errors.attributs
                                                                "
                                                                class="mt-2 text-xs text-red-500"
                                                            >
                                                                {{
                                                                    errors.attributs
                                                                }}
                                                            </div> -->
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
                                                                            class="block text-sm font-medium text-gray-700"
                                                                        >
                                                                            {{
                                                                                attribut.nom
                                                                            }}
                                                                        </label>
                                                                        <div
                                                                            class="mt-1 flex rounded-md"
                                                                        >
                                                                            <select
                                                                                :name="
                                                                                    attribut.nom
                                                                                "
                                                                                :id="
                                                                                    attribut.nom
                                                                                "
                                                                                v-model="
                                                                                    form
                                                                                        .attributs[
                                                                                        attribut
                                                                                            .id
                                                                                    ]
                                                                                "
                                                                                class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                                            >
                                                                                <option
                                                                                    v-for="(
                                                                                        option,
                                                                                        index
                                                                                    ) in attribut.structuretypevaleurs"
                                                                                    :key="
                                                                                        index
                                                                                    "
                                                                                    :value="
                                                                                        option.nom
                                                                                    "
                                                                                >
                                                                                    {{
                                                                                        option.nom
                                                                                    }}
                                                                                </option>
                                                                            </select>
                                                                        </div>

                                                                        <!-- <div
                                                                v-if="
                                                                    errors.attributs
                                                                "
                                                                class="mt-2 text-xs text-red-500"
                                                            >
                                                                {{
                                                                    errors.attributs
                                                                }}
                                                            </div> -->
                                                                    </div>
                                                                    <!-- checkbox -->
                                                                    <div
                                                                        v-if="
                                                                            attribut.type_champ_form ===
                                                                            'checkbox'
                                                                        "
                                                                    >
                                                                        <div
                                                                            class="flex items-center"
                                                                        >
                                                                            <input
                                                                                v-model="
                                                                                    form
                                                                                        .attributs[
                                                                                        attribut
                                                                                            .id
                                                                                    ]
                                                                                "
                                                                                :id="
                                                                                    attribut.nom
                                                                                "
                                                                                type="checkbox"
                                                                                class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                                                                            />
                                                                            <label
                                                                                :for="
                                                                                    attribut.nom
                                                                                "
                                                                                class="ml-2 text-sm font-medium text-gray-700"
                                                                                >{{
                                                                                    attribut.nom
                                                                                }}</label
                                                                            >
                                                                        </div>
                                                                        <!-- <div
                                                                v-if="
                                                                    errors.attributs
                                                                "
                                                                class="mt-2 text-xs text-red-500"
                                                            >
                                                                {{
                                                                    errors.attributs
                                                                }}
                                                            </div> -->
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
                                                                            class="block text-sm font-medium text-gray-700"
                                                                        >
                                                                            {{
                                                                                attribut.nom
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
                                                                                    ) in attribut.structuretypevaleurs"
                                                                                    :key="
                                                                                        index
                                                                                    "
                                                                                >
                                                                                    <input
                                                                                        v-model="
                                                                                            form
                                                                                                .attributs[
                                                                                                attribut
                                                                                                    .id
                                                                                            ]
                                                                                        "
                                                                                        type="radio"
                                                                                        class="form-radio"
                                                                                        :name="
                                                                                            option.nom
                                                                                        "
                                                                                        :value="
                                                                                            option.nom
                                                                                        "
                                                                                        checked
                                                                                    />
                                                                                    <span
                                                                                        class="ml-2"
                                                                                        >{{
                                                                                            option.nom
                                                                                        }}</span
                                                                                    >
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <!-- <div
                                                                v-if="
                                                                    errors.attributs
                                                                "
                                                                class="mt-2 text-xs text-red-500"
                                                            >
                                                                {{
                                                                    errors.attributs
                                                                }}
                                                            </div> -->
                                                                    </div>
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
                                                        class="block text-sm font-medium text-gray-700"
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
                                                        class="block text-sm font-medium text-gray-700"
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
                                                        class="block text-sm font-medium text-gray-700"
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

                                                <!-- abo_news -->
                                                <div>
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <input
                                                            :checked="
                                                                isAboNewsChecked
                                                            "
                                                            @change="
                                                                form.abo_news =
                                                                    $event
                                                                        .target
                                                                        .checked
                                                                        ? 1
                                                                        : 0
                                                            "
                                                            id="abo_news"
                                                            type="checkbox"
                                                            class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                                                        />
                                                        <label
                                                            for="abo_news"
                                                            class="ml-2 text-sm font-medium text-gray-700"
                                                            >Abonnement à la
                                                            newsletter</label
                                                        >
                                                    </div>
                                                </div>

                                                <!-- abo_promo -->
                                                <div>
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <input
                                                            :checked="
                                                                isAboPromoChecked
                                                            "
                                                            @change="
                                                                form.abo_promo =
                                                                    $event
                                                                        .target
                                                                        .checked
                                                                        ? 1
                                                                        : 0
                                                            "
                                                            id="abo_promo"
                                                            type="checkbox"
                                                            class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                                                        />
                                                        <label
                                                            for="abo_promo"
                                                            class="ml-2 text-sm font-medium text-gray-700"
                                                            >Abonnement aux
                                                            promotions</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>

                                            <!--buttons  -->
                                            <div
                                                class="bg-gray-50 px-4 py-3 text-right sm:px-6"
                                            >
                                                <button
                                                    :disabled="form.processing"
                                                    type="submit"
                                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                                >
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
                            class="my-4 flex w-full flex-col space-y-6 rounded-md border border-gray-200 bg-gray-50 px-4 py-2 text-gray-800 shadow-md"
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
                                    class="my-4 flex items-center self-end rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm hover:bg-blue-500 hover:text-white"
                                >
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
                                    type="submit"
                                    class="my-4 flex items-center self-end rounded-md border border-gray-200 bg-white px-4 py-2.5 text-sm hover:bg-blue-500 hover:text-white"
                                >
                                    Enregistrer
                                </button>
                            </form>
                        </div>
                    </template>
                    <!-- <PathsInscriptionNavigation /> -->

                    <template v-if="displayPartenaire">
                        <div
                            class="my-4 flex w-full flex-col space-y-6 rounded-md border border-gray-200 bg-gray-50 px-4 py-2 text-gray-800 shadow-md"
                        >
                            <div class="flex items-center justify-start">
                                <h3 class="text-xl font-semibold">
                                    Vos partenaires et instructeurs:
                                </h3>
                            </div>
                            <ul class="list-inside list-disc space-y-2">
                                <li
                                    class="flex items-center justify-between"
                                    v-for="partenaire in structure.partenaires"
                                    :key="partenaire.id"
                                >
                                    <span
                                        >{{ partenaire.name }},
                                        {{ partenaire.email }}, niveau:
                                        {{ partenaire.pivot.niveau }},
                                        {{ partenaire.pivot.phone }}</span
                                    >

                                    <div class="flex items-center gap-x-6">
                                        <button
                                            type="button"
                                            @click="
                                                displayUpdatePartenaireForm(
                                                    partenaire
                                                )
                                            "
                                        >
                                            <ArrowPathIcon
                                                class="h-6 w-6 text-blue-500 transition-all duration-200 hover:-rotate-90 hover:text-indigo-500"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            @click="
                                                deletePartenaire(partenaire)
                                            "
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

                    <div class="hidden sm:block" aria-hidden="true">
                        <div class="py-5">
                            <div class="border-t border-gray-200" />
                        </div>
                    </div>
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
