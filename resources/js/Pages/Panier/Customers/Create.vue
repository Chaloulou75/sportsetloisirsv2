<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import ResultLayout from "@/Layouts/ResultLayout.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import MazPhoneNumberInput from "maz-ui/components/MazPhoneNumberInput";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const Breadcrumb = defineAsyncComponent(() =>
    import("@/Components/Panier/Breadcrumb.vue")
);
const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);
const props = defineProps({
    errors: Object,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
    customer: Object,
});

const results = ref(null);
const page = usePage();
const user = computed(() => page.props.auth.user);

const customerForm = useForm({
    prenom: props.customer
        ? props.customer.prenom
        : user.value
        ? user.value.name
        : null,
    nom: props.customer ? props.customer.nom : null,
    adresse: props.customer ? props.customer.adresse : null,
    city: props.customer ? props.customer.city : null,
    zip_code: props.customer ? props.customer.zip_code : null,
    country: props.customer ? props.customer.country : null,
    phone: props.customer ? props.customer.phone : null,
});

const createCustomer = () => {
    customerForm.post(route("customers.store"), {
        preserveScroll: true,
        onSuccess: () => {
            customerForm.reset();
        },
    });
};
</script>

<template>
    <Head title="Coordonnées" description="Mes Coordonnées client" />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Coordonnées</template>
            </ResultsHeader>
        </template>

        <Breadcrumb />
        <div class="container mx-auto flex flex-col gap-4 py-6">
            <div
                class="mx-auto w-full max-w-full border border-gray-200 bg-gray-50 p-3 shadow-sm"
            >
                <p class="mb-6 text-sm font-medium text-gray-700">
                    Vous êtes connecté en tant que:
                    <span class="font-semibold">{{ user.name }}</span
                    >, veuillez remplir les informations suivantes pour la
                    création de votre compte client:
                </p>
                <form @submit.prevent="createCustomer" class="space-y-6">
                    <div
                        class="flex w-full flex-col space-y-6 md:flex-row md:space-x-4 md:space-y-0"
                    >
                        <div class="w-full">
                            <InputLabel
                                class="py-2"
                                for="prenom"
                                value="Prénom"
                            />
                            <TextInput
                                class="w-full"
                                type="text"
                                id="prenom"
                                name="prenom"
                                v-model="customerForm.prenom"
                            />
                        </div>
                        <div class="w-full">
                            <InputLabel class="py-2" for="nom" value="Nom" />
                            <TextInput
                                class="w-full"
                                type="text"
                                id="nom"
                                name="nom"
                                v-model="customerForm.nom"
                            />
                        </div>
                    </div>

                    <AddressForm
                        :errors="errors"
                        v-model:address="customerForm.adresse"
                        v-model:city="customerForm.city"
                        v-model:zip_code="customerForm.zip_code"
                        v-model:country="customerForm.country"
                    />
                    <!-- phone -->
                    <div>
                        <label
                            for="phone"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Numéro de téléphone
                        </label>
                        <div class="mt-1 flex w-full md:w-1/2">
                            <MazPhoneNumberInput
                                class="w-full max-w-2xl rounded-sm"
                                v-model="customerForm.phone"
                                color="primary"
                                size="sm"
                                :only-countries="['FR']"
                                @update="results = $event"
                                :success="results?.isValid"
                                :noSearch="true"
                                :noFlags="true"
                                :noCountrySelector="true"
                                :noExample="true"
                                :translations="{
                                    countrySelector: {
                                        placeholder: '',
                                        error: 'Choisir un pays',
                                    },
                                    phoneInput: {
                                        placeholder: '',
                                        example: '',
                                    },
                                }"
                            />
                        </div>
                        <div
                            v-if="errors.phone"
                            class="mt-2 text-xs text-red-500"
                        >
                            {{ errors.phone }}
                        </div>
                    </div>
                    <button
                        type="submit"
                        :disabled="customerForm.processing"
                        :class="{
                            'opacity-25': customerForm.processing,
                        }"
                        class="mx-auto flex w-full max-w-sm items-center justify-center self-end rounded-md border border-gray-200 bg-indigo-800 px-4 py-3 text-base text-white shadow hover:bg-indigo-900"
                    >
                        <LoadingSVG v-if="customerForm.processing" />
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </ResultLayout>
</template>
