<script setup>
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon } from "@heroicons/vue/24/solid";
import AppLayout from "@/Layouts/AppLayout.vue";
import LogoInput from "@/Components/LogoInput.vue";
import StepsIndicator from "@/Components/Inscription/StepsIndicator.vue";
import AutocompleteActiviteForm from "@/Components/Inscription/AutocompleteActiviteForm.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch, defineAsyncComponent } from "vue";

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const props = defineProps({
    structurestypes: Object,
    niveaux: Object,
    publictypes: Object,
    activitestypes: Object,
    disciplines: Object,
    errors: Object,
});

const form = useForm({
    name: ref(null),
    structuretype_id: ref(null),
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
    email: ref(null),
    website: ref(null),
    phone1: ref(null),
    phone2: ref(null),
    facebook: ref(null),
    instagram: ref(null),
    youtube: ref(null),
    tiktok: ref(null),
    presentation_courte: ref(null),
    presentation_longue: ref(null),
    logo: ref(null),
});

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

function enterAfterDisciplines() {
    document.getElementById("address").focus();
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
                                    <!-- formstep 1 -->
                                    <div
                                        class="px-4 py-5 space-y-6 bg-white sm:p-6"
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
                                                    class="flex mt-1 rounded-md"
                                                >
                                                    <input
                                                        ref="name"
                                                        v-model="form.name"
                                                        type="text"
                                                        name="name"
                                                        id="name"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
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
                                                        class="block w-full text-sm text-gray-800 border-gray-300 rounded-lg shadow-sm"
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

                                        <div class="grid grid-cols-3 gap-6">
                                            <!-- website -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="website"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Site web
                                                    <!-- <span class="text-xs italic"
                                                        >(url complète)</span
                                                    > -->
                                                </label>
                                                <div
                                                    class="flex mt-1 rounded-md shadow-sm"
                                                >
                                                    <span
                                                        class="inline-flex items-center px-3 text-xs text-gray-500 border border-r-0 border-gray-300 rounded-l-md bg-gray-50"
                                                    >
                                                        https://...
                                                    </span>
                                                    <input
                                                        v-model="form.website"
                                                        type="text"
                                                        name="website"
                                                        id="website"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-none rounded-r-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        placeholder="www.exemple.com"
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.website"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.website }}
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="email"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Email *
                                                </label>
                                                <div
                                                    class="flex mt-1 rounded-md shadow-sm"
                                                >
                                                    <input
                                                        v-model="form.email"
                                                        type="email"
                                                        name="email"
                                                        id="email"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        placeholder="structure@mail.com"
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.email"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.email }}
                                                </div>
                                            </div>

                                            <!-- Phone1 -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="phone1"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Numéro de téléphone *
                                                </label>
                                                <div
                                                    class="flex mt-1 rounded-md shadow-sm"
                                                >
                                                    <input
                                                        v-model="form.phone1"
                                                        type="tel"
                                                        name="phone1"
                                                        id="phone1"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        placeholder="02 10 ..."
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.phone1"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.phone1 }}
                                                </div>
                                            </div>

                                            <!-- Phone2 -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="phone2"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Numéro de téléphone de
                                                    sauvegarde
                                                </label>
                                                <div
                                                    class="flex mt-1 rounded-md shadow-sm"
                                                >
                                                    <input
                                                        v-model="form.phone2"
                                                        type="tel"
                                                        name="phone2"
                                                        id="phone2"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-50 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        placeholder="02 10 ..."
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.phone2"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.phone2 }}
                                                </div>
                                            </div>

                                            <!-- Facebook -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="facebook"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Facebook
                                                </label>
                                                <div
                                                    class="flex mt-1 rounded-md"
                                                >
                                                    <input
                                                        v-model="form.facebook"
                                                        type="text"
                                                        name="facebook"
                                                        id="facebook"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.facebook"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.facebook }}
                                                </div>
                                            </div>

                                            <!-- Instagram -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="instagram"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Instagram
                                                </label>
                                                <div
                                                    class="flex mt-1 rounded-md"
                                                >
                                                    <input
                                                        v-model="form.instagram"
                                                        type="text"
                                                        name="instagram"
                                                        id="instagram"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.instagram"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.instagram }}
                                                </div>
                                            </div>

                                            <!-- youtube -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="youtube"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Youtube
                                                </label>
                                                <div
                                                    class="flex mt-1 rounded-md"
                                                >
                                                    <input
                                                        v-model="form.youtube"
                                                        type="text"
                                                        name="youtube"
                                                        id="youtube"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.youtube"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.youtube }}
                                                </div>
                                            </div>
                                            <!-- tiktok -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="tiktok"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Tiktok
                                                </label>
                                                <div
                                                    class="flex mt-1 rounded-md"
                                                >
                                                    <input
                                                        v-model="form.tiktok"
                                                        type="text"
                                                        name="tiktok"
                                                        id="tiktok"
                                                        class="flex-1 block w-full placeholder-gray-400 placeholder-opacity-25 border-gray-300 rounded-md shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.tiktok"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.tiktok }}
                                                </div>
                                            </div>
                                        </div>

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
                                                    class="block w-full h-48 min-h-full mt-1 placeholder-gray-400 placeholder-opacity-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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
                                                    class="block w-full h-48 min-h-full mt-1 placeholder-gray-400 placeholder-opacity-50 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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
                                    </div>

                                    <!--buttons -->
                                    <div
                                        class="px-4 py-3 text-right bg-gray-50 sm:px-6"
                                    >
                                        <button
                                            :disabled="form.processing"
                                            type="submit"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
