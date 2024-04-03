<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { ref, computed, defineAsyncComponent } from "vue";
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

const showGiftFields = ref(false);
const openGiftFields = () => {
    showGiftFields.value = !showGiftFields.value;
};

const coordonneesForm = useForm({
    user: props.user ?? null,
    name: props.user?.name ?? null,
    email: props.user?.email ?? null,
    address: null,
    city: null,
    zip_code: null,
    country: null,
    phone: null,
    to_offer: computed(() => showGiftFields.value),
    name_receiver: null,
    email_receiver: null,
});

const onSubmit = () => {
    coordonneesForm.post(route("panier.coordonnees.store"), {
        preserveScroll: true,
    });
};
</script>
<template>
    <Head title="Coordonnées" description="Mes Coordonnées" />

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

                    <button
                        class="rounded-sm bg-blue-700 px-3 py-2 text-sm text-white hover:bg-blue-600"
                        type="button"
                        @click.prevent="openGiftFields"
                    >
                        <span v-if="!showGiftFields">C'est pour offrir ?</span
                        ><span v-else>C'est pour vous?</span>
                    </button>
                    <template v-if="showGiftFields">
                        <p class="text-sm font-semibold text-gray-700">
                            Coordonnées de la personne qui reçoit votre cadeau:
                        </p>
                    </template>
                    <div v-if="showGiftFields">
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

                    <div v-if="showGiftFields">
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
