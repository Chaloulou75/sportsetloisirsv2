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
    structure: Object,
    errors: Object,
});

const form = useForm({
    name: ref(props.structure.name),
    structuretype_id: ref(props.structure.structuretype_id),
    address: ref(props.structure.address),
    city: ref(props.structure.city),
    zip_code: ref(props.structure.zip_code),
    country: ref(props.structure.country),
    address_lat: ref(props.structure.address_lat),
    address_lng: ref(props.structure.address_lng),
    // category_id: ref(null),
    email: ref(props.structure.email),
    website: ref(props.structure.website),
    phone: ref(props.structure.phone),
    facebook: ref(props.structure.facebook),
    instagram: ref(props.structure.instagram),
    youtube: ref(props.structure.youtube),
    description: ref(props.structure.description),
});

const formStep = ref(1);
const name = ref(null);
const categories = ref([]);
const disciplinesList = ref([]);

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

// function nextStep() {
//     formStep.value++;
// }

// function prevStep() {
//     formStep.value--;
// }

function submit() {
    form.patch(route("structures.update", props.structure));
}

function enterAfterDisciplines() {
    document.getElementById("address").focus();
}
</script>

<template>
    <Head title="Editer votre structure" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Editer votre structure
                <span class="text-blue-700">{{ structure.name }}</span>
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
                                    Edition et Profil social de votre structure
                                </h3>
                                <p class="mt-1 text-sm text-gray-800">
                                    Ces informations apparaitront publiquement
                                    sur ce site.
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
                                                    <span class="text-xs italic"
                                                        >(Attention, en
                                                        changeant le nom, vous
                                                        changez aussi le lien
                                                        d'accès à la page de
                                                        votre structure)</span
                                                    >
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
                                                        placeholder="02 10 ..."
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

                                    <!--buttons formstep 1 -->
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
                                        <!-- <button
                                            @click="nextStep"
                                            type="button"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            Ajouter une activité
                                        </button> -->
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
