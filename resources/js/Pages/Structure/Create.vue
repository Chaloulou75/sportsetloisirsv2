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
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch, defineAsyncComponent } from "vue";

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const props = defineProps({
    structuresType: Object,
    errors: Object,
});

const form = useForm({
    name: ref(null),
    firstname: ref(null),
    lastname: ref(null),
    structuretype_id: ref(null),
    category_id: ref(null),
    email: ref(null),
    website: ref(null),
    phone: ref(null),
    facebook: ref(null),
    instagram: ref(null),
    youtube: ref(null),
    address: ref(null),
    city: ref(null),
    zip_code: ref(null),
    country: ref(null),
    address_lat: ref(null),
    address_lng: ref(null),
    description: ref(null),
    disciplines: ref([]),
    activites: ref([]),
});

const formStep = ref(1);
const name = ref(null);
const categories = ref([]);
const disciplinesList = ref([]);
const activiteSportsList = ref([]);

const getCategories = async () => {
    let response = await axios.get("/api/categories");
    categories.value = response.data.data;
};

onMounted(() => {
    name.value.focus();
    getCategories();
});

watch(
    () => form.category_id,
    async (newCategoryID) => {
        axios
            .get("/api/disciplines?category_id=" + newCategoryID)
            .then((response) => {
                disciplinesList.value = response.data.data;
            })
            .catch((e) => {
                console.log(e);
            });
    }
);

function nextStep() {
    formStep.value++;
}

function prevStep() {
    formStep.value--;
}

function submit() {
    form.post("/structure");
}

function addActivite() {
    form.activites.push({
        activite_name: "",
        activite_category_id: "",
    });
}

function removeActivite(index) {
    form.activites.splice(index, 1);
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
                            <!-- formstep 1 -->
                            <div v-if="formStep == 1" class="px-4 sm:px-0">
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-700"
                                >
                                    Inscription et Profil social de votre
                                    structure
                                </h3>
                                <p class="mt-1 text-sm text-gray-800">
                                    Ces informations apparaitront publiquement
                                    sur ce site.
                                </p>
                            </div>
                            <!-- formstep 2 -->
                            <div v-if="formStep == 2" class="px-4 sm:px-0">
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-700"
                                >
                                    Ajouter une activité ponctuelle
                                </h3>
                                <p class="mt-1 text-sm text-gray-800">
                                    Ces informations apparaitront publiquement
                                    sur ce site.
                                </p>
                            </div>

                            <StepsIndicator :formStep="formStep" />
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
                                        v-if="formStep == 1"
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
                                                    Nom de la structure
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
                                            <!-- Prenom -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="firstname"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Prénom
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        ref="firstName"
                                                        v-model="form.firstname"
                                                        type="text"
                                                        name="firstname"
                                                        id="firstname"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.firstname"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.firstname }}
                                                </div>
                                            </div>
                                            <!-- Nom -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="lastname"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Nom
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        v-model="form.lastname"
                                                        type="text"
                                                        name="lastname"
                                                        id="lastname"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.lastname"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.lastname }}
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
                                                    Type de structure
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
                                                            v-for="structure in structuresType"
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
                                            v-if="form.structuretype_id === 3"
                                            label="Addresse du Club"
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
                                                    <span class="text-xs italic"
                                                        >(url complète)</span
                                                    >
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md shadow-sm"
                                                >
                                                    <span
                                                        class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-xs text-gray-500"
                                                    >
                                                        https://...
                                                    </span>
                                                    <input
                                                        v-model="form.website"
                                                        type="text"
                                                        name="website"
                                                        id="website"
                                                        class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        placeholder="https://www.exemple.com"
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
                                                    Email du club
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md shadow-sm"
                                                >
                                                    <input
                                                        v-model="form.email"
                                                        type="email"
                                                        name="email"
                                                        id="email"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        placeholder="Club ..."
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

                                            <!-- Phone -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="phone"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Numéro de téléphone
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md shadow-sm"
                                                >
                                                    <input
                                                        v-model="form.phone"
                                                        type="tel"
                                                        name="phone"
                                                        id="phone"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        placeholder="+33 1 42 ..."
                                                        autocomplete="none"
                                                    />
                                                </div>
                                                <div
                                                    v-if="errors.phone"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.phone }}
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
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        v-model="form.facebook"
                                                        type="text"
                                                        name="facebook"
                                                        id="facebook"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
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
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        v-model="form.instagram"
                                                        type="text"
                                                        name="instagram"
                                                        id="instagram"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
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
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        v-model="form.youtube"
                                                        type="text"
                                                        name="youtube"
                                                        id="youtube"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
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

                                            <!-- categorie -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="category_id"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Catégorie
                                                </label>
                                                <div class="mt-1">
                                                    <select
                                                        name="category_id"
                                                        id="category_id"
                                                        v-model="
                                                            form.category_id
                                                        "
                                                        class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                    >
                                                        <option
                                                            v-for="category in categories"
                                                            :key="category.id"
                                                            :value="category.id"
                                                        >
                                                            {{ category.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div
                                                    v-if="errors.category_id"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.category_id }}
                                                </div>
                                            </div>

                                            <!-- disciplines -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="disciplines"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Disciplines pratiquées dans
                                                    votre structure
                                                    <span
                                                        class="text-xs text-gray-600"
                                                        >(Selectionnez une ou
                                                        plusieurs
                                                        disciplines)</span
                                                    >
                                                </label>
                                                <div class="mt-1">
                                                    <select
                                                        name="disciplines"
                                                        id="disciplines"
                                                        v-model="
                                                            form.disciplines
                                                        "
                                                        @keydown.enter.prevent="
                                                            enterAfterDisciplines
                                                        "
                                                        class="block h-64 w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                        multiple
                                                    >
                                                        <option
                                                            v-for="discipline in disciplinesList"
                                                            :key="discipline.id"
                                                            :value="discipline"
                                                        >
                                                            {{
                                                                discipline.name
                                                            }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div
                                                    class="mt-2 text-xs text-gray-700"
                                                >
                                                    Disciplines sélectionnées:
                                                    <span
                                                        v-for="discipline in form.disciplines"
                                                        :key="discipline.id"
                                                        class="ml-1 text-sm font-semibold text-gray-700"
                                                        >{{ discipline.name }}
                                                        &bullet;
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="errors.disciplines"
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{ errors.disciplines }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label
                                                for="description"
                                                class="block text-sm font-medium text-gray-700"
                                            >
                                                Description
                                            </label>
                                            <div class="mt-1">
                                                <textarea
                                                    v-model="form.description"
                                                    id="description"
                                                    name="description"
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
                                                Description de votre structure
                                                en quelques lignes.
                                            </p>
                                            <div
                                                v-if="errors.description"
                                                class="mt-2 text-xs text-red-500"
                                            >
                                                {{ errors.description }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- formstep 2 -->
                                    <div
                                        v-if="formStep == 2"
                                        class="space-y-6 bg-white px-4 py-5 sm:p-6"
                                    >
                                        <button
                                            type="button"
                                            @click="addActivite"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            Ajouter une activité
                                        </button>
                                        <div
                                            v-for="(
                                                activite, index
                                            ) in form.activites"
                                            :key="index"
                                            class="grid grid-cols-3 gap-6"
                                        >
                                            <!-- activité -->
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <!-- Nom activité -->
                                                <label
                                                    for="activite_name"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Nom de l'activité
                                                </label>
                                                <div
                                                    class="mt-1 flex rounded-md"
                                                >
                                                    <input
                                                        ref="activiteName"
                                                        v-model="
                                                            activite.activite_name
                                                        "
                                                        type="text"
                                                        name="activite_name"
                                                        id="activite_name"
                                                        class="block w-full flex-1 rounded-md border-gray-300 placeholder-gray-400 placeholder-opacity-25 shadow-sm sm:text-sm"
                                                        placeholder=""
                                                        autocomplete="none"
                                                    />
                                                    <button
                                                        type="button"
                                                        @click="
                                                            removeActivite(
                                                                index
                                                            )
                                                        "
                                                        class="ml-6 inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                            />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <!-- <div
                                                    v-if="
                                                        errors.activite
                                                            .activite_name
                                                    "
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{
                                                        errors.activite
                                                            .activite_name
                                                    }}
                                                </div> -->
                                            </div>
                                            <div
                                                class="col-span-3 sm:col-span-2"
                                            >
                                                <label
                                                    for="activite_category_id"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    Domaine d'activité
                                                </label>
                                                <div class="mt-1">
                                                    <select
                                                        name="activite_category_id"
                                                        id="activite_category_id"
                                                        v-model="
                                                            activite.activite_category_id
                                                        "
                                                        class="block w-full rounded-lg border-gray-300 text-sm text-gray-800 shadow-sm"
                                                    >
                                                        <option
                                                            v-for="category in categories"
                                                            :key="category.id"
                                                            :value="category.id"
                                                        >
                                                            {{ category.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <!-- <div
                                                    v-if="
                                                        errors.activite
                                                            .activite_category_id
                                                    "
                                                    class="mt-2 text-xs text-red-500"
                                                >
                                                    {{
                                                        errors.activite
                                                            .activite_category_id
                                                    }}
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!--buttons formstep 1 -->
                                    <div
                                        v-if="formStep == 1"
                                        class="bg-gray-50 px-4 py-3 text-right sm:px-6"
                                    >
                                        <button
                                            @click="nextStep"
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            Ajouter une activité ponctuelle
                                        </button>
                                    </div>
                                    <!--buttons formstep 2 -->
                                    <div
                                        v-if="formStep == 2"
                                        class="bg-gray-50 px-4 py-3 text-right sm:px-6"
                                    >
                                        <div
                                            class="flex w-full items-center justify-between"
                                        >
                                            <button
                                                type="button"
                                                @click="prevStep"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                            >
                                                Précédent
                                            </button>
                                            <button
                                                :disabled="form.processing"
                                                type="submit"
                                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                            >
                                                Enregistrer
                                            </button>
                                        </div>
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
