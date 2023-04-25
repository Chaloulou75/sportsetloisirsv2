<script setup>
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch, defineAsyncComponent } from "vue";

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
    errors: Object,
});

const form = useForm({
    name: ref(null),
    structuretype_id: ref(null),
    attributs: ref([]),
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
    email: ref(null),
    date_creation: ref(null),
    website: ref(null),
    phone1: ref(null),
    phone2: ref(null),
    facebook: ref(null),
    instagram: ref(null),
    youtube: ref(null),
    tiktok: ref(null),
    presentation_courte: ref(null),
    presentation_longue: ref(null),
    abo_news: ref(true),
    abo_promo: ref(true),
    logo: ref(null),
});

const addItem = (id) => {
    form.attributs.push({
        id,
    });
};

function resetFields() {
    form.reset("attributs");
}

const name = ref(null);
const familles = ref([]);
const disciplinesList = ref([]);

const getFamilles = async () => {
    let response = await axios.get("/api/familles");
    familles.value = response.data.data;
};

onMounted(() => {
    name.value.focus();
    getFamilles();
});

watch(
    () => form.famille_id,
    async (newFamilleID) => {
        axios
            .get("/api/disciplines?famille_id=" + newFamilleID)
            .then((response) => {
                disciplinesList.value = response.data.data;
            })
            .catch((e) => {
                console.log(e);
            });
    }
);

function submit() {
    form.post("/structures");
}
</script>

<template>
    <Head title="Inscription de votre structure" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Inscription de votre structure
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <!--  -->
                            <div class="px-4 sm:px-0">
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-700"
                                >
                                    Inscription et Profil social de votre
                                    structure
                                </h3>
                                <p class="mt-1 text-sm text-gray-800">
                                    Les champs suivis d'un astérisque (*) sont
                                    requis. Ces informations apparaitront
                                    publiquement sur ce site.
                                </p>
                            </div>
                        </div>
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <form
                                @submit.prevent="submit"
                                enctype="multipart/form-data"
                                autocomplete="off"
                            >
                                <div
                                    class="shadow-lg shadow-sky-700 sm:overflow-hidden sm:rounded-md"
                                >
                                    <div
                                        class="space-y-6 bg-white px-4 py-5 sm:p-6"
                                    >
                                        <div class="grid grid-cols-3 gap-6">
                                            <!-- Name -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="name"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Nom de la structure *
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        ref="name"
                                                        v-model="form.name"
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
                                                        @change="resetFields"
                                                        name="structuretype_id"
                                                        id="structuretype_id"
                                                        v-model="
                                                            form.structuretype_id
                                                        "
                                                        class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                    >
                                                        <option
                                                            v-for="structure in structurestypes"
                                                            :key="structure.id"
                                                            :value="
                                                                structure.id
                                                            "
                                                        >
                                                            {{ structure.name }}
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
                                                        structuretype, index
                                                    ) in props.structurestypes"
                                                    :key="structuretype.id"
                                                >
                                                    <div
                                                        v-for="(
                                                            attribut, idx
                                                        ) in structuretype.structuretypeattributs"
                                                        :key="attribut.id"
                                                    >
                                                        <div
                                                            v-if="
                                                                attribut.structuretype_id ===
                                                                form.structuretype_id
                                                            "
                                                            class="flex flex-col space-y-6"
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

                                        <BaseInfoForm
                                            :errors="errors"
                                            v-model:website="form.website"
                                            v-model:email="form.email"
                                            v-model:date_creation="
                                                form.date_creation
                                            "
                                            v-model:phone1="form.phone1"
                                            v-model:phone2="form.phone2"
                                            v-model:facebook="form.facebook"
                                            v-model:instagram="form.instagram"
                                            v-model:youtube="form.youtube"
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
                                                    rows="2"
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
                                                Description de votre structure
                                                en quelques lignes.
                                            </p>
                                            <div
                                                v-if="
                                                    errors.presentation_courte
                                                "
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ errors.presentation_courte }}
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
                                                Description de votre structure.
                                            </p>
                                            <div
                                                v-if="
                                                    errors.presentation_longue
                                                "
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ errors.presentation_longue }}
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
                                            <div class="flex items-center">
                                                <input
                                                    v-model="form.abo_news"
                                                    checked
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
                                            <div class="flex items-center">
                                                <input
                                                    v-model="form.abo_promo"
                                                    checked
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

                                    <!--buttons -->
                                    <div
                                        class="bg-gray-50 px-4 py-3 text-right sm:px-6"
                                    >
                                        <button
                                            :disabled="form.processing"
                                            type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            Enregistrer et ajouter une activité
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block" aria-hidden="true">
                    <div class="py-5">
                        <div class="border-t border-gray-200" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.vc-select select {
    background-image: unset;
}
</style>
