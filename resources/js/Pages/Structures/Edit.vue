<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, onMounted, computed, defineAsyncComponent } from "vue";
// import InscriptionNavigation from "@/Components/Navigation/InscriptionNavigation.vue";
import PathsInscriptionNavigation from "@/Components/Navigation/PathsInscriptionNavigation.vue";

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
    errors: Object,
    can: Object,
});

const showSidebar = ref(false);

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
    // getFamilles();
});

function submit() {
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
}
</script>

<template>
    <Head title="Editer votre structure" />

    <ProLayout :structure="structure" :can="can">
        <template #header>
            <h1
                class="w-full py-6 text-center text-xl font-semibold leading-tight text-gray-800"
            >
                Editer votre structure
                <span class="text-blue-700">{{ structure.name }}</span>
            </h1>
        </template>
        <div
            class="relative flex flex-col space-y-6 py-2 md:flex-row md:space-x-6 md:space-y-0 md:py-8"
        >
            <!-- <InscriptionNavigation
                :can="can"
                :structure="structure"
                class="hidden md:flex"
            /> -->
            <div class="flex-1">
                <PathsInscriptionNavigation />
                <!-- <button
                    @click="showSidebar = !showSidebar"
                    class="my-2 inline-flex w-full items-center justify-end self-end rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:text-gray-500 focus:text-gray-500 focus:outline-none md:hidden"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            :class="{
                                hidden: showSidebar,
                                'inline-flex': !showSidebar,
                            }"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{
                                hidden: !showSidebar,
                                'inline-flex': showSidebar,
                            }"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
                <InscriptionNavigation
                    v-if="showSidebar"
                    :can="can"
                    :structure="structure"
                    class="my-4 flex md:hidden"
                /> -->
                <div class="mx-auto max-w-full lg:px-4">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-3">
                            <!--  -->
                            <div class="px-4 sm:px-0">
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-700"
                                >
                                    Edition et Profil social de votre structure
                                </h3>
                                <p class="mt-1 text-sm text-gray-800">
                                    Les champs suivis d'un astérisque (*) sont
                                    requis. Ces informations apparaitront
                                    publiquement sur ce site.
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
                                                    class="flex flex-col space-y-6"
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
                                                    :checked="isAboNewsChecked"
                                                    @change="
                                                        form.abo_news = $event
                                                            .target.checked
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
                                            <div class="flex items-center">
                                                <input
                                                    :checked="isAboPromoChecked"
                                                    @change="
                                                        form.abo_promo = $event
                                                            .target.checked
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

                <div class="hidden sm:block" aria-hidden="true">
                    <div class="py-5">
                        <div class="border-t border-gray-200" />
                    </div>
                </div>
            </div>
        </div>
    </ProLayout>
</template>

<style>
.vc-select select {
    background-image: unset;
}
</style>
