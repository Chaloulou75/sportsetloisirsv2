<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import ResultsHeader from "@/Components/ResultsHeader.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import Breadcrumb from "@/Components/Panier/Breadcrumb.vue";
import MazPhoneNumberInput from "maz-ui/components/MazPhoneNumberInput";
import LoadingSVG from "@/Components/SVG/LoadingSVG.vue";

const AddressForm = defineAsyncComponent(() =>
    import("@/Components/Google/AddressForm.vue")
);

const props = defineProps({
    errors: Object,
    user: Object,
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
});

const results = ref();

const coordonneesForm = useForm({
    user: props.user ?? null,
    name: props.user?.name ?? null,
    email: props.user?.email ?? null,
    address: null,
    city: null,
    zip_code: null,
    country: null,
    phone: null,
    to_offer: false,
    name_receiver: null,
    email_receiver: null,
    phone_receiver: null,
});

watch(
    () => coordonneesForm.to_offer,
    (newValue) => {
        console.log(newValue);
        if (newValue === false) {
            coordonneesForm.name_receiver = null;
            coordonneesForm.email_receiver = null;
            coordonneesForm.phone_receiver = null;
        }
    }
);

const onSubmit = () => {
    coordonneesForm.post(route("panier.coordonnees.store"), {
        preserveScroll: true,
    });
};
</script>
<template>
    <Head
        title="Coordonnées de facturation"
        description="Mes Coordonnées de facturation"
    />

    <ResultLayout
        :familles="familles"
        :list-disciplines="listDisciplines"
        :all-cities="allCities"
    >
        <template #header>
            <ResultsHeader>
                <template v-slot:title> Mes coordonnées</template>
            </ResultsHeader>
        </template>

        <Breadcrumb />
        <form @submit.prevent="onSubmit" autocomplete="off">
            <div class="container mx-auto flex flex-col gap-4 py-6">
                <div class="w-full space-y-4 px-2 md:px-0">
                    <h2 class="text-center text-lg font-semibold md:text-left">
                        Remplissez vos informations:
                    </h2>
                    <div>
                        <InputLabel for="name" value="Nom complet*" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full max-w-sm"
                            v-model="coordonneesForm.name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError
                            class="mt-2"
                            :message="coordonneesForm.errors.name"
                        />
                    </div>
                    <div>
                        <InputLabel for="email" value="Email*" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full max-w-sm"
                            v-model="coordonneesForm.email"
                            required
                            autocomplete="username"
                        />

                        <InputError
                            class="mt-2"
                            :message="coordonneesForm.errors.email"
                        />
                    </div>
                    <div>
                        <InputLabel for="phone" value="Numéro de téléphone*" />
                        <div class="mt-1 flex w-full max-w-sm">
                            <MazPhoneNumberInput
                                class="w-full rounded-md border-gray-300 shadow-sm"
                                v-model="coordonneesForm.phone"
                                color="primary"
                                size="sm"
                                country-locale="fr-FR"
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
                            v-if="coordonneesForm.errors.phone"
                            class="mt-2 text-xs text-red-500"
                        >
                            {{ coordonneesForm.errors.phone }}
                        </div>
                    </div>
                    <AddressForm
                        :errors="errors"
                        v-model:address="coordonneesForm.address"
                        v-model:city="coordonneesForm.city"
                        v-model:zip_code="coordonneesForm.zip_code"
                        v-model:country="coordonneesForm.country"
                    />
                    <div class="flex">
                        <input
                            v-model="coordonneesForm.to_offer"
                            type="checkbox"
                            class="mt-0.5 shrink-0 rounded border-gray-200 text-blue-600 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50"
                            id="hs-default-checkbox"
                        />
                        <label
                            for="hs-default-checkbox"
                            class="ms-3 text-sm text-gray-500"
                            >C'est pour offrir ?</label
                        >
                    </div>
                    <template v-if="coordonneesForm.to_offer">
                        <p class="text-sm font-semibold text-gray-700">
                            Coordonnées de la personne qui reçoit votre cadeau:
                        </p>
                    </template>
                    <div v-if="coordonneesForm.to_offer === true">
                        <InputLabel
                            for="name_receiver"
                            value="Nom complet du recevant"
                        />

                        <TextInput
                            id="name_receiver"
                            type="text"
                            class="mt-1 block w-full max-w-sm"
                            v-model="coordonneesForm.name_receiver"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError
                            class="mt-2"
                            :message="coordonneesForm.errors.name_receiver"
                        />
                    </div>

                    <div v-if="coordonneesForm.to_offer">
                        <InputLabel
                            for="email_receiver"
                            value="Email du recevant"
                        />

                        <TextInput
                            id="email_receiver"
                            type="email"
                            class="mt-1 block w-full max-w-sm"
                            v-model="coordonneesForm.email_receiver"
                            required
                            autocomplete="email"
                        />

                        <InputError
                            class="mt-2"
                            :message="coordonneesForm.errors.email_receiver"
                        />
                    </div>
                    <div v-if="coordonneesForm.to_offer">
                        <InputLabel
                            for="phone_receiver"
                            value="Numéro de téléphone du recevant*"
                        />
                        <div class="mt-1 flex w-full max-w-sm">
                            <MazPhoneNumberInput
                                class="w-full rounded-md border-gray-300 shadow-sm"
                                v-model="coordonneesForm.phone_receiver"
                                color="primary"
                                size="sm"
                                country-locale="fr-FR"
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
                            v-if="coordonneesForm.errors.phone_receiver"
                            class="mt-2 text-xs text-red-500"
                        >
                            {{ coordonneesForm.errors.phone_receiver }}
                        </div>
                    </div>
                </div>

                <div
                    class="mx-auto w-full max-w-sm self-end text-base font-bold text-gray-700 md:mx-0"
                >
                    <button
                        :disabled="coordonneesForm.processing"
                        :class="{
                            'opacity-25': coordonneesForm.processing,
                        }"
                        type="submit"
                        class="mt-4 inline-flex w-full items-center justify-center rounded-md bg-blue-700 px-4 py-2.5 font-medium text-blue-50 shadow hover:bg-blue-600"
                    >
                        <LoadingSVG v-if="coordonneesForm.processing" />
                        Réserver
                    </button>
                </div>
            </div>
        </form>
    </ResultLayout>
</template>
