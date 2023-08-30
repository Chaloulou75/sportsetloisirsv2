<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import { ref, computed, watch, defineAsyncComponent } from "vue";
import PathsInscriptionNavigation from "@/Components/Navigation/PathsInscriptionNavigation.vue";
import {
    ArrowPathIcon,
    PlusIcon,
    TrashIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";
const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    errors: Object,
    structure: Object,
    can: Object,
});

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
            console.log(newAdresse);
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
</script>
<template>
    <Head
        title="Gestion de votre structure"
        :description="'Gestion de votre structure, disciplines et activités.'"
    />
    <ProLayout :structure="structure" :can="can">
        <template #header>
            <h1
                class="text-xl font-semibold leading-tight tracking-widest text-gray-700"
            >
                Accueil
            </h1>
        </template>

        <template #default="{ displayAdresses, handleCloseEvent }">
            <div class="space-y-10 px-2 py-6 text-gray-700 md:px-4">
                <h3 class="text-2xl">
                    Bienvenue
                    <span class="text-indigo-700">{{ user.name }}</span>
                </h3>
                <p class="text-xl font-semibold">
                    Vos demandes de réservations en attente:
                </p>
                <ul>
                    <!-- <li v-for="reservation in "></li> -->
                </ul>

                <template v-if="displayAdresses">
                    <div
                        class="my-4 flex w-full flex-col space-y-6 rounded-md border border-gray-200 bg-gray-50 px-4 py-2 text-gray-800 shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold">Vos adresses:</h3>
                            <button type="button" @click="handleCloseEvent">
                                <XCircleIcon class="h-7 w-7 text-red-500" />
                            </button>
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
                                            displayUpdateAdresseForm(adresse)
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
                                v-model:zip_code="updateAddressForm.zip_code"
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
                                v-model:address_lat="addressForm.address_lat"
                                v-model:address_lng="addressForm.address_lng"
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
            </div>
        </template>
    </ProLayout>
</template>
