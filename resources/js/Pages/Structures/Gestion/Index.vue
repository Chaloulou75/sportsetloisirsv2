<script setup>
import ProLayout from "@/Layouts/ProLayout.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import { ref, computed, defineAsyncComponent } from "vue";
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
        `/url a definir`,
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
                emit("close");
            },
            structure: props.structure.slug,
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
            <div class="px-2 py-6 text-gray-700 md:px-4">
                <h3 class="text-2xl">
                    Bienvenue
                    <span class="text-indigo-700">{{ user.name }}</span>
                </h3>
                <p class="text-lg">Vos demandes de réservations:</p>
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
                                        @click="updateAdresse(adresse)"
                                    >
                                        <ArrowPathIcon
                                            class="h-5 w-5 text-blue-500"
                                        />
                                    </button>
                                    <button
                                        type="button"
                                        @click="deleteAdresse(adresse)"
                                    >
                                        <TrashIcon
                                            class="h-5 w-5 text-red-500"
                                        />
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <div class="flex items-center justify-end">
                            <button
                                class="group flex items-center justify-center rounded-md border border-gray-200 px-4 py-2.5 text-sm hover:bg-blue-500"
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
